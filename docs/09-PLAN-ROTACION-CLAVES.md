# 09 — Plan de rotación de claves y re-cifrado

**Estado: PLANIFICADO. No ejecutado.** La rotación toca credenciales de acceso y datos
cifrados de usuarios reales: necesita autorización explícita y ventana de mantenimiento.
Lo que ya está hecho es el **inventario verificado** y el instrumento que ejecuta el plan.

## Por qué

`core/config/define.php` y `core/config/server.php` están versionados en un repositorio
público y contienen, respectivamente, las claves de cifrado del sitio y las credenciales de
MariaDB (hallazgos H-07 y H-08). Rotar no es opcional: es la única forma de que lo publicado
deje de servir.

## Inventario real (ejecutado el 2026-10-17)

`php tools/rotate-keys.php` — salida real:

| Tabla | Columna | Clave | Filas | Descifrables |
|---|---|---|---|---|
| `uverify` | `username` | sitio | 2 | 2 |
| `uverify` | `email` | sitio | 2 | 2 |
| `uverify` | `mkpin` | sitio | 2 | 2 |
| `users` | `mkpin` | sitio | 2 | 2 |
| `site_security` | `USEREMAIL` | sitio | 1 | **0** |
| `site_security` | `PASSMAIL` | sitio | 1 | **0** |
| `uverify` | `password` | usuario (`mktoken`/`mkkey`) | 2 | — |
| `users` | `username`, `email`, `password`, `mkpin` | usuario / sitio | 2 | — |

Dos consecuencias directas:

1. **Las credenciales de correo SMTP están en claro** en `site_security` (no descifran porque
   nunca se cifraron). Al rotar hay que cifrarlas, no re-cifrarlas.
2. El resto de columnas con claves del sitio descifra al 100 %, así que el re-cifrado es
   viable sin pérdida de datos.

## Alcance

| Secreto | Dónde vive | Acción |
|---|---|---|
| `SECURE_TOKEN`, `SECURE_HASH` | `site_security` (uso real) y `core/config/define.php` (copia versionada) | Rotar en base de datos, re-cifrar dependientes y **eliminar de `define.php`** |
| Usuario y contraseña de MariaDB | `core/config/server.php` (versionado) | Rotar contraseña, mover a `.env` no versionado |
| Usuario y contraseña SMTP | `site_security.USEREMAIL` / `PASSMAIL` (en claro) | Cifrar con las claves nuevas |

## Prerrequisitos (bloqueantes)

1. Respaldo de la base de datos de menos de una hora: `php tools/backup-db.php`
   (el propio instrumento de rotación rechaza `--apply` sin él).
2. Copia de `core/config/define.php` y `core/config/server.php` fuera del repositorio.
3. Sitio en mantenimiento: nadie iniciando sesión ni registrándose durante la migración.
4. Inventario sin pendientes: `php tools/rotate-keys.php` debe informar 0 filas ilegibles
   en las columnas con claves del sitio (hoy: 2, y son las de correo, que se cifrarán aparte).

## Procedimiento

    # 1. Respaldo
    php tools/backup-db.php

    # 2. Inventario (solo lectura)
    php tools/rotate-keys.php

    # 3. Rotación + re-cifrado, en una transacción
    php tools/rotate-keys.php --apply --confirm=ROTAR

    # 4. Verificación funcional
    php tools/verify-login.php
    php tools/verify-login-http.php

    # 5. Sacar los secretos del repositorio
    #    - borrar SECURE_TOKEN y SECURE_HASH de core/config/define.php
    #    - crear .env con DB_HOST/DB_NAME/DB_USER/DB_PASS y leerlo desde server.php
    #    - añadir core/config/server.php y .env a .gitignore

    # 6. Rotar la contraseña de MariaDB
    #    ALTER USER 'pepiuox'@'localhost' IDENTIFIED BY '...';  (con el usuario de la app)

    # 7. Invalidar sesiones y forzar re-acceso (el script ya vacía `users_active`)

    # 8. Registrar el cambio en docs/07-DECISIONES-ADR.md (ADR-007)

## Qué hace exactamente `--apply`

1. Exige `--confirm=ROTAR` y un respaldo de la última hora; si no, se niega a ejecutar.
2. Genera `SECURE_TOKEN` y `SECURE_HASH` nuevos con `random_bytes(64)` (128 hex cada uno).
3. Guarda las claves **anteriores** en `storage/backups/keys-<fecha>.txt`, fuera del control de
   versiones, para poder descifrar una copia restaurada.
4. Dentro de una transacción: descifra cada valor con las claves viejas, lo cifra con las nuevas
   y lo actualiza por el valor anterior (idempotente: si se repite, no encuentra qué cambiar).
5. Actualiza `site_security` y vacía `users_active`.
6. Si cualquier paso falla: `rollBack()` y las claves antiguas siguen vigentes.

## Verificación posterior (criterios de aceptación)

- `tools/rotate-keys.php` (inventario) informa 0 filas ilegibles.
- `tools/verify-login.php` → 15/15 PASS con las claves nuevas.
- `tools/verify-login-http.php` → 8/8 PASS (acceso real, rotación de id y logout).
- `tools/http-smoke.php` → 8/8 rutas OK.
- Ningún archivo versionado contiene `SECURE_TOKEN`, `SECURE_HASH` ni contraseñas:
  comprobación con `git grep` en el commit resultante.

## Rollback

| Escenario | Acción |
|---|---|
| Fallo durante el re-cifrado | No hay nada que hacer: la transacción revierte |
| Rotación completada y algo deja de funcionar | Restaurar `storage/backups/backup-<fecha>.sql` y las claves de `keys-<fecha>.txt` en `site_security` |
| Rotación completada y se pierde el archivo de claves viejas | Restaurar el respaldo completo: claves y datos vuelven juntos |

## Riesgos

1. **Re-cifrado parcial** si alguien escribe durante la migración: por eso el paso 3 exige
   mantenimiento. Mitigación adicional: la tabla `users_active` se vacía al final, lo que fuerza
   un nuevo acceso limpio.
2. **Pérdida del archivo de claves anteriores**: sin él, una copia restaurada no se puede leer.
   Se guarda junto al respaldo y se documenta su ubicación.
3. **Rotación sin re-cifrado**: dejaría ilegibles correo, usuario y PIN. El instrumento se niega
   a aplicar si hay valores que no descifran.
4. **Password de MariaDB**: rotarla sin actualizar `server.php` deja la aplicación caída; debe
   hacerse con el sitio en mantenimiento y verificando después con `tools/inspect-db.php`.

## Checklist antes de dar el paso

- [ ] Autorización explícita del responsable del sistema
- [ ] Respaldo reciente verificado (`storage/backups/backup-*.sql`)
- [ ] Copia de `define.php` y `server.php` fuera del repositorio
- [ ] Ventana de mantenimiento acordada
- [ ] Inventario sin filas ilegibles en columnas con clave de sitio
- [ ] Plan de rollback leído y con acceso al respaldo