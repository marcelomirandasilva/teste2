<?php

namespace Application\models;

use Application\core\Database;
use PDO;

class Roles
{

    public static function findAll()
    {
        $conn = new Database();
        $result = $conn->executeQuery('SELECT * FROM roles');
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id)
    {
        $conn = new Database();
        $result = $conn->executeQuery('SELECT * FROM roles WHERE idroles = :ID LIMIT 1', array(
            ':ID' => $id
        ));

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

}