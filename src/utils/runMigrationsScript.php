<?php
//Script para rodar todas as migrations do sistema.

$host = 'mysql';
$db   = 'ebanx';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

$migrationsPath = '/var/www/html/utils/migrations';
$files = glob("$migrationsPath/*.sql");
sort($files);

foreach ($files as $file) {
    echo "> Executando migration: $file\n";
    $sql = file_get_contents($file);
    try {
        $pdo->exec($sql);
        echo "> Migration executada com sucesso\n";
    } catch (PDOException $e) {
        echo "> Erro na migration $file: " . $e->getMessage() . "\n";
    }
}

echo "> Todas as migrations foram processadas.\n";