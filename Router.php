<?php

namespace app;

class Router{

    public array $getRoutes = [];
    public array $postRoutes = [];
    public Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function get($url, $callback)
    {
        $this->getRoutes[$url] = $callback;
    }

    public function post($url, $callback)
    {
        $this->postRoutes[$url] = $callback;
    }

    public function resolve()
    {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '/';
        $urlPosition = strpos($currentUrl, '?');

        if($urlPosition !== false){
            $currentUrl = substr($currentUrl, 0, $urlPosition);
        }
        $method = $_SERVER['REQUEST_METHOD'];

        if($method === 'GET'){
            $callback = $this->getRoutes[$currentUrl] ?? null;
        }else{
            $callback = $this->postRoutes[$currentUrl] ?? null;
        }
        
        if($callback){
            call_user_func($callback, $this);
        }else{
            echo "page not found";
        }
        

    }

    public function renderView($view, $params = [])
    {
        foreach($params as $key => $value){
            $$key = $value;
        }
        ob_start();
        include_once __DIR__."/view/$view.php";
        $content = ob_get_clean();
        include_once __DIR__."/view/layouts/layout.php";
    }

}