<?php

return [
    'db' => [
'dbname' => 'infinite_app',
 'host' => 'localhost',
 'user' => 'root',
 'pass' => ''
    ],
    'mvc' => [
        'defaults' => [
            'action' => 'index',
            'controller' => 'index',
            'error' => 'error'
        ]
    ],
    'authKey' => sha1(date('d-m-Y') . 'V2'),
    'lcApi' => 'http://192.168.5.251:9000/19_v2/'
];
