<?php

//Classe para processar todos os requests que chegam à API.
class RestService {

    //Processa o request, direcionando-o para o método mapeado.
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
            echo ">>> Erro ao processar request. Endpoint $endpoint não encontrado. <<<";
            return;
        }

        if(!method_exists($controller, $metodo)) {
            http_response_code(400);
            echo ">>> Erro ao processar request. Endpoint $endpoint não encontrado. <<<";
            return;
        }

        $controllerInstance = new $controller;
        $response = $controllerInstance->$metodo($params);
        
        if(!$response) {
            $response = [
                'code' => '400',
                'load' => 'Bad Request'
                ];
        }

        $this->enviarResposta($response);
    }

    //Envia a resposta ao servidor.
    private function enviarResposta($response) {
        header("Content-Type: text/plain");
        http_response_code($response['code']);
        echo $response['load'];
        exit;
    }

    //Faz o mapeamento dos endpoits para os métodos corretos do sistema.
    private static $mapping = [
        "GET" => [
            "balance" => ["controller"    => "AccountController",   "method" => "getBalance"]
        ],
        "POST" => [
            "reset"   => ["controller"    => "DatabaseService",     "method" => "resetAccountDatabase"],
            "event"   => ["controller"    => "AccountController",   "method" => "processEvent"]
        ]
    ];
}