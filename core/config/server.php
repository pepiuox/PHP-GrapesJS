<?php

$settings=array(
    'default-connection' => 'cms',
    'connections' => array(
        'cms' => array(
            'server' => 'localhost',
            'database' => 'test_cms',
            'username' => 'root',
            'password' => 'truelove',
            'charset' => 'utf8',
            'port' => '3306',
        ),// use different connection for another DB in this app, and change values.
         'ecommerce' => array(
            'server' => 'localhost',
            'database' => 'ecommerce',
            'username' => 'user',
            'password' => 'password',
            'charset' => 'utf8',
            'port' => '3306',
        ),
        ),
);
        ?>