<?php

class UsuarioModel{

    private $database;
    private $id;
    private $nombreUsuario;
    private $pass;

    public function __construct($database)
    {
        $this->database = $database;
    }


}
