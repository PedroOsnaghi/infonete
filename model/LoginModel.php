<?php

class LoginModel
{   //aca va la logica

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function auth($nombreUsuario, $pass){//autorizar
        //devuelve un array, si esta vacio no encontro nada
        return $this->database->query("SELECT * FROM Usuario 
               WHERE nombreUsuario = '$nombreUsuario' AND pass = '$pass'"); //TODO falta la encriptacion
    }
}