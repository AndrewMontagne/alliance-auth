<?php
/**
 * Copyright 2016 Andrew O'Rourke.
 */
namespace Auth\Controller;

use Auth\Session;

trait ControllerTrait
{
    /**
     * Hooks routes.
     */
    abstract public static function registerRoutes();

    static public function requireLogin()
    {
        if (is_null(\Auth\Session::current()->getLoggedInUser())) {
            Session::current()->setRedirectPath($_SERVER['REQUEST_URI']);
            \Flight::redirect('/login');
            return true;
        }
        return false;
    }
}
