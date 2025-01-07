<?php

return [
    'connections' => [
        'cms_1' => [
            'server' => 'localhost',
            'database' => 'grapesjs',
            'username' => 'root',
            'password' => 'Tru3L@ve',
            'charset' => 'utf8',
            'port' => '3306',
        ], // use different connection for another DB in this app, and change values.
        'ecommerce' => [
            'server' => 'localhost',
            'database' => 'ecommerce',
            'username' => 'user',
            'password' => 'password',
            'charset' => 'utf8',
            'port' => '3306',
        ],
    ],
];
?>
