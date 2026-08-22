<?php

$settings=[
    'default-connection' => 'cms',
    'connections' => [
        'cms' => [
            'server' => 'localhost',
            'database' => 'phpgrapesjs',
            'username' => 'pepiuox',
            'password' => 'Tru3L@ve',
            'charset' => 'utf8',
            'port' => '3306',
        ],// use different connection for another DB in this app, and change values.
         'ecommerce' => [
            'server' => 'localhost',
            'database' => 'ecommerce',
            'username' => 'pepiuox',
            'password' => 'password',
            'charset' => 'utf8',
            'port' => '3306',
        ]
    ]
];
?>
