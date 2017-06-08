<?php
$GLOBALS  = [
	'db' => [
        'driver'    => 'mysql',
		'host'      => '127.0.0.1',
        'port'      => 3306,
		'username'  => 'root',
		'password'  => 'root',
		'database'  => 'yytester',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
		'prefix'    => ''
    ],

	//存储已经实例化的对象
    'obj' => [],
];
