<?php

return [
    'db' => [
 'dbname' => 'spci_shipping',
 'host' => '127.0.0.1',
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
    'lcApi' => 'http://192.168.5.251:9000/19_v2/',
    'btc' => [
        "api_url" => "http://demotestivps.com/infinite_app/api/",
        "token" => "abcdefghijklmnopqr"
    ],
];
