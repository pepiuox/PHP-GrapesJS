<?php
declare(strict_types=1);

/**
 * Clasifica el SQL dinamico del codigo PDO en tres categorias manejables:
 *
 *   valor                    un valor entra en el texto SQL por concatenacion o
 *                            interpolacion entre comillas  -> DEBE ser marcador (prepared)
 *   identificador_validado   interpolacion de tabla/columna que ya pasa por
 *                            SqlIdentifier::assert()/quote() -> correcto (PDO no permite
 *                            enlazar identificadores)
 *   identificador_sin_validar interpolacion de tabla/columna SIN validacion -> riesgo
 *
 * Uso:
 *   php tools/scan-sql.php                resumen y detalle por categoria
 *   php tools/scan-sql.php --json         salida JSON
 *   php tools/scan-sql.php --only=valor   filtra una categoria
 *   php tools/scan-sql.php --save=ruta    guarda el informe en markdown
 *
 * No analiza terceros: vendor/, build/assets, core/PHPMailer, core/filemanager, core/blocks.
 */

$root = dirname(__DIR__);
$args = array_slice($argv, 1);
$json = in_array('--json', $args, true);
$only = null;
$save = null;

foreach ($args as $arg) {
    if (str_starts_with($arg, '--json')) {
        $json = true;
    } elseif (str_starts_with($arg, '--only=')) {
        $only = substr($arg, 7);
    } elseif (str_starts_with($arg, '--save=')) {
        $save = substr($arg, 7);
    }
}

$excludedDirs = ['vendor', 'node_modules', '.git', 'assets', 'themes', 'uploads', 'PHPMailer', 'filemanager', 'file-manager', 'blocks'];
$scanRoots = ['core', 'build', 'tools', 'tests'];
$files = [];

foreach ($scanRoots as $scanRoot) {
    $base = $root . '/' . $scanRoot;

    if (!is_dir($base)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS),
            static fn (SplFileInfo $current): bool => !$current->isDir()
                || !in_array($current->getFilename(), $excludedDirs, true)
        )
    );

    foreach ($iterator as $file) {
        if ($file instanceof SplFileInfo && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }
}

sort($files);

function firstArgument(string $code, int $openParen): ?string
{
    $depth = 0;
    $length = strlen($code);
    $quote = null;
    $buffer = '';

    for ($i = $openParen; $i < $length; $i++) {
        $char = $code[$i];

        if ($quote !== null) {
            $buffer .= $char;

            if ($char === '\\') {
                $i++;
                $buffer .= $code[$i] ?? '';
                continue;
            }

            if ($char === $quote) {
                $quote = null;
            }

            continue;
        }

        if ($char === "'" || $char === '"') {
            $quote = $char;
            $buffer .= $char;
            continue;
        }

        if ($char === '(') {
            $depth++;

            if ($depth === 1) {
                continue;
            }
        }

        if ($char === ')') {
            $depth--;

            if ($depth === 0) {
                return $buffer;
            }
        }

        if ($char === ',' && $depth === 1) {
            return $buffer;
        }

        $buffer .= $char;
    }

    return null;
}

/** Variables que aparecen dentro del SQL. */
function variablesIn(string $sql): array
{
    $variables = [];

    if (preg_match_all('/\{\$this->([A-Za-z_][A-Za-z0-9_]*)\}/', $sql, $m) > 0) {
        foreach ($m[1] as $name) {
            $variables[] = '$this->' . $name;
        }
    }

    if (preg_match_all('/(?<![\w>$])\$([A-Za-z_][A-Za-z0-9_]*)(?![\w(])/', $sql, $m) > 0) {
        foreach ($m[1] as $name) {
            $variables[] = '$' . $name;
        }
    }

    return array_values(array_unique($variables));
}

/** ¿Ese valor entra por concatenacion con comillas (contexto de valor)? */
function concatenatesValue(string $sql): bool
{
    return preg_match('/["\']\s*\.\s*\$/', $sql) === 1
        || preg_match("/'\s*\.\s*(\\\\?\\$|\\$)/", $sql) === 1;
}

$pattern = '/->\s*(prepare|query|exec)\s*\(/i';
$findings = [];

