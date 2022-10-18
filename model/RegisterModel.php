<?php

class RegisterModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registrar($Usuario){

        return $Usuario->registrar();

    }
}