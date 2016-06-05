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