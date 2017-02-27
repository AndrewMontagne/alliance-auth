<?php
/**
 * Copyright 2016 Andrew O'Rourke.
 */
namespace Auth;

use Auth\Model\User;

/**
 * Session Class.
 *
 * Handles the management of user sessions across the application
 *
 * @author Andrew O'Rourke <andrew@montagne.uk>
 */
class Session
{
    /**
     * @var Session
     */
    private static $instance = null;

    /**
     * @var \Predis\Client
     */
    private $redis;

    /**
     * @var string
     */
    private $sessionID;

    /**
     * @var stdClass
     */
    private $sessionData;

    /**
     * Session constructor.
     */
    private function __construct()
    {
        $this->redis = new \Predis\Client(REDIS_CONNECTION);

        $this->sessionID = Cookie::get('s', null);

        $this->sessionData = json_decode(
            $this->redis->get($this->sessionID)
        );

        if (empty($this->sessionData)) {
            $this->sessionData = new \stdClass();
        }

        $newSessionID = $this->generateSessionID();
        /*if (is_null($this->sessionID)) {
            $this->redis->rename($this->sessionID, $newSessionID);
        }(*/
        $this->sessionID = $newSessionID;
        Cookie::set('s', $this->sessionID);
    }

    /**
     * Session destructor.
     */
    public function __destruct()
    {
        $this->redis->set(
            $this->sessionID,
            json_encode($this->sessionData)
        );
        $this->redis->expire(
            $this->sessionID,
            60 * 60 * 24 * 7
        );
    }

    /**
     * Returns the singleton instance.
     *
     * @return Session
     */
    public static function current()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function generateSessionID()
    {
        return bin2hex(random_bytes(8));
    }

    /**
     * Generates a CSRF token and sets it in the session.
     */
    public function regenCSRFToken()
    {
        $token = bin2hex(random_bytes(32));
        $this->__set('csrf_token', $token);
        return $token;
    }

    /**
     * Returns the logged in user.
     *
     * @return User
     */
    public function getLoggedInUser()
    {
        if (is_null($this->__get('username'))) {
            return null;
        }
        return User::factory()->where('username', $this->__get('username'))->find_one();
    }

    /**
     * Clears the session.
     */
    public function clear()
    {
        $this->sessionData = new \stdClass();
    }

    /**
     * Returns all session data
     *
     * @return \stdClass
     */
    public function allData()
    {
        return $this->sessionData;
    }

    /**
     * Magic Method - Handles getter and setter functions.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return $this|mixed
     */
    public function __call($method, $arguments)
    {
        if (preg_match('/^(s|g)et[A-Z]\w*$/', $method)) {
            $property = lcfirst(substr($method, 3));
            $type = substr($method, 0, 3);
            if ($type === 'set') {
                $value = $arguments[0];
                $this->__set($property, $value);

                return $this;
            } elseif ($type === 'get') {
                return $this->__get($property);
            }
        }
    }

    /**
     * Magic Method - Setter.
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->sessionData->$name = $value;
    }

    /**
     * Magic Method - Getter.
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->__isset($name)) {
            return $this->sessionData->$name;
        }

        return null;
    }

    /**
     * Magic Method - Is Set.
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->sessionData->$name);
    }

    /**
     * Magic Method - Unsetter.
     *
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->sessionData->$name);
    }
}
