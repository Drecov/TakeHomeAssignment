<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'autoload.php';

//Cria um initial Load para testes
$initialStorage = new Account(101010, 100);

RuntimeMemoryService::resetMemory();
RuntimeMemoryService::addAccountStorage($initialStorage);

$restService    = new RestService();
$restService->processaRequest();