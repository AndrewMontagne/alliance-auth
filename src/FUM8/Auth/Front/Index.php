<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace FUM8\Auth\Front;

use FUM8\Auth\Cookie;

class Index
{
    public static function loginAction()
    {
        $content = 'LEL';
        \Flight::render('front/index.html', ['body_content' => $content]);
    }

    public static function authorizeAction()
    {
        if(!Cookie::exists('auth')) {
            \Flight::redirect('/login?redirect=' . base64_encode('/authorize?' . $_SERVER['QUERY_STRING']));
        }
    }
}