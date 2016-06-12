<?php
/**
 * Copyright 2016 Andrew O'Rourke.
 */
namespace Auth\Controller;

use Auth\Model\User;
use Auth\Session;

class Login
{
    use ControllerTrait;

    /**
     * Hooks routes.
     */
    public static function registerRoutes()
    {
        \Flight::route('GET /login', [get_called_class(), 'loginAction']);
        \Flight::route('POST /login', [get_called_class(), 'loginCallbackAction']);
    }

    /**
     * Render the login form.
     */
    public static function loginAction()
    {
        if (!is_null(Session::current()->getLoggedInUser())) {
            if (isset(Session::current()->redirectPath)) {
                \Flight::redirect(Session::current()->redirectPath);
            } else {
                \Flight::redirect('/');
            }
        } else {
            \Flight::render('front/index.html', [
                'csrfToken' => Session::current()->regenCSRFToken()
            ]);
        }
    }

    /**
     * Handles OAuth 2 Login.
     */
    public static function loginCallbackAction()
    {
        $username = trim(filter_input(INPUT_POST, 'username'));
        $password = trim(filter_input(INPUT_POST, 'password'));

        global $logger;

        if (Session::current()->csrf_token !== trim(filter_input(INPUT_POST, 'csrf_token'))) {
            \Flight::json([
                'success' => 'false',
                'message' => 'CSRF Failure'
            ], 400);
            $logger->info('CSRF failure for user ' . $username);
        }

        /* @var \Auth\Model\User */
        $user = User::factory()->where('username', $username)->find_one();

        if ($user != null && $user->verifyPassword($password)) {
            $logger->info($username . ' logged in.');
            $user->loginAs();
            \Flight::json([
                'success' => 'true',
                'message' => 'Logged In Successfully!',
            ]);
        } else {
            $logger->info('Authentication failure for ' . $username);
            \Flight::json([
                'success' => 'false',
                'message' => 'Incorrect Username or Password',
            ], 403);
        }
    }
}