foreach ($files as $file) {
    $code = (string) file_get_contents($file);

    if (preg_match_all($pattern, $code, $matches, PREG_OFFSET_CAPTURE) === 0) {
        continue;
    }

    foreach ($matches[0] as $match) {
        $openParen = $match[1] + strlen($match[0]) - 1;
        $argument = firstArgument($code, $openParen);

        if ($argument === null) {
            continue;
        }

        $sql = trim($argument);
        $interpolates = preg_match('/"[^"]*\$[A-Za-z_{]/s', $sql) === 1;
        $concatenates = concatenatesValue($sql);

        if (!$interpolates && !$concatenates) {
            continue;
        }

        $variables = variablesIn($sql);
        $unvalidated = [];
        $derived = [];

        // Variables que se construyen a partir de piezas ya validadas (listas de joins,
        // marcadores, fragmentos de WHERE...). Se informan aparte: no son entrada directa.
        $derivedNames = [
            'rjoin', 'placeholders', 'whre', 'upname', 'varname', 'idParam', 'inparams',
            'col', 'cpost', 'upost', 'vpost', 'query', 'cqn', 'sql',
        ];

        foreach ($variables as $variable) {
            $name = ltrim(str_replace('$this->', '$', $variable), '$');

            $validated = preg_match(
                '/SqlIdentifier::(assert|quote|fromWhitelist|filter)\(\s*' . preg_quote($variable, '/') . '/',
                $code
            ) === 1
                || preg_match('/validateIdentifier\(\s*' . preg_quote($variable, '/') . '/', $code) === 1
                || preg_match('/(?<![\w>])' . preg_quote($variable, '/') . '\s*=\s*[\'"][^\'$]*[\'"]\s*;/', $code) === 1;

            if ($validated) {
                continue;
            }

            if (in_array($name, $derivedNames, true)) {
                $derived[] = $variable;

                continue;
            }

            $unvalidated[] = $variable;
        }

        // Clasificacion: valor entre comillas -> marcador; identificador sin validar -> riesgo;
        // identificador construido con piezas validadas -> informativo.
        if ($concatenates) {
            $category = 'valor';
        } elseif ($unvalidated !== []) {
            $category = 'identificador_sin_validar';
        } elseif ($derived !== []) {
            $category = 'identificador_derivado';
        } else {
            $category = 'identificador_validado';
        }

        $findings[] = [
            'file' => str_replace($root . DIRECTORY_SEPARATOR, '', $file),
            'line' => substr_count(substr($code, 0, $match[1]), "\n") + 1,
            'category' => $category,
            'sql' => preg_replace('/\s+/', ' ', mb_substr($sql, 0, 150)),
            'variables' => $variables,
            'unvalidated' => $unvalidated,
        ];
    }
}

$order = ['valor' => 0, 'identificador_sin_validar' => 1, 'identificador_derivado' => 2, 'identificador_validado' => 3];
usort($findings, static fn (array $a, array $b): int => [$order[$a['category']], $a['file'], $a['line']] <=> [$order[$b['category']], $b['file'], $b['line']]);

if ($only !== null) {
    $findings = array_values(array_filter($findings, static fn (array $f): bool => $f['category'] === $only));
}

if ($json) {
    echo json_encode($findings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), "\n";
    exit(0);
}

$counts = ['valor' => 0, 'identificador_sin_validar' => 0, 'identificador_derivado' => 0, 'identificador_validado' => 0];

foreach ($findings as $finding) {
    $counts[$finding['category']]++;
}

$report = [];
$report[] = '# SQL dinamico clasificado';
$report[] = '';
$report[] = sprintf('Generado: %s', date('Y-m-d H:i:s'));
$report[] = sprintf('Archivos analizados: %d', count($files));
$report[] = '';
$report[] = '| Categoria | Coincidencias | Significado |';
$report[] = '|---|---|---|';
$report[] = sprintf('| valor | %d | Un valor entra en el SQL: debe ser marcador preparado |', $counts['valor']);
$report[] = sprintf('| identificador_sin_validar | %d | Nombre de tabla/columna sin validar: riesgo de inyeccion |', $counts['identificador_sin_validar']);
$report[] = sprintf('| identificador_derivado | %d | Nombre construido con piezas ya validadas (joins, marcadores): informativo |', $counts['identificador_derivado']);
$report[] = sprintf('| identificador_validado | %d | Nombre validado con SqlIdentifier: correcto |', $counts['identificador_validado']);
$report[] = '';

foreach (['valor', 'identificador_sin_validar', 'identificador_derivado', 'identificador_validado'] as $category) {
    $items = array_values(array_filter($findings, static fn (array $f): bool => $f['category'] === $category));
    $report[] = sprintf('## %s (%d)', $category, count($items));
    $report[] = '';

    foreach ($items as $item) {
        $report[] = sprintf(
            '- %s:%d — %s%s',
            str_replace('\\', '/', str_replace($root . DIRECTORY_SEPARATOR, '', $item['file'])),
            $item['line'],
            $item['sql'],
            $item['unvalidated'] === [] ? '' : ' — sin validar: ' . implode(', ', $item['unvalidated'])
        );
    }

    $report[] = '';
}

$text = implode(PHP_EOL, $report) . PHP_EOL;
echo $text;

if ($save !== null) {
    file_put_contents($save, $text);
    fwrite(STDERR, PHP_EOL . 'Informe guardado en ' . $save . PHP_EOL);
}

exit(0);