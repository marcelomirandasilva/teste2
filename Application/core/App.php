<?php

namespace Application\core;

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $page404 = false;
    protected $params = [];

    public function __construct()
    {
        $URL_ARRAY = $this->parseUrl();
        $this->getControllerFromUrl($URL_ARRAY);
        $this->getMethodFromUrl($URL_ARRAY);
        $this->getParamsFromUrl($URL_ARRAY);

        echo "Controller: " . (is_object($this->controller) ? get_class($this->controller) : $this->controller) . "<br>";
        echo "Method: " . $this->method . "<br>";
        echo "Params: " . implode(", ", $this->params) . "<br>";

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl()
    {
        $REQUEST_URI = explode('/', substr(filter_input(INPUT_SERVER, 'REQUEST_URI'), 1));
        return $REQUEST_URI;
    }

    private function getControllerFromUrl($url)
    {
        if (!empty($url[0]) && isset($url[0])) {
            if (file_exists('../Application/controllers/' . ucfirst($url[0]) . '.php')) {
                $this->controller = ucfirst($url[0]);
            } else {
                $this->page404 = true;
            }
        }

        require '../Application/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller();
    }

    private function getMethodFromUrl($url)
    {
        if (!empty($url[1]) && isset($url[1])) {
            if (method_exists($this->controller, $url[1]) && !$this->page404) {
                $this->method = $url[1];
            } else {
                $this->method = 'pageNotFound';
            }
        } elseif (count($url) == 2 && method_exists($this->controller, 'index')) {
            // Se o segundo segmento da URL for o parâmetro userId, use o método index
            $this->method = 'index';
        }
    }

    private function getParamsFromUrl($url)
    {
        if (count($url) > 2) {
            $this->params = array_slice($url, 2);
        } elseif (count($url) == 2) {
            $this->params = [$url[1]];
        }

    }
}

?>
