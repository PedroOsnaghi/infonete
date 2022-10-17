<?php

class RegisterController{

    private $registerModel;
    private $render;

    public function __construct($registerModel, $render)
    {
        $this->registerModel = $registerModel;
        $this->render = $render;
    }

    public function execute()
    {
        echo $this->render->render("public/view/registro.mustache");
    }

    //el formulario llama a esta funcion
    public function validarRegistro()
    {

        $nombreUsuario = $_POST['usuario'] ?? false; //si esta seteado pone lo que recibe por post y si no false
        $pass = $_POST['pass'] ?? false; //TODO enviar mensaje de error, falta validar
        $nombre = $_POST['nombre'] ?? false;
        $apellido = $_POST['apellido'] ?? false;
        $dni = $_POST['dni'] ?? false;
        $ubicacion = $_POST['ubicacion'] ?? false;
        $email = $_POST['email'] ?? false;

        $this->registerModel->registrarLector($nombreUsuario, $pass, $nombre, $apellido, $dni, $ubicacion, $email) ?
            $data["success"] = "registro realizado con exito" :
            $data["error"] = "ocurrió un error al registrarse";

        echo $this->render->render("public/view/registro.mustache", $data);
    }
}