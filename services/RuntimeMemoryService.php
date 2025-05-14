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

    public static function resetMemory($params) {
        self::verificarSessaoAtiva();
        self::setAccountStorage([]);
    }

    public static function findAccountByAccountNumber($accountNumber) {
        self::verificarSessaoAtiva();
        if(isset($_SESSION['accountStorage'])) {
            foreach($_SESSION['accountStorage'] as $account) {
                if($account['accNumber'] == $accountNumber) {
                    return $account;
                }
            }
        }
        return null;
    }
}