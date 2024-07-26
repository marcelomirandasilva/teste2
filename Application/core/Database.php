<?php

namespace Application\Core;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct()
    {
        $host = 'localhost';
        $dbname = 'teste2';
        $username = 'root';
        $password = 'root';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function executeQuery($query, $params = [])
    {
        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $stmt;
    }
}

?>
