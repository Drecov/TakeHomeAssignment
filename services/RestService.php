<?php

class RestService {
    public function processaRequest() {
        $requestType = $_SERVER["REQUEST_METHOD"];
        $uriCompleta = $_SERVER["REQUEST_URI"];
        $uriPartes = array_values(array_filter(explode('/', $uriCompleta)));
        $endpoint = $uriPartes[0];

        if($requestType == "GET") {
            $params = $_GET ?? null;
        } else if($requestType == "POST") {
            $params = json_decode(file_get_contents("php://input"), true) ?? $_POST  ?? null;
        }

        $controller = self::$mapping[$requestType][$endpoint]["controller"];
        $metodo = self::$mapping[$requestType][$endpoint]["method"];

        if(!$controller || !$metodo) {
            http_response_code(500);
            print ">>> Erro ao processar request. Método $metodo não existe para a classe $controller.<<<\n";
            return;
        }

        if(!method_exists($controller, $metodo)) {
            http_response_code(500);
            print ">>> Erro ao processar request. Método $metodo não existe para a classe $controller.<<<\n";
            return;
        }

        $controllerInstance = new $controller;
        $controllerInstance->$metodo($params);

    }

    private static $mapping = [
        "GET" => [
            "balance" => ["controller" => "AccountController", "method" => "getBalance"]
        ],
        "POST" => [
            "reset" => ["controller" => "RuntimeMemoryService", "method" => "resetMemory"],
            "event" => ["controller" => "AccountController", "method" => "processEvent"]
        ]
    ];
}