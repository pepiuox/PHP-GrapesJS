<?php
declare(strict_types=1);

/**
 * Configuración del router con validación de rutas.
 */
return [
    'cache' => [
        'enabled' => true,
'ttl' => 3600,
'dir' => __DIR__ . '/../cache/pages'
    ],
'routes' => [
    'home' => ['slug' => 'home', 'page_id' => 1],
'404' => ['slug' => '404', 'page_id' => null]
],
'performance' => [
    'max_parent_depth' => 10,
'use_compressed_cache' => true,
'preload_common_pages' => ['home', 'about', 'contact']
],
'security' => [
    'allowed_slugs_pattern' => '/^[a-z0-9\-]+$/',
'max_slug_length' => 100
]
];
