<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);

require ROOT_DIR . 'vendor/autoload.php';

$config = json_decode(file_get_contents(ROOT_DIR . 'config.json'), true);

foreach ($config as $key => $value) {
    define(strtoupper($key), $value);
}

Flight::set('flight.views.path', ROOT_DIR . 'views');
Flight::route('/', ['\\FUM8\Auth\Front\Index', 'indexAction']);
Flight::start();