<?php

namespace Application\core;

use Exception;
use RuntimeException;

class Controller
{
    protected $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    protected function getDatabaseConnection()
    {
        return $this->database->getPdo();
    }

    protected function view($view, $data = [])
    {
        $viewPath = '../Application/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            extract($data);
            require $viewPath;
        } else {
            echo "View {$view} não encontrada!";
        }
    }

    protected function model($model)
    {
        $modelPath = '../Application/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            $modelClass = 'Application\\models\\' . $model;
            if (class_exists($modelClass)) {
                return new $modelClass();
            }
        }

        throw new RuntimeException("Model {$model} não encontrado!");
    }
}
