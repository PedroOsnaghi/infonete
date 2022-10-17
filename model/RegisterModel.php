<?php

class RegisterModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registrarLector($nombreUsuario, $pass, $nombre, $apellido, $dni, $ubicacion, $email){

        $lector = new LectorModel($this->database);
        $lector->setNombre($nombre);
        $lector->setApellido($apellido);
        $lector->setDni($dni);
        $lector->setUbicacion($ubicacion);
        $lector->setNombreUsuario($nombreUsuario);
        $lector->setPass($pass);
        $lector->setEmail($email);

        return $lector->registrar();

    }
}