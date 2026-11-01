# Auditoría funcional de PHP-GrapesJS — índice

Documentación del análisis, la verificación y las correcciones aplicadas el **2026-10-17**
sobre el proyecto `PHP-GrapesJS` (`C:\Users\PePiuoX\Documents\Github\PHP-GrapesJS`).

| Documento | Contenido |
|---|---|
| [01-METODOLOGIA-Y-ALCANCE.md](01-METODOLOGIA-Y-ALCANCE.md) | Qué se analizó, qué se excluyó, cómo se obtuvo cada evidencia |
| [02-ARQUITECTURA.md](02-ARQUITECTURA.md) | Mapa real de capas, arranque de una petición, autoload y datos |
| [03-AUTENTICACION-Y-SESIONES.md](03-AUTENTICACION-Y-SESIONES.md) | Acceso con correo + contraseña + PIN, rotación del identificador |
| [04-HALLAZGOS.md](04-HALLAZGOS.md) | 18 hallazgos con severidad, evidencia y estado |
| [05-CORRECCIONES-APLICADAS.md](05-CORRECCIONES-APLICADAS.md) | Cada cambio, su archivo, su respaldo y su verificación |
| [06-PRUEBAS-Y-EVIDENCIAS.md](06-PRUEBAS-Y-EVIDENCIAS.md) | Instrumentos, comandos y resultados reales |
| [07-DECISIONES-ADR.md](07-DECISIONES-ADR.md) | ADR-001 a ADR-006 con alternativas descartadas |
| [08-ESTADO-DEL-PROYECTO.md](08-ESTADO-DEL-PROYECTO.md) | Estado, pendientes, riesgos y próximos pasos |
| [09-PLAN-ROTACION-CLAVES.md](09-PLAN-ROTACION-CLAVES.md) | Plan de rotación de claves con re-cifrado verificado (planificado, no ejecutado) |
| [10-SQL-PREPARADO.md](10-SQL-PREPARADO.md) | Refactor de SQL a sentencias preparadas y validación de identificadores |

## Resumen en una frase

El CMS no permitía iniciar sesión por cinco defectos encadenados (inyección de dependencias,
cifrado no determinista, búsqueda por criptograma, `(int)` sobre identificadores de texto y
doble escape de la contraseña); tras corregirlos, el acceso con correo + contraseña + PIN
funciona y se verificó por HTTP contra la base de datos real.

Estado de la entrega: **VALIDADO CON LIMITACIONES** — ver [08](08-ESTADO-DEL-PROYECTO.md).

## Carpetas de trabajo

    docs/     esta documentación
    tools/    instrumentos de verificación (auditoría, respaldo, pruebas de humo)
    tests/    suite PHPUnit
    storage/backups/  respaldos generados (base de datos y archivos originales parcheados)

Las carpetas `vendor/`, `plugin/` (equivalente a `build/assets/plugins/`), `build/assets/`,
`build/themes/` y `build/uploads/` quedaron **fuera del análisis y de toda modificación**.