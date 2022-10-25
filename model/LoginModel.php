<?php

class LoginModel
{   //aca va la logica

    private $database;
    private $usuarioModel;



    public function __construct($database, $usuarioModel)
    {
        $this->database = $database;
        $this->usuarioModel = $usuarioModel;

    }

    public function auth($email, $pass){//autorizar

        //devuelve un array, si esta vacio no encontro nada
        $query = $this->database->query("SELECT * FROM usuario WHERE email = '$email' AND pass = '$pass'");

        if ($query != null)
            return $this->toUser($query);

        return null;

    }

    private function toUser($query)
    {
        $this->usuarioModel->setNombre($query['nombre']);
        $this->usuarioModel->setApellido($query['apellido']);
        $this->usuarioModel->setPass($query['pass']);
        $this->usuarioModel->setEmail($query['email']);
        $this->usuarioModel->setDomicilio($query['domicilio']);
        $this->usuarioModel->setLatitud($query['latitud']);
        $this->usuarioModel->setLongitud($query['longitud']);
        $this->usuarioModel->setAvatar($query['avatar']);
        $this->usuarioModel->setHash($query['vhash']);
        $this->usuarioModel->setRol($query['rol']);
        $this->usuarioModel->setEstado($query['estado']);
        $this->usuarioModel->setActivo($query['activo']);

        return $this->usuarioModel;
    }
}