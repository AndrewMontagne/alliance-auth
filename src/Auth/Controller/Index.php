<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Controller;

use Auth\Session;

class Index
{
    /**
     * Render the login form
     *
     * @return void
     */
    public static function loginAction()
    {
        \Flight::render('front/index.html');
    }

    /**
     * OAuth 2 Authorization Endpoint
     *
     * @return void
     */
    public static function authorizeAction()
    {
        if(Session::current()->loggedIn == false) {
            Session::current()->redirectPath = '/authorize?' . $_SERVER['QUERY_STRING'];
            \Flight::redirect('/login');
        }

        //TODO: Implement OAuth 2
    }
}