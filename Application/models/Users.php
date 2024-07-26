<?php

namespace Application\models;

use Application\core\Database;
use PDO;

class Users
{

    public static function findAll()
    {
        $conn = new Database();
        //$result = $conn->executeQuery('SELECT * FROM users');
        $result = $conn->executeQuery('SELECT users.id, users.no_user,  roles.no_role FROM users JOIN roles ON users.role_id = roles.id');
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id)
    {
        $conn = new Database();
        $result = $conn->executeQuery('SELECT * FROM users WHERE id = :ID LIMIT 1', array(
            ':ID' => $id
        ));

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}