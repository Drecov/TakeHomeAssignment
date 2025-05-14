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
    
    public function processEvent($params) {
        if(isset($params['type'])) {
            switch($params['type']) {
                case 'deposit':
                    return $this->processDeposit($params);
                case 'withdraw':
                    return $this->processWithdraw($params);
                case 'transfer':
                    return $this->processTransfer($params);
                default:
                    return null;
            }
        }
    }

    private function processDeposit($params) {
        if(!isset($params['destination']) || !isset($params['amount'])) {
            return null;
        }
        $accNumber  = (int) $params['destination'];
        $amount     = (float) $params['amount'];

        $account = RuntimeMemoryService::findAccountByAccountNumber($accNumber);

        if(!$account || !($account instanceof Account)) {
            $account = new Account($accNumber, $amount);
            DatabaseService::insertAccount($account);
            return ['code' => 201, 'load'=> ['destination' => ['id'=>$accNumber, 'balance'=>$amount]]];

        } else {
            $balance = $account->getBalance() + $amount;
            $account->setBalance($balance);
            $result = RuntimeMemoryService::updateAccountStorage($account);
            if($result) {
                return ['code' => 201, 'load'=> ['destination' => ['id'=>$accNumber, 'balance'=>$balance]]];
            }
        }
        return null;
    }

    private function processWithdraw($params) {}

    private function processTransfer($params) {}

}