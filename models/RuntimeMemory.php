<?php

class RuntimeMemory {
    private static $accountStorage = [];

    public function setAccountStorage($accountStorage) {
        self::$accountStorage = $accountStorage;
    }
    public function getAccountStorage() {
        return self::$accountStorage;
    }
}