<?php
/**
 * Copyright 2016 Andrew O'Rourke.
 */
define('ROOT_DIR', realpath($_SERVER['DOCUMENT_ROOT']).'/');

require ROOT_DIR.'vendor/autoload.php';

$configPath = ROOT_DIR.$_SERVER['HTTP_HOST'].'.config.json';

global $logger;
global $requestID;

$requestID = substr(hash('md5', uniqid()), 0, 8);

$logger = new \Monolog\Logger('[alliance-auth]');
$logger->pushProcessor(function($record) {
    global $requestID;
    $record['extra']['requestID'] = $requestID;
    $record['extra']['remoteIP'] = $_SERVER['REMOTE_ADDR'];
    $record['extra']['session'] = \Auth\Session::current()->allData();
    return $record;
});
$logger->pushHandler(new \Monolog\Handler\RotatingFileHandler(ROOT_DIR . 'app.log', 10, \Monolog\Logger::INFO));

/*
 * Error page handling
 */
if (!file_exists($configPath)) {
    http_response_code(500);
    Flight::render('front/error.html',
        [
            'errorTitle' => 'Configuration Error',
            'errorMessage' => 'Missing '.$_SERVER['HTTP_HOST'].'.config.json',
        ]
    );
    die();
}

if (ALLOW_CHROMELOGGER) {
    $logger->pushHandler(new \Monolog\Handler\ChromePHPHandler(), \Monolog\Logger::DEBUG);
}

Flight::map('error', function (Throwable $ex) {
    global $logger;
    $handler = new \Monolog\ErrorHandler($logger);
    $handler->handleException($ex);

    http_response_code(500);
    Flight::render('front/error.html',
        [
            'errorTitle' => 'Internal Server Error',
            'errorMessage' => get_class($ex) . ': ' . $ex->getMessage(),
        ]
    );
    die();
});
Flight::map('notFound', function () {
    global $logger;
    $logger->debug('Request made to missing route "' . $_SERVER['REQUEST_URI'] .'"');
    http_response_code(404);
    Flight::render('front/error.html',
        [
            'errorTitle' => 'Page Not Found',
            'errorMessage' => 'No route exists for '.$_SERVER['REQUEST_URI'],
        ]
    );
    die();
});

/*
 * Configuration Loading
 */
$config = json_decode(file_get_contents($configPath), true);

foreach ($config as $key => $value) {
    define(strtoupper($key), $value);
}

ORM::configure(ORM_CONNECTION);
ORM::configure('username', ORM_USERNAME);
ORM::configure('password', ORM_PASSWORD);
Flight::set('flight.views.path', ROOT_DIR.'views');
//Flight::set('flight.handle_errors', false);

/**
 * Global Functions.
 */
function getVersionedAsset($asset)
{
    $path = ROOT_DIR.'/static/'.$asset;
    $assetHash = substr(md5_file($path, false), 0, 8);

    return '/'.$asset.'?'.$assetHash;
}

/*
 * Request Routing
 */
Flight::route('/', function () {
    if (is_null(\Auth\Session::current()->getLoggedInUser())) {
        Flight::redirect('/login');
        return;
    }
    $char = \Auth\Session::current()->getLoggedInUser()->getPrimaryCharacter();
    echo '<h1>' . $char->getCharacterName() . '</h1><img src="http://image.eveonline.com/Character/' . $char->getCharacterId() . '_256.jpg">';
});

\Auth\Controller\Login::registerRoutes();
\Auth\Controller\EveSSO::registerRoutes();
\Auth\Controller\Register::registerRoutes();

Flight::start();
