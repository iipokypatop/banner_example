<?php

require_once __DIR__ . '/../bootstrap.php';

\Task\Controller::create()
    ->handle($_SERVER);

header('Content-Type: image/png');
header('Cache-Control: no-cache');
echo file_get_contents(__DIR__ . '/img/pixel.png');


