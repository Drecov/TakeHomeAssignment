<?php

class AccountController {

    public function getBalance($params) {
        if(!isset($params['account_id']) || !$params['account_id']) {
            return false;
        }

        $accId = $params['account_id'];
        $account = DatabaseService::selectAccount($accId);

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
            return false;
        }
        $accNumber  = (int) $params['destination'];
        $amount     = (float) $params['amount'];

        $account = DatabaseService::selectAccount($accNumber);

        if(!$account || !($account instanceof Account)) {
            $account = new Account($accNumber, $amount);
            DatabaseService::insertAccount($account);
            return ['code' => 201, 'load'=> "{\"destination\": {\"id\":\"$accNumber\", \"balance\":$amount}}"];

        } else {
            $balance = $account->getBalance() + $amount;
            $account->setBalance($balance);
            $result = DatabaseService::updateAccount($account);
            if($result) {
                return ['code' => 201, 'load'=> "{\"destination\": {\"id\":\"$accNumber\", \"balance\":$balance}}"];
            }
        }
        return false;
    }

    private function processWithdraw($params) {
        if(!isset($params['origin']) || !isset($params['amount'])) {
            return false;
        }
        $accNumber  = (int) $params['origin'];
        $amount     = (float) $params['amount'];

        $account = DatabaseService::selectAccount($accNumber);
        if(!$account || !($account instanceof Account)) {
            return ['code' => 404, 'load'=> 0];
        }

        $balance = $account->getBalance() - $amount;
        $account->setBalance($balance);
        $result = DatabaseService::updateAccount($account);
        if($result) {
            return ['code' => 201, 'load'=> "{\"origin\": {\"id\":\"$accNumber\", \"balance\":$balance}}"];
        }
        return false;
    }

    private function processTransfer($params) {
        if(!isset($params['origin']) || !isset($params['destination']) || !isset($params['amount'])) {
            return false;
        }

        $accOrigin      = (int) $params['origin'];
        $accDestination = (int) $params['destination'];
        $amount         = (float) $params['amount'];

        $origin         = DatabaseService::selectAccount($accOrigin);
        $destination    = DatabaseService::selectAccount($accDestination);

        if(!($origin && $destination)) {
            return ['code'=> 404, ''=> 0];
        }

        $retOrigin      = $this->processWithdraw(['origin' => $accOrigin, 'amount' => $amount]);
        $retDestination = $this->processDeposit( ['destination' => $accDestination, 'amount' => $amount]);

    }

}