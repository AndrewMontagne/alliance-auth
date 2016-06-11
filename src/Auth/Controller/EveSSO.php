<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Controller;

use Auth\Model\Character;
use Auth\Model\User;
use Auth\Session;

class EveSSO
{
    /**
     * Redirects the user to the EVE SSO OAuth 2 authorisation endpoint
     *
     * @return void
     * @throws \Exception
     */
    static public function loginAction()
    {
        if (!isset(Session::current()->redirectPath)) {
            throw new \Exception('No redirect path specified.');
        }

        \Flight::redirect(
            'https://login.eveonline.com/oauth/authorize?response_type=code&client_id=' . EVE_SSO_ID .
            '&redirect_uri=' . BASE_URL . 'evesso/callback&scope=' . implode(' ', [
                'characterSkillsRead',
                'publicData'
            ])
        );
    }

    /**
     * Handles the OAuth 2 callback from EVE SSO
     *
     * @return void
     */
    static public function callbackAction()
    {
        $code = \Flight::request()->query['code'];
        $state = \Flight::request()->query['state'];

        $character = Character::handleAuthentication($code);

        $session = Session::current();
        $session->setRegisteredCharacter($character->getCharacterId());

        \Flight::redirect($session->redirectPath);
        $session->__unset('redirectPath');
    }
}