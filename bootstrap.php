<?php

require_once __DIR__ . './vendor/autoload.php';

$mysql_conf = require __DIR__ . '/config/mysql.php';

\Task\Mysql::init(
    $mysql_conf['host'],
    $mysql_conf['port'],
    $mysql_conf['db'],
    $mysql_conf['user'],
    $mysql_conf['pass']
)
    ->connect();

// uncomment to create table. database must exists
//\Task\Mysql::get()->up();
