<?php
// Usage Example
$db = new PDO('mysql:host=localhost;dbname=yourdb', 'youruser', 'yourpassword');
$router = new Router($db);

// Assuming $uri is determined by your application's entry point (e.g., index.php)
$uridash = '/db/managers/dashboard'; // Example URI, should be dynamically determined
$router->route($uridash);
$uriusr = '/db/users/dashboard'; // Example URI, should be dynamically determined
$router->route($uriusr);

