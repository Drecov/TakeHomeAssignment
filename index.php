<?php
//Ponto de entrada do sistema.
error_reporting(E_ERROR);
ini_set('display_errors', 0);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'autoload.php';

$restService    = new RestService();
$restService->processaRequest();