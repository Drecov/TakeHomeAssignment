<?php

class RuntimeMemoryService {
    public function __construct() {
        self::verificarSessaoAtiva();
    }

    private static function verificarSessaoAtiva() {
        if(session_status() !== PHP_SESSION_ACTIVE) {
            print "> Iniciando Sessão\n";
            session_start();
        }

        if(!isset($_SESSION['accountStorage'])) {
            print "> Iniciando accountStorage\n";
            $_SESSION['accountStorage'] = [];
        }
    }

    public function setAccountStorage($accountStorage) {
        self::verificarSessaoAtiva();
        $_SESSION['accountStorage'] = $accountStorage;
    }
    public function getAccountStorage() {
        self::verificarSessaoAtiva();
        return $_SESSION['accountStorage'];
    }
    public function addAccountStorage(Account $account) {
        self::verificarSessaoAtiva();
        $_SESSION['accountStorage'][] = $account;
    }
}