<?php
/**
 * Copyright 2016 Andrew O'Rourke
 */

namespace Auth;

use Auth\Model\User;

class Session
{
    private static $instance = null;

    private $redis;
    private $sessionID;
    private $sessionData;

    private function __construct()
    {
        $this->redis = new \Predis\Client(REDIS_CONNECTION);

        $this->sessionID = Cookie::get('s', null);
        if(is_null($this->sessionID)) {
            $this->sessionID = substr(hash('md5', uniqid()), 0, 8);
            Cookie::set('s', $this->sessionID);
        }
        $this->sessionData = json_decode(
            $this->redis->get($this->sessionID)
        );
    }

    public function __destruct()
    {
        $this->redis->set(
            $this->sessionID,
            json_encode($this->sessionData)
        );
    }

    public static function current()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    public function getLoggedInUser()
    {
        return User::factory()->where('username', $this->__get('id'))->find_one();
    }

    public function clear()
    {
        $this->sessionData = new \stdClass();
    }

    public function __call($method, $arguments)
    {
        if(preg_match('/^(s|g)et[A-Z]\w*$/', $method)) {
            $property = lcfirst(substr($method, 3));
            $type = substr($method, 0, 3);
            if ($type === 'set') {
                $value = $arguments[0];
                $this->__set($property, $value);
                return $this;
            } else if ($type === 'get') {
                return $this->__get($property);
            }
        }
    }

    public function __set($name, $value)
    {
        return $this->sessionData->$name = $value;
    }

    public function __get($name)
    {
        if($this->__isset($name)) {
            return $this->sessionData->$name;
        }
        return null;
    }

    public function __isset($name)
    {
        return isset($this->sessionData->$name);
    }

    public function __unset($name)
    {
        unset($this->sessionData->$name);
    }
}