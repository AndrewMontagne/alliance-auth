<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth\Model;

/**
 * User model. Handles password management.
 *
 * @package Auth\Model
 */
class User extends Base
{
    public static $_table = 'users';

    /**
     * @return array
     * @throws \Exception
     */
    private function getPasswordArgs() {
        if('changeme' == PASSWORD_SALT) {
            throw new \Exception('NO SALT SET IN CONFIG');
        }

        return [
            'salt' => PASSWORD_SALT,
            'cost' => 10
        ];
    }

    /**
     * Verifies the password. Updates the hash if needed.
     *
     * @param string $password
     * @return bool
     * @throws \Exception
     */
    public function verifyPassword($password) {
        $matches = password_verify($password, $this->hash);
        if($matches && password_needs_rehash($this->hash, PASSWORD_DEFAULT, $this->getPasswordArgs())) {
            $this->setPassword($password);
            $this->save();
        }
        return $matches;
    }

    /**
     * Sets the password.
     *
     * @param $password
     * @return \Auth\Model\User
     * @throws \Exception
     */
    public function setPassword($password) {
        $this->hash = password_hash($password, PASSWORD_DEFAULT, $this->getPasswordArgs());
        return $this;
    }
}