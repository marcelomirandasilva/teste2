<?php

namespace Application\controllers;

require_once __DIR__ . '/../models/UserSession.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';

use Application\models\UserSession;
use Application\core\Controller;
use Application\core\Database;
use PDO;
use PDOException;

class Auth extends Controller
{
    private $db;
    public function __construct()
    {
        $host = 'localhost';
        $dbname = 'teste2';
        $username = 'root';
        $password = 'root';

        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            try {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE no_user = :username");
                $stmt->execute([':username' => $username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    session_start();

                    $stmt = $this->db->prepare("SELECT no_role FROM roles WHERE id = :id");
                    $stmt->execute([':id' => $user['role_id']]);
                    $role = $stmt->fetch(PDO::FETCH_ASSOC);

                    $_SESSION['usuario_logado'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['no_user'] = $user['no_user'];
                    $_SESSION['role'] = $role['no_role'];

                    $userSession = UserSession::getInstance();
                    $userSession->setUserId($user['id']);

                    header("Location: /home");
                    exit();
                } else {
                    $this->view('auth/login', ['error' => 'Credenciais inválidas!']);
                    return;
                }
            } catch (PDOException $e) {
                error_log("Erro no banco de dados: " . $e->getMessage());
                $this->view('auth/login', ['error' => 'Ocorreu um erro.']);
                return;
            }
        }

        $this->view('auth/login');
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /login");
        exit();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            // Insere o novo usuário no banco de dados
            $stmt = $this->db->prepare("INSERT INTO users (no_user, password, role_id) VALUES (:username, :password, 2)");
            $stmt->execute(['username' => $username, 'password' => $password]);

            header("Location: /auth/login");
            exit();
        }

        $this->view('auth/register');
    }
}
