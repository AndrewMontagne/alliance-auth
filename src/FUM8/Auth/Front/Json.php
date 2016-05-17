<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace FUM8\Auth\Front;

use FUM8\Auth\Model\User;

class Json
{
    public static function loginCallbackAction()
    {
        $username = trim(filter_input(INPUT_POST, 'username'));
        $password = trim(filter_input(INPUT_POST, 'password'));

        /* @var \FUM8\Auth\Model\User */
        $user = User::factory()->where('username', $username)->find_one();

        if ($user != null && $user->verifyPassword($password)) {
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

    public static function registerCallbackAction()
    {
        $username = trim(filter_input(INPUT_POST, 'username'));
        $password = trim(filter_input(INPUT_POST, 'password'));
        $email = trim(filter_input(INPUT_POST, 'email'));

        $user = User::factory()->create();
        $user
            ->generateID()
            ->setUsername($username)
            ->setEmail($email)
            ->setPassword($password)
            ->save();
    }
}