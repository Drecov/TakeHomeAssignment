<?php

class RuntimeMemoryService {
    public function __construct() {
        self::verificarSessaoAtiva();
    }

    private static function verificarSessaoAtiva() {
        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if(!isset($_SESSION['accountStorage'])) {
            $_SESSION['accountStorage'] = [];
        }
    }

    public static function setAccountStorage($accountStorage) {
        self::verificarSessaoAtiva();
        $_SESSION['accountStorage'] = $accountStorage;
    }
    public static function getAccountStorage() {
        self::verificarSessaoAtiva();
        return $_SESSION['accountStorage'];
    }


    public static function addAccountStorage(Account $account) {
        self::verificarSessaoAtiva();
        $_SESSION['accountStorage'][] = $account;
    }

    public static function resetMemory($params=null) {
        self::verificarSessaoAtiva();
        self::setAccountStorage([]);
        return ['code' => 200, 'load'=> 'OK'];
    }

    public static function findAccountByAccountNumber($accountNumber) {
        self::verificarSessaoAtiva();
        $accountArray = self::getAccountStorage();
        $resultado = array_values(array_filter($accountArray, function($account) use ($accountNumber) {
            return $account->getAccNumber() === ((int) $accountNumber);
        }));
        
        return $resultado[0] ?? null;
    }

    public static function updateAccountStorage(Account $accountAtualizado) {
        self::verificarSessaoAtiva();
        $accountArray = self::getAccountStorage();
        $accNumber = $accountAtualizado->getAccNumber();

        foreach ($accountArray as $index => $account) {
            if ($account instanceof Account && $account->getAccNumber() === $accNumber) {
                $accountArray[$index] = $accountAtualizado;
                self::setAccountStorage($accountArray);
                return true;
            }
        }
        return false;
    }
}