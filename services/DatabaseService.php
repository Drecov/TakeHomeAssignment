<?php

class DatabaseService {
    private const HOST = "localhost";
    private const PORT = 3306;
    private const USER = "root";
    private const PASSWORD = "";
    private const DATABASE = "takehomeassignment";
    private static $pdo;

    private static function initPdo() {
        if (!self::$pdo) {
            self::$pdo = new PDO("mysql:host=" . self::HOST . ";port=" . self::PORT . ";dbname=" . self::DATABASE,
        self::USER, self::PASSWORD);
        }
    }

    public static function runMigrations() {
        self::initPdo();

        $migrationsDir = __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'migrations';

        if (!is_dir($migrationsDir)) {
            print"Diretório de migrations não encontrado: $migrationsDir";
            return false;
        }

        $files = glob($migrationsDir . DIRECTORY_SEPARATOR . '*.sql');
        sort($files);

        foreach ($files as $file) {
            $sql = file_get_contents($file);
            if (trim($sql)) {
                self::$pdo->exec($sql);
            }
        }
    }

    public function resetAccountDatabase() {
        self::initPdo();
        $query = "DELETE FROM account WHERE true;";
        self::$pdo->exec($query);
        return ['code' => 200, 'load'=> 'OK'];
    }

    public static function insertAccount(Account $data) {
        self::initPdo();
        $query = "INSERT INTO account (id, acc_number, balance) VALUES (:id, :acc_number, :balance);";
        $stmt = self::$pdo->prepare($query);
        $ret = $stmt->execute([
            ":id"=> $data->getId(),
            ":acc_number"=> $data->getAccNumber(),
            ":balance"=>$data->getBalance()
        ]);
        return $ret;
    }

    public static function updateAccount(Account $data) {
        self::initPdo();
        $query = "UPDATE account set balance=:balance WHERE acc_number = :acc_number;";
        $stmt = self::$pdo->prepare($query);
        $ret = $stmt->execute([
            ":acc_number"=> $data->getAccNumber(),
            ":balance"=>$data->getBalance()
        ]);
        return $ret;
    }

    public static function selectAccount(int $acc_number) {
        self::initPdo();
        $query = "SELECT * FROM account WHERE acc_number = :acc_number LIMIT 1;";
        $stmt = self::$pdo->prepare($query);
        $retQuery = $stmt->execute([
            ":acc_number"=> $acc_number
        ]);
        if($retQuery) {
            $ret = $stmt->fetch(PDO::FETCH_ASSOC);
            if($ret) {
                return new Account($ret['acc_number'], $ret['balance']);
            }
        }
        return false;
    }
}