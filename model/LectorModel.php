<?php

include_once ("model/UsuarioModel.php");

class LectorModel extends UsuarioModel
{
    //1 : ADMIN
    //2 : CONTENIDISTA
    //3 : LECTOR
    const ROL_LECTOR = 3;
    private $pass;
    private $nombreUsuario;
    private $ubicacion;
    private $dni;
    private $apellido;
    private $nombre;
    private $email;




    public function __construct($database)
  {
      parent::__construct($database);
  }


  //GETTERS Y SETTERS

    public function getPass()
    {
        return $this->pass;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    public function getDni()
    {
        return $this->dni;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;
    }

    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }



    //cuando registro al lector se registra a un usuario y luego al lector
    public function registrar(){

        $id_usuario = parent::guardar($this->nombreUsuario, $this->pass, self::ROL_LECTOR);

        return  $this->database->execute("INSERT INTO lector(idUsuario, nombre, apellido, dni, ubicacion, email)
                              VALUES($id_usuario,'$this->nombre','$this->apellido','$this->dni', $this->ubicacion, '$this->email')");
    }


}