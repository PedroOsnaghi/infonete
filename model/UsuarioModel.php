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
    private $id;
    private $nombre;
    private $apellido;
    private $pass;
    private $email;
    private $avatar;
    private $domicilio;
    private $latitud;
    private $longitud;
    private $activo;
    private $estado;
    private $hash;
    private $rol;

    private $database;

    //Getters & Setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

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

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getLongitud()
    {
        return $this->longitud;
    }

    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function getDomicilio()
    {
        return $this->domicilio;
    }

    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;
    }

    public function getLatitud()
    {
        return $this->latitud;
    }

    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
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

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }




    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registrar(){

        return $this->database->execute("INSERT INTO usuario (nombre, apellido, email, pass, domicilio, latitud, longitud, avatar, vhash, rol, estado, activo)
                                        VALUES('$this->nombre',
                                               '$this->apellido',
                                               '$this->email', 
                                               '$this->pass', 
                                               '$this->domicilio',
                                               '$this->latitud',
                                               '$this->longitud',
                                               '$this->avatar',
                                               '$this->hash',
                                                $this->rol, 
                                                $this->estado, 
                                                $this->activo)");
    }

    public function activate($email, $hash){
        return $this->database->execute("UPDATE usuario SET estado =" . self::STATE_VERIFIED . " WHERE email ='" . $email . "' AND vhash = '" . $hash ."'" );
    }

    public function existeEmail($email){
        return $this->database->query("SELECT COUNT(email) 'email' FROM usuario WHERE email='". $email ."' GROUP BY email");
    }
}

// []
