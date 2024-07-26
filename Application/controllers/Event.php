<?php

namespace Application\Controllers;

use Application\Models\UserSession;
use Application\Models\Events;
use Application\Core\Controller;


class Event extends Controller
{
    private $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = $this->getDatabaseConnection();
    }

    public function index_all()
    {
        $Events = new Events();
        $events = $Events->findAll();

        $this->view('event/index', ['events' => $events]);
    }

    public function index($id = null)
    {
        $Events = new Events();
        $events = $Events->findAllUser($id);

        $this->view('event/index', ['events' => $events, 'id' => $id]);
    }

    public function subscribe()
    {
        $userId = $_POST['user_id'];
        $eventId = $_POST['event_id'];

        try {
            $sql = 'INSERT INTO subscriptions (user_id, event_id) VALUES (:user_id, :event_id)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $userId, ':event_id' => $eventId]);

            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function unsubscribe()
    {
        $userId = $_POST['user_id'];
        $eventId = $_POST['event_id'];

        try {
            $sql = 'DELETE FROM subscriptions WHERE user_id = :user_id AND event_id = :event_id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $userId, ':event_id' => $eventId]);

            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function isAdmin()
    {
        if ($_SESSION['role'] === "Admin") {
            return true;
        }
        return false;
    }

    private function redirectIfNotAdmin()
    {
        if (!$this->isAdmin()) {
            $_SESSION['error_message'] = "Você não tem permissão para executar esta ação.";
            header('Location: /event');
            exit;
        }
    }

    public function show($id = null)
    {
        if (is_numeric($id)) {
            $Events = new Events();
            $event = $Events::findById($id);
            $this->view('event/show', ['event' => $event]);
        } else {
            $this->pageNotFound();
        }
    }

    public function store()
    {
        $no_event = $_POST['no_event'];

        try {
            $sql = 'SELECT COUNT(*) FROM events WHERE no_event = :no_event';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':no_event' => $no_event]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $response = ['status' => 'error', 'message' => 'O nome do evento já está em uso'];
                header('Content-Type: application/json');
                echo json_encode($response);
                return;
            }

            $sql = 'INSERT INTO events (no_event) VALUES (:no_event)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':no_event' => $no_event]);
            $eventId = $this->pdo->lastInsertId();

            $response = ['status' => 'success', 'message' => 'Evento criado com sucesso', 'eventId' => $eventId];
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        } catch (PDOException $e) {
            $response = ['status' => 'error', 'message' => 'Erro ao tentar salvar o Evento: ' . $e->getMessage()];
            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }
    }

    private function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function update()
    {
        $id = $_POST['id'];
        $no_event = $_POST['no_event'];

        try {
            // Verificar se o nome do evento já existe
            $sql = 'SELECT COUNT(*) FROM events WHERE no_event = :no_event AND id != :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':no_event' => $no_event, ':id' => $id]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                throw new \Exception('O nome do evento já está em uso');
            }

            $sql = 'UPDATE events SET no_event = :no_event WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':no_event' => $no_event,
                ':id' => $id
            ]);

            header('Location: /event');
            exit; // Garanta que o script pare aqui
        } catch (\Exception $e) {
            echo "Erro: " . $e->getMessage();
        } catch (PDOException $e) {
            echo "Erro ao tentar atualizar o Evento: " . $e->getMessage();
        }
    }

    public function create()
    {
        $this->redirectIfNotAdmin();
        $this->view('event/create');
    }

    public function edit($id)
    {
        $this->redirectIfNotAdmin();

        if (is_numeric($id)) {
            try {
                $sql = 'SELECT * FROM events WHERE id = :id';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                $event = $stmt->fetch();

                if ($event) {
                    $this->view('event/edit', ['event' => $event]);
                } else {
                    $this->pageNotFound();
                }
            } catch (PDOException $e) {
                echo "Erro ao buscar o evento: " . $e->getMessage();
            }
        } else {
            $this->pageNotFound();
        }
    }

    public function delete($id)
    {
        $this->redirectIfNotAdmin();
        if (is_numeric($id)) {
            $sql = 'DELETE FROM events WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            header('Location: /events');
            exit;
        } else {
            $this->pageNotFound();
        }
    }
}

?>
