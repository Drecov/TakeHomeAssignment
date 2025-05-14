<?php

class RestService {
    public function processaRequest() {
        $requestType = $_SERVER["REQUEST_METHOD"];
        $uriCompleta = $_SERVER["REQUEST_URI"];
        $uriCaminho = parse_url($uriCompleta, PHP_URL_PATH);
        $uriPartes = array_values(array_filter(explode('/', $uriCaminho)));
        $endpoint = $uriPartes[0];

        if($requestType == "GET") {
            $params = $_GET ?? null;
        } else if($requestType == "POST") {
            $params = json_decode(file_get_contents("php://input"), true) ?? $_POST  ?? null;
        }

        $controller = self::$mapping[$requestType][$endpoint]["controller"];
        $metodo = self::$mapping[$requestType][$endpoint]["method"];

        if(!$controller || !$metodo) {
            http_response_code(400);
            print "<br>>>> Erro ao processar request. Método $metodo não existe para a classe $controller.<<<";
            return;
        }

        if(!method_exists($controller, $metodo)) {
            http_response_code(400);
            print "<br>>>> Erro ao processar request. Método $metodo não existe para a classe $controller.<<<\n";
            return;
        }

        $controllerInstance = new $controller;
        $response = $controllerInstance->$metodo($params);
        
        if(!$response) {
            $response = [
                'code' => '400',
                'load' => [
                    'message' => 'Bad Request...'
                ]
            ];
        }

        $this->enviarResposta($response);
    }

    private function enviarResposta($response) {
        header("Content-Type: text/plain");
        http_response_code($response['code']);
        echo json_encode($response['load']);
        exit;
    }

    private static $mapping = [
        "GET" => [
            "balance" => ["controller"    => "AccountController",     "method" => "getBalance"]
        ],
        "POST" => [
            "reset"   => ["controller"    => "RuntimeMemoryService",  "method" => "resetMemory"],
            "event"   => ["controller"    => "AccountController",     "method" => "processEvent"]
        ]
    ];
}