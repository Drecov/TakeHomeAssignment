<?php

class RestService {
    public function __construct() {
        $this->autoload();
    }

    private function autoload() {
        spl_autoload_register(function ($class) {
            
            $paths =   [__DIR__ . '/../controllers/' . $class . '.php',
                        __DIR__ . '/../services/' . $class . '.php',
                        __DIR__ . '/../models/' . $class . '.php'];

            foreach ($paths as $path) {
                if(file_exists($path)) {
                    require_once $path;
                } else {
                    print '>>>Erro. Não foi possível carregar a classe '. $class .'. Class Path: ' . $path . '.<<<';
                }
            }
        });
    }

    public function processaRequest() {
        $metodo = $_SERVER['REQUEST_METHOD'];
        $uriCompleta = $_SERVER['REQUEST_URI'];
        $uriPartes = explode('/', $uriCompleta);


    }

    private static $MAPPING = [
        
    ];
}