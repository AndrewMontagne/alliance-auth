<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace FUM8\Auth\Front;

class Json
{
    public static function loginCallbackAction()
    {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        if ('friend' == $password) {
            \Flight::json([
                'success' => 'true',
                'message' => 'Logged In Successfully!',
                'redirectUri' => 'http://leagueofgentlemen.org/'
            ]);
        } else {
            \Flight::json([
                'success' => 'false',
                'message' => 'Incorrect Username or Password'
            ], 403);
        }
    }
}