<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);

require ROOT_DIR . 'vendor/autoload.php';

$configPath = ROOT_DIR . $_SERVER['HTTP_HOST'] . '.config.json';

if(!file_exists($configPath)) {
    http_response_code(500);
    Flight::render('front/error.html',
        [
            'errorTitle' => 'Configuration Error',
            'errorMessage' => 'Missing ' . $_SERVER['HTTP_HOST'] . '.config.json'
        ]
    );
    die();
}

$config = json_decode(file_get_contents($configPath), true);

foreach ($config as $key => $value) {
    define(strtoupper($key), $value);
}

function getVersionedAsset($asset) {
    $path = ROOT_DIR . '/static/' . $asset;
    $assetHash = substr(md5_file($path, false), 0, 8);
    return '/' . $asset . '?' . $assetHash;
}

ORM::configure(ORM_CONNECTION);
ORM::configure('username', ORM_USERNAME);
ORM::configure('password', ORM_PASSWORD);

Flight::set('flight.views.path', ROOT_DIR . 'views');
Flight::route('/', function() {
    Flight::redirect('/login');
});

Flight::route('GET /login', ['\Auth\Controller\Index', 'loginAction']);
Flight::route('POST /login', ['\Auth\Controller\Json', 'loginCallbackAction']);
Flight::route('GET /authorize', ['\Auth\Controller\Index', 'authorizeAction']);

Flight::map('error', function(Exception $ex){
    http_response_code(500);
    Flight::render('front/error.html',
        [
            'errorTitle' => 'Internal Server Error',
            'errorMessage' => get_class($ex) . ': ' . $ex->getMessage()
        ]
    );
    die();
});
Flight::map('notFound', function(){
    http_response_code(404);
    Flight::render('front/error.html',
        [
            'errorTitle' => 'Page Not Found',
            'errorMessage' => 'No route exists for ' . $_SERVER['REQUEST_URI']
        ]
    );
    die();
});
Flight::start();