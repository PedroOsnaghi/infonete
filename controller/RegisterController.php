<?php

class RegisterController{

    private $registerModel;
    private $usuarioModel;
    private $render;

    public function __construct($registerModel, $usuarioModel, $render)
    {
        $this->registerModel = $registerModel;
        $this->usuarioModel = $usuarioModel;
        $this->render = $render;
    }

    public function execute()
    {
        echo $this->render->render("public/view/registro.mustache");
    }

    //el formulario llama a esta funcion
    public function validarRegistro()
    {
        $usuario = $this->usuarioValidado();

        $this->registerModel->registrar($usuario) ?
            $data["success"] = "registro realizado con éxito" :
            $data["error"] = "ocurrió un error al registrarse";

        echo $this->render->render("public/view/registro.mustache", $data);
    }

    private function usuarioValidado()
    {
        $nombreUsuario = $_POST['usuario'] ?? false; //si esta seteado pone lo que recibe por post y si no false
        $pass = $_POST['pass'] ?? false; //TODO enviar mensaje de error, falta validar
        $nombre = $_POST['nombre'] ?? false;
        $apellido = $_POST['apellido'] ?? false;
        $dni = $_POST['dni'] ?? false;
        $ubicacion = $_POST['ubicacion'] ?? false;
        $email = $_POST['email'] ?? false;
    }

    private function notEmpty($string)
    {

    }

    private function sendError($err_msg)
    {
        
    }


}