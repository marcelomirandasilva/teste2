<?php

namespace Application\controllers;

use Application\Core\Controller;


class Subscription extends Controller
{
    private $pdo;

    public function __construct()
    {
        parent::__construct();
        $this->pdo = $this->getDatabaseConnection();
    }

    /*public function index()
    {
        $Subscriptions = $this->model('Subscriptions');
        $subscriptions = $Subscriptions::findAll();
        $this->view('subscription/index', ['subscriptions' => $subscriptions]);

    }*/

    public function index()
    {
        $sql = '
            SELECT events.*, 
                   users.id AS user_id,
                   users.no_user AS no_user
            FROM events
            LEFT JOIN subscriptions ON events.id = subscriptions.event_id
            LEFT JOIN users ON subscriptions.user_id = users.id
        ';

        $stmt = $this->pdo->query($sql);
        $events = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $eventData = [];
        foreach ($events as $event) {
            $eventId = $event['id'];
            if (!isset($eventData[$eventId])) {
                $eventData[$eventId] = [
                    'id' => $event['id'],
                    'no_event' => $event['no_event'],
                    'subscriptions' => []
                ];
            }
            if ($event['user_id']) {
                $eventData[$eventId]['subscriptions'][] = [
                    'user_id' => $event['user_id'],
                    'no_user' => $event['no_user']
                ];
            }
        }
        $this->view('subscription/index', ['events' => $eventData]);
    }

    public function show($id)
    {
        if (is_numeric($id)) {
            $subscription = $this->subscriptionModel->findById($id);
            $this->view('subscription/show', ['subscription' => $subscription]);
        } else {
            $this->pageNotFound();
        }
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['event_id']) && isset($_POST['user_id'])) {
                $eventId = htmlspecialchars($_POST['event_id']);
                $userId = htmlspecialchars($_POST['user_id']);

                try {
                    $stmt = $this->pdo->prepare("INSERT INTO subscriptions (user_id, event_id) VALUES (:user_id, :event_id)");
                    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                    $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        header('Location: /event');
                        exit();
                    } else {
                        echo "Erro ao criar a inscrição.";
                    }
                } catch (PDOException $e) {
                    echo "Erro: " . $e->getMessage();
                }
            } else {
                echo "Erro.";
            }
        } else {
            echo "Erro.";
        }
    }

    public function store()
    {
        $userId = $_POST['user_id'];
        $eventId = $_POST['event_id'];

        if ($this->subscriptionModel->create($userId, $eventId)) {
            header('Location: /subscription');
            exit;
        } else {
            echo "Erro ao criar inscrição.";
        }
    }

    public function edit($id)
    {
        if (is_numeric($id)) {
            $subscription = $this->subscriptionModel->findById($id);
            $this->view('subscription/edit', ['subscription' => $subscription]);
        } else {
            $this->pageNotFound();
        }
    }

    public function update($id, $userId, $eventId)
    {
        $stmt = $this->pdo->prepare("UPDATE subscriptions SET user_id = :user_id, event_id = :event_id WHERE id = :id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        if (is_numeric($id)) {
            if ($this->subscriptionModel->delete($id)) {
                header('Location: /subscription');
                exit;
            } else {
                echo "Erro ao excluir inscrição.";
            }
        } else {
            $this->pageNotFound();
        }
    }
}
