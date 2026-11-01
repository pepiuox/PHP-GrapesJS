<?php
declare(strict_types=1);

/**
 * Inspeccion de solo lectura del estado real de la base de datos.
 *
 * No modifica nada y no imprime datos personales ni secretos: solo cuenta filas, mide
 * longitudes y determina en que formato esta cifrado cada campo sensible, descifrando
 * en memoria con la implementacion del proyecto.
 *
 * Uso:
 *   php tools/inspect-db.php             resumen
 *   php tools/inspect-db.php --tables    inventario de tablas con filas
 */

require __DIR__ . '/../core/Autoload.php';

$root = dirname(__DIR__);
$showTables = in_array('--tables', $argv, true);

$settings = '';
$returned = require $root . '/core/config/server.php';

if (is_array($returned) && $returned !== []) {
    $settings = $returned;
}

$connection = $settings['connections'][$settings['default-connection']] ?? null;

if (!is_array($connection)) {
    fwrite(STDERR, "Configuracion de base de datos no disponible.\n");
    exit(2);
}

$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $connection['server'], (int) $connection['port'], $connection['database'], $connection['charset'] ?? 'utf8mb4');

try {
    $pdo = new PDO($dsn, $connection['username'], $connection['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e) {
    fwrite(STDERR, 'SIN CONEXION: ' . $e->getMessage() . PHP_EOL);
    exit(2);
}

$server = (string) $pdo->query('SELECT VERSION()')->fetchColumn();
printf("Servidor: %s\nBase de datos: %s\n\n", $server, $connection['database']);

$tables = [];

foreach ($pdo->query('SHOW TABLE STATUS') as $row) {
    $tables[$row['Name']] = (int) $row['Rows'];
}

if ($showTables) {
    arsort($tables);

    foreach ($tables as $name => $rows) {
        printf("%-34s %6d filas\n", $name, $rows);
    }

    exit(0);
}

$crypto = new GetCodeDeEncrypt();
$siteSecurity = $pdo->query('SELECT SECURE_HASH, SECURE_TOKEN FROM site_security WHERE site = 1')->fetch();

if (!$siteSecurity) {
    echo "site_security sin filas para site=1\n";
    exit(0);
}

$stoken = (string) $siteSecurity['SECURE_TOKEN'];
$shash = (string) $siteSecurity['SECURE_HASH'];

/**
 * Determina el formato del valor cifrado sin revelarlo.
 */
function detect_format(string $value, string $token, string $hash, GetCodeDeEncrypt $crypto): string
{
    if ($value === '') {
        return 'vacio';
    }

    if (preg_match('/^[0-9]{6}$/', $value) === 1) {
        return 'PIN EN CLARO';
    }

    if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
        return 'email en claro';
    }

    $plain = $crypto->ende_crypter('decrypt', $value, $token, $hash);

    if (is_string($plain) && $plain !== '') {
        $again = $crypto->ende_crypter('encrypt', $plain, $token, $hash);

        return $again === $value ? 'CBC determinista (compatible)' : 'descifra pero no re-cifra igual';
    }

    $raw = base64_decode($value, true);

    if ($raw !== false && strlen($raw) >= 28) {
        return 'GCM IV aleatorio (no buscable por igualdad)';
    }

    return 'formato desconocido';
}

foreach (['uverify' => ['email', 'mkpin', 'username', 'password'], 'users' => ['email', 'mkpin', 'username', 'password'], 'users_active' => ['usercode']] as $table => $columns) {
    if (!isset($tables[$table])) {
        continue;
    }

    printf("### %s (%d filas)\n", $table, $tables[$table]);

    $rows = $pdo->query('SELECT * FROM `' . $table . '` LIMIT 5')->fetchAll();

    if ($rows === []) {
        echo "  sin filas\n\n";
        continue;
    }

    foreach ($columns as $column) {
        if (!array_key_exists($column, $rows[0])) {
            continue;
        }

        $formats = [];

        foreach ($rows as $row) {
            $value = (string) ($row[$column] ?? '');
            $formats[] = detect_format($value, $stoken, $shash, $crypto);
        }

        printf("  %-10s len=%2d  formatos: %s\n", $column, strlen((string) ($rows[0][$column] ?? '')), implode(' | ', array_unique($formats)));
    }

    echo "\n";
}

echo "### Indices relevantes para el acceso\n";

foreach (['uverify', 'users'] as $table) {
    $indexes = $pdo->query('SHOW INDEX FROM `' . $table . '`')->fetchAll();
    $byColumn = [];

    foreach ($indexes as $index) {
        $byColumn[$index['Column_name']][] = $index['Key_name'] . ($index['Non_unique'] ? '' : ' (unico)');
    }

    printf("%s: %s\n", $table, implode(', ', array_map(
        static fn (string $column, array $names): string => $column . ' -> ' . implode('/', $names),
        array_keys($byColumn),
        $byColumn
    )));
}

echo "\n### Cifrado del sitio (site_security)\n";
printf("  SECURE_TOKEN presente: %s (len %d)\n", $stoken !== '' ? 'si' : 'no', strlen($stoken));
printf("  mismo valor que define.php: %s\n", (defined('SECURE_TOKEN') && SECURE_TOKEN === $stoken) ? 'si' : 'no');