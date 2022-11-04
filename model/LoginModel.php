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
        $query = $this->database->query("SELECT u.*,r.rol_name FROM usuario u JOIN rol r ON u.rol = r.id WHERE u.email = '$email' AND u.pass = '$pass'");

        if ($query != null)
            return $this->toUser($query);
        return null;

    }

    private function toUser($query)
    {
        return $this->usuarioModel->toObject($query);
    }
}