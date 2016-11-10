<?php

// 线上环境数据库设置
$config['driver'] = 'mysql';
$config['host'] = env('DB_HOST', 'localhost');
$config['database'] = env('DB_DATABASE', 'database');
$config['username'] = env('DB_USERNAME', 'username');
$config['password'] = env('DB_PASSWORD', 'password');
$config['charset'] = 'utf8';
$config['collation'] = 'utf8_unicode_ci';
$config['prefix'] = '';
$config['strict'] = false;
