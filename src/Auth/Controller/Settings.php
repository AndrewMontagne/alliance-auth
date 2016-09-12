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
        \Flight::route('GET /', [get_called_class(), 'indexAction']);
    }

    /**
     * Render the login form.
     */
    public static function indexAction()
    {
        static::requireLogin();

        $user = \Auth\Session::current()->getLoggedInUser();
        $primaryCharacterId = $user->getPrimaryCharacter()->getCharacterId();
        $characters = Character::factory()->where_equal('userId', $user->getId())->find_many();

        \Flight::render('front/settings.html', [
            'csrfToken' => Session::current()->regenCSRFToken(),
            'characters' => $characters,
            'primaryCharacterId' => $primaryCharacterId
        ]);
    }
}
