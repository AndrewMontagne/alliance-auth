<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Controller;

use Auth\Model\Application;
use Auth\Model\AuthToken;
use Auth\Model\User;
use Auth\Session;

class OAuth
{
    use ControllerTrait;

    static public function registerRoutes()
    {
        \Flight::route('GET /oauth/authorize', [get_called_class(), 'authorizeAction']);
        \Flight::route('POST /oauth/authorize', [get_called_class(), 'authorizeCallbackAction']);
        \Flight::route('POST /oauth/token', [get_called_class(), 'tokenAction']);
    }

    static public function authorizeAction()
    {
        self::requireLogin();

        $clientID = self::input('client_id', INPUT_GET);
        $responseType = self::input('response_type', INPUT_GET);
        if ($responseType != 'code') {
            throw new \Exception('Invalid response_type');
        }
        $application = self::verifyClientId($clientID);

        \Flight::render('front/authorize.html', [
            'csrfToken' => Session::current()->regenCSRFToken(),
            'clientId' => $clientID,
            'application' => $application
        ]);
    }

    static public function authorizeCallbackAction()
    {
        global $logger;
        $user = Session::current()->getLoggedInUser();

        if (!($user instanceof User))
        {
            \Flight::json([
                'success' => 'false',
                'message' => 'Not logged in'
            ], 401);
        }
        if (Session::current()->csrf_token !== trim(filter_input(INPUT_POST, 'csrf_token'))) {
            \Flight::json([
                'success' => 'false',
                'message' => 'CSRF Failure'
            ], 400);
            $logger->info('CSRF failure for user ' . $user->getUsername());
        }

        $clientID = self::input('client_id');
        $application = self::verifyClientId($clientID);

        $token = AuthToken::factory()
            ->create()
            ->generateID()
            ->setAuthToken(bin2hex(random_bytes(16)))
            ->setApplicationId($application->getId())
            ->setExpires(date('Y-m-d h:i:s', time() + 60));
        $token->save();

        \Flight::json([
            'success' => 'true',
            'message' => 'Redirecting to application',
            'redirectUri' => $application->getRedirectUri(). '?code=' . $token->getAuthToken()
        ], 201);
    }

    static public function tokenAction()
    {
        $clientId = $clientID = self::input('client_id');
        $application = self::verifyClientId($clientId);
        $client_secret = $clientID = self::input('client_secret');
        $grantType = $clientID = self::input('grant_type');
        $code = $clientID = self::input('code');
        $token = AuthToken::factory()->where('authToken', $code)->find_one();

        if (!($token instanceof AuthToken)) {
            throw new \Exception('Invalid Authorization Code');
        }
        if ($token->getApplicationId() != $application->id()) {
            throw new \Exception('Application ID Mismatch');
        }
        if ($client_secret != $application->getSecret()) {
            throw new \Exception('Invalid Client Secret');
        }
        if ($grantType !== 'authorization_code') {
            throw new \Exception('Invalid Grant Type');
        }

        $token->setAuthToken(null)
            ->setAccessToken((bin2hex(random_bytes(16))))
            ->setRefreshToken((bin2hex(random_bytes(16))))
            ->setExpires(date('Y-m-d h:i:s', time() + ACCESS_TOKEN_LIFETIME));
        $token->save();

        \Flight::json([
            'access_token' => $token->getAccessToken(),
            'token_type' => 'bearer',
            'expires_in' => ACCESS_TOKEN_LIFETIME,
            'refresh_token' => $token->getRefreshToken()
        ], 201);
    }

    static private function verifyClientId($clientID) {
        $application = Application::factory()->find_one($clientID);
        if (empty($application)) {
            throw new \Exception('Missing client_id');
        }
        return $application;
    }
}