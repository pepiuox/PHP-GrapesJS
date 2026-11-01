# 03 — Autenticación y sesiones

Requisito del usuario, verificado en código y en datos:

> El usuario necesita **correo electrónico, contraseña y PIN**. El identificador del usuario
> **cambia en cada ingreso**.

## Flujo de acceso (`core/controller/UsersClass.php` → `Login()`)

1. `POST /signin/login` con `email`, `password`, `PIN`, `remember` y `csrf_token`.
2. Validación CSRF con `PepiuoX\Security\Csrf` (campo oculto del formulario).
3. Validación de forma: correo no vacío, contraseña no vacía, PIN no vacío.
4. Lectura de las claves del sitio desde `site_security` (`SECURE_TOKEN`, `SECURE_HASH`).
5. Cifrado del correo y del PIN **con las claves del sitio** y búsqueda:
   `SELECT * FROM uverify WHERE email = :e AND mkpin = :p AND is_activate = 1`.
6. Comprobaciones de estado: solicitud de recuperación pendiente, cuenta bloqueada,
   correspondencia con `users_active.is_active`, `is_activate` e `is_banned`.
7. Descifrado del par usuario/contraseña con las claves **por usuario** (`mktoken`, `mkkey`).
8. Comparación en tiempo constante (`hash_equals`) de la contraseña.
9. Lectura de la ficha en `users` por clave primaria (`idUser = uverify.iduv`).
10. Transacción atómica de rotación de identificador:
    - `UPDATE uverify SET iduv = :nuevo, mkhash = :nuevo`
    - `UPDATE users_profiles SET mkhash = :nuevo WHERE idp = :nuevo`
    - Las FK `ON UPDATE CASCADE` propagan el identificador nuevo a `users`, `users_info`,
      `users_csp`, `users_privacy` y `users_secures`.
11. Sesión: `access_id`, `username`, `user_id` (el nuevo), `levels`, `hash`, `email`,
    `logged_in = true`. Los contadores de intento se limpian.
12. Si algo falla dentro de la transacción: `rollBack()`, `session_destroy()` y mensaje de error.

## Dónde están los datos y en qué formato

| Dato | Tabla.columnas | Cifrado | Clave |
|---|---|---|---|
| Correo, usuario, PIN | `uverify.email/username/mkpin` | AES-256-CBC determinista (tras la corrección) | del sitio |
| Contraseña | `uverify.password`, `users.password` | AES-256-CBC determinista con IV derivado | por usuario |
| Verificación de sesión | `uverify.mkhash`, `users_profiles.mkhash` | no (hash aleatorio en hexadecimal) | — |
| Claves del sitio | `site_security.SECURE_TOKEN / SECURE_HASH` | no | — |

**Es obligatorio que el cifrado sea determinista**: el acceso busca por igualdad
(`WHERE email = :e AND mkpin = :p`). Un IV aleatorio por operación hace que esa consulta no
encuentre nunca la fila. Ese fue el defecto H-02.

## Rotación del identificador

`iduv` (64 caracteres hexadecimales) se reemplaza en cada acceso. Efectos verificados:

- La sesión guarda el identificador nuevo en `user_id`.
- Las vistas consultan con ese valor:
  `core/users/personalInfo.php` → `users_profiles WHERE idp = ? AND mkhash = ?`.
- Las claves foráneas `ON UPDATE CASCADE` mantienen la coherencia sin código adicional.

Consecuencia aceptada: cualquier referencia externa al identificador anterior (enlaces
guardados, registros de auditoría) deja de resolver. Se documenta en el ADR-004.

## Cierre de sesión

`GET|POST /signin/logout` → `UsersClass::logout()` destruye la sesión y borra las cookies de
"recordarme". Antes solo se atendía `POST`, de modo que el enlace de salida del sitio dejaba la
sesión viva (hallazgo H-05).

## Verificación ejecutada

`php tools/verify-login-http.php` reproduce un acceso real con las credenciales de la base
(descifradas en memoria, nunca impresas) y comprueba los ocho pasos: formulario, token CSRF,
envío, ausencia de error fatal, sesión iniciada, rotación del identificador, propagación por FK
y cierre de sesión. Resultado actual: **8/8 PASS**.