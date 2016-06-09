<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Controller;

use Auth\Session;
use Auth\Model\User;
use Auth\Model\Character;

class Register
{
    /**
     * Redirects clients to OAuth Login
     *
     * @return void
     */
    public static function indexAction()
    {
        Session::current()->setRedirectPath('/register/register');
        \Flight::redirect('/evesso/login');
    }

    /**
     * Render the registration form
     *
     * @return void
     * @throws \Exception
     */
    public static function registerAction()
    {
        $characterID = Session::current()->getRegisteredCharacter();
        $character = Character::getBy('characterId', $characterID);

        if ( ! empty($character->getUserId())) {
            throw new \Exception('Character Already In Use');
            // TODO: Handle this more gracefully
        }

        \Flight::render('front/register.html', [
            'characterName' => $character->getCharacterName(),
            'suggestedUsername' => str_replace(' ', '', $character->getCharacterName())
        ]);
    }

    /**
     * AJAX Endpoint for the registration form
     *
     * @return void
     */
    public static function registerCallbackAction()
    {
        // TODO: CSRF Token

        $session = Session::current();
        $username = trim(filter_input(INPUT_POST, 'username'));
        $password = trim(filter_input(INPUT_POST, 'password'));
        $characterID = $session->getRegisteredCharacter();
        $character = Character::getBy('characterID', $characterID);

        if (User::factory()->where('username', $username)->find_one() !== false) {
            \Flight::json([
                'success' => 'false',
                'message' => 'Account Already Exists'
            ], 400);
        }
        if ( ! empty($character->getUserId())) {
            \Flight::json([
                'success' => 'false',
                'message' => 'Character Already In Use'
            ], 400);
        }

        $user = User::factory()
            ->create()
            ->generateID()
            ->setUsername($username)
            ->setPassword($password)
            ->setPrimaryCharacter($character->getId());

        $user->save();
        $user->loginAs();

        $character->setUserId($user->getId());
        $character->save();

        $session = Session::current();
        unset($session->registeredCharacter);
        unset($session->redirectPath);

        $data = [
            'success' => true,
            'message' => 'Account Created'
        ];

        if (isset($session->oauthRedirectPath)) {
            $data['redirect'] = $session->getOauthRedirectPath;
        }

        \Flight::json($data, 201);
    }
}