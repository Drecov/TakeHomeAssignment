<?php

class RuntimeMemory {
    private static $accountStorage = [];

    public function setAccountStorage($accountStorage) {
        self::$accountStorage = $accountStorage;
    }
    public function getAccountStorage() {
        return self::$accountStorage;
    }
    public function addAccountStorage(Account $account) {
        self::$accountStorage[] = $account;
    }
}