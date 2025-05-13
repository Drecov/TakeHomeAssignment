<?php

class Account {
    public $id;
    public $balance;
    public function __construct($id, $balance) {
        $this->id = $id;
        $this->balance = $balance;
    }
}