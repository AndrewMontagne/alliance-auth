<?php
/**
 * Copyright 2016 Andrew O'Rourke.
 */
namespace Auth\Controller;

use Auth\Session;
use Auth\Model\User;
use Auth\Model\Character;

class Register
{
    use ControllerTrait;

    /**
     * Hooks routes.
     */
    public static function registerRoutes()
    {
        \Flight::route('GET /register/', [get_called_class(), 'indexAction']);
        \Flight::route('POST /register/callback', [get_called_class(), 'registerCallbackAction']);
    }

    /**
     * Render the registration form.
     *
     * @throws \Exception
     */
    public static function indexAction()
    {
        \Flight::render('front/register.html', [
            'csrfToken' => Session::current()->regenCSRFToken()
        ]);
    }

    /**
     * AJAX Endpoint for the registration form.
     */
    public static function registerCallbackAction()
    {
        if (Session::current()->csrf_token !== trim(filter_input(INPUT_POST, 'csrf_token'))) {
            \Flight::json([
                'success' => 'false',
                'message' => 'CSRF Failure',
            ], 400);
        }

        $session = Session::current();
        $username = trim(filter_input(INPUT_POST, 'username'));
        $password = trim(filter_input(INPUT_POST, 'password'));

        if (User::factory()->where('username', $username)->find_one() !== false) {
            \Flight::json([
                'success' => 'false',
                'message' => 'Account Already Exists',
            ], 400);
        }

        $user = User::factory()
            ->create()
            ->generateID()
            ->setUsername($username)
            ->setPassword($password);

        $user->save();
        $user->loginAs();

        global $logger;
        $logger->notice('Account "' . $username . '" created.');

        $session = Session::current();
        unset($session->registeredCharacter);
        unset($session->redirectPath);

        $data = [
            'success' => true,
            'message' => 'Account Created',
        ];

        if (isset($session->oauthRedirectPath)) {
            $data['redirect'] = $session->getOauthRedirectPath;
        }

        \Flight::json($data, 201);
    }
}
