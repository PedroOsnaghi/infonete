<?php

class UsuarioModel{

    protected $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    protected function guardar($nombreUsuario, $pass, $rol){
        return $this->database->insert("INSERT INTO usuario(nombreUsuario, pass, rol) 
                                        VALUES('$nombreUsuario','$pass',$rol)");
    }
}
