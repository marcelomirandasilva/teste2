<?php

namespace Application\models;
require_once __DIR__ . '/../models/UserSession.php';


use Application\core\Database;
use PDO;

class Subscriptions
{
    private $pdo;

    public static function findAll($id = null)
    {
        $conn = new Database();
        $query = '
            SELECT events.*, 
                   CASE 
                       WHEN subscriptions.user_id IS NOT NULL THEN 1 
                       ELSE 0 
                   END AS is_subscribed 
            FROM events
            LEFT JOIN subscriptions ON events.id = subscriptions.event_id AND subscriptions.user_id = :user_id
        ';

        $stmt = $conn->executeQuery($query, ['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findAll2()
    {
        $conn = new Database();

        $query = '
                SELECT events.*, 
                       users.id AS user_id,
                       users.username AS user_name
                FROM events
                LEFT JOIN subscriptions ON events.id = subscriptions.event_id
                LEFT JOIN users ON subscriptions.user_id = users.id
        ';
        $stmt = $conn->executeQuery($query, ['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        $conn = new Database();
        $stmt = $conn->executeQuery("SELECT * FROM subscriptions WHERE id = :id", [':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByUserId($userId)
    {
        $conn = new Database();
        $stmt = $conn->executeQuery("SELECT * FROM subscriptions WHERE user_id = :user_id", [':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByEventId($eventId)
    {
        $conn = new Database();
        $stmt = $conn->executeQuery("SELECT * FROM subscriptions WHERE event_id = :event_id", [':event_id' => $eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($userId, $eventId)
    {
        $conn = new Database();
        $stmt = $conn->executeQuery(
            "INSERT INTO subscriptions (user_id, event_id, created_at) VALUES (:user_id, :event_id, NOW())",
            [':user_id' => $userId, ':event_id' => $eventId]
        );
        return $stmt->rowCount() > 0;
    }

    public static function update($id, $userId, $eventId)
    {
        $conn = new Database();
        $stmt = $conn->executeQuery(
            "UPDATE subscriptions SET user_id = :user_id, event_id = :event_id WHERE id = :id",
            [':user_id' => $userId, ':event_id' => $eventId, ':id' => $id]
        );
        return $stmt->rowCount() > 0;
    }

    public static function delete($id)
    {
        $conn = new Database();
        $stmt = $conn->executeQuery("DELETE FROM subscriptions WHERE id = :id", [':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
