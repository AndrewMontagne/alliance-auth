<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Controller;

use Auth\Model\Application;
use Auth\Session;

class OAuth
{
    use ControllerTrait;

    static public function registerRoutes()
    {
        \Flight::route('GET /oauth/authorize', [get_called_class(), 'authorizeAction']);
        \Flight::route('POST /oauth/authorize', [get_called_class(), 'authorizeCallbackAction']);
        \Flight::route('GET /oauth/token', [get_called_class(), 'tokenAction']);
    }

    static public function authorizeAction()
    {
        self::requireLogin();

        $clientID = trim(filter_input(INPUT_GET, 'client_id'));
        $responseType = trim(filter_input(INPUT_GET, 'response_type'));
        $grant = trim(filter_input(INPUT_GET, 'grant'));

        if (empty($clientID) ) {
            throw new \Exception('Missing client_id');
        }
        if (empty($responseType) ) {
            throw new \Exception('Missing response_type');
        }
        $application = Application::factory()->find_one($clientID);
        if (empty($application)) {
            throw new \Exception('Invalid client_id');
        }
        \Flight::render('front/authorize.html', [
            'csrfToken' => Session::current()->regenCSRFToken(),
            'application' => $application
        ]);
    }

    static public function authorizeCallbackAction()
    {
        global $logger;
        $user = Session::current()->getLoggedInUser();

        if (Session::current()->csrf_token !== trim(filter_input(INPUT_POST, 'csrf_token'))) {
            \Flight::json([
                'success' => 'false',
                'message' => 'CSRF Failure'
            ], 400);
            $logger->info('CSRF failure for user ' . $user->getUsername());
        }
    }
}