<?php

class UsuarioModel {

    //constantes de Roles de Usuario
    const ROL_LECTOR = 1;
    const ROL_REDACTOR = 2;
    const ROL_EDITOR = 3;
    const ROL_ADMIN = 4;

    //constantes de estado
    const STATE_UNVERIFIED = 0;
    const STATE_VERIFIED = 1;

    //Propiedades
    private $nombre;
    private $apellido; 
    private $ubicacion;
    private $pass;
    private $nombreUsuario;
    private $email;
    private $activo;
    private $estado;
    private $rol;

    private $database;


    //Getters & Setters
    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
    }

    public function getActivo()
    {
        return $this->activo;
    }

    public function setActivo($activo)
    {
        $this->activo = $activo;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }



    public function __construct($database)
    {
        $this->database = $database;
    }

    protected function registrar(){
        return $this->database->execute("INSERT INTO usuario (nombre, apellido, ubicacion, nombreUsuario, email, pass, rol, estado, activo)
                                        VALUES('$this->nombre',
                                               '$this->Apellido',
                                                $this->ubicacion, 
                                               '$this->email', 
                                               '$this->pass', 
                                                $this->>rol, 
                                                $this->estado, 
                                                $this->activo");
    }
}
