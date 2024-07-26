<?php

namespace Application\Controllers;

use Application\Models\Users;
use Application\Models\Roles;
use Application\Core\Controller;

class User extends Controller
{
    private $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = $this->getDatabaseConnection();
    }

    public function index()
    {
        $Users = new Users();
        $users = $Users::findAll();
        $this->view('user/index', ['users' => $users]);
    }

    public function show($id = null)
    {
        if (is_numeric($id)) {
            $Users = new Users();
            $user = $Users::findById($id);
            $this->view('user/show', ['user' => $user]);
        } else {
            $this->pageNotFound();
        }
    }

    public function store()
    {
        $name = $_POST['no_user'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role_id = $_POST['role_id'];

        try {
            $sql = 'INSERT INTO users (no_user, password, role_id) VALUES (:no_user, :password, :role_id)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':no_user' => $name, ':password' => $password, ':role_id' => $role_id]);

            header('Location: /user');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "O nome de usuário já está em uso";
            } else {
                echo "Erro ao tentar salvar o usuário: " . $e->getMessage();
            }
        }
    }

    public function update()
    {
        $id = $_POST['id'];
        $name = $_POST['no_user'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role_id = $_POST['role_id'];

        try {
            $sql = 'SELECT COUNT(*) FROM users WHERE no_user = :no_user AND id != :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':no_user' => $name, ':id' => $id]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                throw new Exception('O nome de usuário já está em uso');
            }

            $sql = 'UPDATE users SET no_user = :no_user, password = :password, role_id = :role_id WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':no_user' => $name,
                ':password' => $password,
                ':role_id' => $role_id,
                ':id' => $id
            ]);

            header('Location: /user');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "Erro ao atualizar o usuário: O nome de usuário já está em uso.";
            } else {
                echo "Erro ao tentar atualizar o usuário: " . $e->getMessage();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function create()
    {
        $Roles = new Roles();
        $roles = $Roles::findAll();
        $this->view('user/create', ['roles' => $roles]);
    }

    public function edit($id = null)
    {
        if (is_numeric($id)) {
            $Users = new Users();
            $user = $Users::findById($id);

            $Roles = new Roles();
            $roles = $Roles::findAll();

            $this->view('user/edit', ['user' => $user, 'roles' => $roles]);
        } else {
            $this->pageNotFound();
        }
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            $sql = 'DELETE FROM users WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            header('Location: /user');
            exit;
        } else {
            $this->pageNotFound();
        }
    }
}

?>
