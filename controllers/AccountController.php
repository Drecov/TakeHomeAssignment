<?php

class AccountController {

    public function getBalance($params) {
        if(!isset($params['account_id']) || !$params['account_id']) {
            return false;
        }

        $accId = $params['account_id'];
        $account = RuntimeMemoryService::findAccountByAccountNumber($accId);

        if(!$account || !($account instanceof Account)) {
            return ['code' => 404, 'load'=> 0];
        } else {
            return ['code' => 200, 'load'=> $account->getBalance()];
        }
    }
    
    public function processEvent($params): void {

    }
}