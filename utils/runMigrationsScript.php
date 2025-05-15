<?php
//Script para rodar todas as migrations do sistema.
require_once __DIR__ . DIRECTORY_SEPARATOR . "autoload.php";
DatabaseService::runMigrations();