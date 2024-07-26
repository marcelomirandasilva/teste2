<?php

namespace Application\controllers;

use Application\core\Controller;

class Home extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Home Page',
            'message' => 'Bem-vindo à Home Page!'
        ];
        $this->view('home/index', $data);
    }
}
