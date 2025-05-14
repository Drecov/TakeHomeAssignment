<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'autoload.php';

print "<br>Index.php";

$restService    = new RestService();
var_dump($restService);
$restService->processaRequest();