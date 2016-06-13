<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Controller;

use Auth\Model\Application;

class OAuth
{
    use ControllerTrait;

    static public function registerRoutes()
    {
        \Flight::route('GET /oauth/authorize', [get_called_class(), 'authorizeAction']);
    }

    static public function authorizeAction()
    {
        self::requireLogin();

        $clientID = trim(filter_input(INPUT_GET, 'client_id'));
        $redirectURI = trim(filter_input(INPUT_GET, 'redirect_uri'));
        $responseType = trim(filter_input(INPUT_GET, 'response_type'));
        $grant = trim(filter_input(INPUT_GET, 'grant'));

        if (empty($clientID) ) {
            throw new \Exception('Missing client_id');
        }
        if (empty($redirectURI) ) {
            throw new \Exception('Missing redirect_uri');
        }
        if (empty($responseType) ) {
            throw new \Exception('Missing response_type');
        }

        $application = Application::factory()->find_one($clientID);
        if (empty($application)) {
            throw new \Exception('Invalid client_id');
        }
    }
}