<?php

class AccountController {

    public function getBalance($params) {
        if(!isset($params['account_id']) || !$params['account_id']) {
            return false;
        }
        $accId = $params['account_id'];
        $account = RuntimeMemoryService::findAccountByAccountNumber($accId);

        if(!$account) {
            return ['code' => 404, 'load'=> 0];
        }

        $balance = $account->getBalance();
        return ['code' => 200, 'load'=> $balance];
    }
    
    public function processEvent($params): void {

    }
}