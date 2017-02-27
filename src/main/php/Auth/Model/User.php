<?php
/**
 * Copyright 2016 Andrew O'Rourke.
 */
namespace Auth\Model;

use Auth\Session;

/**
 * User model. Handles password management.
 */
class User extends Base
{
    public static $_table = 'users';

    /**
     * @return array
     *
     * @throws \Exception
     */
    private function getPasswordArgs()
    {
        return [
            'cost' => PASSWORD_ALGO_COST,
        ];
    }

    /**
     * Verifies the password. Updates the hash if needed.
     *
     * @param string $password
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function verifyPassword($password)
    {
        $matches = password_verify($password, $this->hash);
        if ($matches && password_needs_rehash($this->hash, PASSWORD_DEFAULT, $this->getPasswordArgs())) {
            $this->setPassword($password);
            $this->save();
        }

        return $matches;
    }

    /**
     * Sets the password.
     *
     * @param $password
     *
     * @return \Auth\Model\User
     *
     * @throws \Exception
     */
    public function setPassword($password)
    {
        $this->hash = password_hash($password, PASSWORD_DEFAULT, $this->getPasswordArgs());

        return $this;
    }

    /**
     * Sets this user as the currently logged in user in the session.
     */
    public function loginAs()
    {
        Session::current()->setUsername($this->getUsername());
    }

    /**
     * @return bool|Character
     */
    public function getPrimaryCharacter()
    {
        return Character::factory()->where_equal('id', $this->__get('primaryCharacter'))->find_one();
    }

    /**
     * @param Character $character
     */
    public function setPrimaryCharacter($character) {
        $this->__set('primaryCharacter', $character->getId());
    }
}
