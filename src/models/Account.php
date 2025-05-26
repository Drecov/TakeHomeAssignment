<?php

//Modelo do Account com Id, numero da conta e saldo.
class Account {
    private string $id;
    private float $balance;
    private int $accNumber;

    public function __construct($accNumber, $balance) {
        $this->id = uniqid();
        $this->accNumber = $accNumber;
        $this->balance = $balance;
    }

    public function getId() {
        return $this->id;
    }
    public function getBalance() {
        return $this->balance;
    }
    public function getAccNumber() {
        return $this->accNumber;
    }
    public function setBalance($balance) {
        $this->balance = $balance;
    }
    public function setAccNumber($accNumber) {
        $this->accNumber = $accNumber;
    }
}