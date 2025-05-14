<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'autoload.php';

print "Index.php <br>";

$restService    = new RestService();
var_dump($restService);
$restService->processaRequest();