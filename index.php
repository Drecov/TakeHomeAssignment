<?php
require 'services/RestService.php';

$restService    = new RestService();
$memory         = new RuntimeMemoryService();

$restService->processaRequest();

