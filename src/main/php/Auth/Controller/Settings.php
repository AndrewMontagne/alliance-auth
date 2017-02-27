<?php
/**
 * Copyright 2016 Andrew O'Rourke.
 */
namespace Auth\Controller;

use Auth\Model\Character;
use Auth\Model\User;
use Auth\Session;

class Settings
{
    use ControllerTrait;

    /**
     * Hooks routes.
     */
    public static function registerRoutes()
    {
        \Flight::route('GET /characters', [get_called_class(), 'indexAction']);
        \Flight::route('GET /', function() {
            \Flight::redirect('/characters');
        });
    }

    /**
     * Render the login form.
     */
    public static function indexAction()
    {
        static::requireLogin();

        $user = \Auth\Session::current()->getLoggedInUser();
        $primaryCharacter = $user->getPrimaryCharacter();

        $characters = Character::factory()
            ->where_equal('userId', $user->getId())
            ->order_by_asc('characterName')
            ->find_many();

        \Flight::render('front/settings.html', [
            'csrfToken' => Session::current()->regenCSRFToken(),
            'characters' => $characters,
            'primaryCharacterId' => ($primaryCharacter === false ? null : $primaryCharacter->getCharacterId())
        ]);
    }
}
