<?php
/**
 * Copyright 2016 Andrew O'Rourke.
 */
namespace Auth\Controller;

use Auth\Model\User;
use Auth\Session;

class Login implements ControllerInterface
{
    /**
     * Hooks routes.
     */
    public static function registerRoutes()
    {
        \Flight::route('GET /login', ['\Auth\Controller\Login', 'loginAction']);
        \Flight::route('POST /login', ['\Auth\Controller\Login', 'loginCallbackAction']);
        \Flight::route('GET /authorize', ['\Auth\Controller\Login', 'authorizeAction']);
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

        if (Session::current()->csrf_token !== trim(filter_input(INPUT_POST, 'csrf_token'))) {
            \Flight::json([
                'success' => 'false',
                'message' => 'CSRF Failure'
            ], 400);
        }

        /* @var \Auth\Model\User */
        $user = User::factory()->where('username', $username)->find_one();

        if ($user != null && $user->verifyPassword($password)) {
            $user->loginAs();
            \Flight::json([
                'success' => 'true',
                'message' => 'Logged In Successfully!',
            ]);
        } else {
            \Flight::json([
                'success' => 'false',
                'message' => 'Incorrect Username or Password',
            ], 403);
        }
    }
    /**
     * OAuth 2 Authorization Endpoint.
     */
    public static function authorizeAction()
    {
        if (Session::current()->loggedIn == false) {
            Session::current()->redirectPath = '/authorize?'.$_SERVER['QUERY_STRING'];
            \Flight::redirect('/login');
        }

        //TODO: Implement OAuth 2
    }
}
