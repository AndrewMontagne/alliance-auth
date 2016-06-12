<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Controller;


use Auth\Session;

class OAuth
{
    use ControllerTrait;

    static public function registerRoutes()
    {
        \Flight::route('GET /oauth/authorize', [get_called_class(), 'authorizeAction']);
    }

    static public function authorizeAction()
    {
        self::requireLogin();
    }
}