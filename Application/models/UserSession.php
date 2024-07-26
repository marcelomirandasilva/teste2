<?php

namespace Application\models;

class UserSession
{
    private static $instance;
    private $userId;

    private function __construct()
    {
        //
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new UserSession();
        }

        return self::$instance;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}

?>
