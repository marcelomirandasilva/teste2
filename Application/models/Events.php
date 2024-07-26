<?php

namespace Application\models;

require_once __DIR__ . '/UserSession.php';

use Application\core\Database;
use PDO;

class Events
{
    public static function findAll()
    {
        $conn = new Database();
        return $conn->executeQuery('SELECT * FROM events')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAllUser($userId)
    {
        $conn = new Database();
        $query = '
            SELECT e.*, 
                   CASE 
                       WHEN s.user_id IS NOT NULL THEN 1 
                       ELSE 0 
                   END AS is_subscribed 
            FROM events e
            LEFT JOIN subscriptions s ON e.id = s.event_id AND s.user_id = :user_id
        ';

        $stmt = $conn->executeQuery($query, ['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function findById(int $id)
    {
        $conn = new Database();
        $result = $conn->executeQuery('SELECT * FROM events WHERE id = :ID LIMIT 1', array(
            ':ID' => $id
        ));

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

}