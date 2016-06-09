<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Controller;

use Auth\Model\User;
use Auth\Session;

class Json
{
    /**
     * Handles OAuth 2 Login
     *
     * @return void
     */
    public static function loginCallbackAction()
    {
        //TODO: CSRF TOKEN
        //TODO: Move into login controller

        $username = trim(filter_input(INPUT_POST, 'username'));
        $password = trim(filter_input(INPUT_POST, 'password'));

        /* @var \Auth\Model\User */
        $user = User::factory()->where('username', $username)->find_one();

        if ($user != null && $user->verifyPassword($password)) {
            $user->loginAs();
            \Flight::json([
                'success' => 'true',
                'message' => 'Logged In Successfully!'
            ]);
        } else {
            \Flight::json([
                'success' => 'false',
                'message' => 'Incorrect Username or Password'
            ], 403);
        }
    }
}