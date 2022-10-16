<?php

class LoginModel
{   //aca va la logica

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function auth(){//autorizar

        //validar info
        $nombreUsuario = $_POST['usuario'] ?? false; //si esta seteado pone lo que recibe por post y si no false
        $pass = $_POST['pass'] ?? false; //TODO enviar mensaje de error, falta validar

        //devuelve un array, si esta vacio no encontro nada
        return $this->database->query("SELECT * FROM Usuario 
               WHERE nombreUsuario = '$nombreUsuario' AND pass = '$pass'"); //TODO falta la encriptacion
    }
}