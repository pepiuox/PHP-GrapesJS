<?php
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
]
];
