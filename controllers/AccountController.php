<?php

class AccountController {

    //Retorna o saldo de uma conta.
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
    
    //Distribui o request de /event para as funções de processamento de saque, transferência ou depósito.
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

    //Processa depósitos. Cria uma nova conta caso o depósito seja numa conta que não existe no banco de dados.
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
            return ['code' => 201, 'load'=> "{\"destination\": {\"id\":\"$accNumber\", \"balance\":$amount}}", 'balance' => $amount];

        } else {
            $balance = $account->getBalance() + $amount;
            $account->setBalance($balance);
            $result = DatabaseService::updateAccount($account);
            if($result) {
                return ['code' => 201, 'load'=> "{\"destination\": {\"id\":\"$accNumber\", \"balance\":$balance}}", 'balance' => $balance];
            }
        }
        return false;
    }

    //Processa saque. Retorna erro caso a conta não exista.
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
            return ['code' => 201, 'load'=> "{\"origin\": {\"id\":\"$accNumber\", \"balance\":$balance}}", 'balance' => $balance];
        }
        return false;
    }

    //Processa tansferência. Faz um saque e um depósito de duas contas distintas.
    private function processTransfer($params) {
        if(!isset($params['origin']) || !isset($params['destination']) || !isset($params['amount'])) {
            return false;
        }

        $accOrigin      = (int) $params['origin'];
        $accDestination = (int) $params['destination'];
        $amount         = (float) $params['amount'];

        $origin         = DatabaseService::selectAccount($accOrigin);
        $destination    = DatabaseService::selectAccount($accDestination);

        if(!$origin) {
            return ['code'=> 404, 'load'=> 0];
        }

        $retOrigin      = $this->processWithdraw(['origin' => $accOrigin, 'amount' => $amount]);
        $retDestination = $this->processDeposit( ['destination' => $accDestination, 'amount' => $amount]);

        if(!isset($retOrigin['balance']) || !isset($retDestination['balance'])) {
            return false;
        } else {
            $originBalance = $retOrigin['balance'];
            $destinationBalance = $retDestination['balance'];
        }

        return ['code' => 201, 'load'=> "{\"origin\": {\"id\":\"$accOrigin\", \"balance\":$originBalance}, 
        \"destination\": {\"id\":\"$accDestination\", \"balance\":$destinationBalance}}"];
    }

}