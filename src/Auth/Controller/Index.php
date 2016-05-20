<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Controller;

use Auth\Session;

class Index
{
    public static function loginAction()
    {
        $content = 'LEL';
        \Flight::render('front/index.html', ['body_content' => $content]);
    }

    public static function authorizeAction()
    {
        if(Session::current()->loggedIn == false) {
            Session::current()->redirectPath = '/authorize?' . $_SERVER['QUERY_STRING'];
            \Flight::redirect('/login');
        }
    }
}