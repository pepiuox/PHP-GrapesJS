# 07 — Decisiones de arquitectura (ADR)

## ADR-001 — Resolución de la conexión por constructor opcional

**Contexto.** 58 puntos de la capa de plantillas instancian controladores dentro de métodos de
`PageSystem`, donde no existe `$conn`. Los constructores exigen `PDO`.

**Decisión.** Añadir `PepiuoX\Database\Connection::shared()` y aceptar `?PDO $conn = null` en los
constructores afectados, resolviendo la conexión compartida cuando no se inyecta.

**Alternativas descartadas.**
- *Pasar `$conn` a cada plantilla*: obliga a modificar 40+ archivos de vista y a mantener el
  acoplamiento global `$conn`; más ruido y más riesgo por cambio.
- *Contenedor de servicios completo*: sobreingeniería para un CMS con plantillas incluidas; no
  aporta nada verificable en esta iteración.

**Trade-off aceptado.** Reintroduce localización de servicios (dependencia implícita) en el
código heredado. Se documenta el camino preferente: inyectar el PDO cuando se pueda.

## ADR-002 — Cifrado determinista con lectura multicompatible

**Contexto.** El esquema compara criptogramas (`WHERE email = :e AND mkpin = :p`). El cifrado
con IV aleatorio hace imposible esa comparación, y el cambio de algoritmo dejó ilegibles las
filas antiguas.

**Decisión.** Escribir siempre en AES-256-CBC determinista (el formato original) y leer CBC, GCM
100.000 y GCM 10.000.

**Alternativas descartadas.**
- *Mantener GCM aleatorio y añadir columna de índice ciego*: es la solución correcta a medio
  plazo, pero exige migración de esquema y re-cifrado de todas las filas: no se puede hacer sin
  ventana de mantenimiento y sin autorización explícita.
- *Volver solo a CBC sin lectura GCM*: perdería los registros escritos por las versiones
  intermedias.

**Trade-off aceptado.** El cifrado determinista revela igualdad de valores (dos correos iguales
producen el mismo criptograma). Ya era el comportamiento del esquema; la columna de índice ciego
lo elimina en una iteración futura.

## ADR-003 — Búsqueda del usuario por clave primaria

**Decisión.** Localizar la ficha de `users` por `idUser` (el `iduv` que ya devolvió `uverify`) y
comparar contraseña y PIN en PHP con comparación en tiempo constante.

**Alternativas descartadas.** *Re-cifrar toda la tabla con IV determinista*: cambia datos y no
resuelve el oráculo de igualdad. *Comparar solo por correo y PIN en `uverify` y confiar en la
fila de `users`*: deja sin verificar la contraseña duplicada.

**Trade-off.** Se hace una consulta más por acceso; el coste es despreciable frente a la
claridad y la seguridad ganadas.

## ADR-004 — Mantener la rotación del identificador en cada acceso

**Contexto.** El requisito del usuario es que el identificador cambie en cada ingreso. La
rotación actualiza `uverify.iduv` y `users_profiles.mkhash` dentro de una transacción, y las 31
claves foráneas `ON UPDATE CASCADE` propagan el identificador nuevo.

**Decisión.** Mantener la rotación tal como está diseñada y documentarla.

**Riesgo aceptado.** Cualquier referencia externa al identificador anterior deja de resolver.
Se mitiga con la sesión (`user_id` siempre el vigente) y con la cascada de claves foráneas.

## ADR-005 — Alcance de las correcciones

**Decisión.** Corregir solo lo que impide usar el sistema y lo que se puede verificar en esta
sesión. Los cambios que exigen rotar claves, re-cifrar datos o migrar el esquema (H-07 a H-11)
quedan documentados con plan de remediación y **no se aplican** sin autorización.

**Trade-off.** El sistema queda funcional, pero con deuda de seguridad conocida y visible.

## ADR-006 — Exclusión de terceros

**Decisión.** No analizar ni modificar `vendor/`, `plugin/` (`build/assets/plugins/`),
`build/assets/`, `build/themes/`, `build/uploads/`, `core/PHPMailer/` ni `core/filemanager/`.

**Motivo.** Son dependencias de terceros o artefactos binarios; sus correcciones pertenecen a sus
proyectos de origen y cualquier parche local se perdería en la siguiente actualización.