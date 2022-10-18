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
        $this->usuarioModel->setNombre($this->notEmpty("Nombre", $_POST['nombre']));
        $this->usuarioModel->setApellido($this->notEmpty("Apellido", $_POST['apellido']));
        $this->usuarioModel->setUbicacion($this->notEmpty("Ubicacíon", $_POST['ubicacion']));
        $this->usuarioModel->setNombreUsuario($this->notEmpty("Nombre de usuario", $_POST['usuario']));
        $this->usuarioModel->setPass($this->notEmpty("Password", $_POST['pass']));
        $this->usuarioModel->setEmail($this->notEmpty("Email", $_POST['email']));
        $this->usuarioModel->setRol(UsuarioModel::ROL_LECTOR);
        $this->usuarioModel->setEstado(UsuarioModel::STATE_UNVERIFIED);
        $this->usuarioModel->setActivo(1);

        return $this->usuarioModel();
    }

    private function notEmpty($key, $value)
    {
        !empty($value) ? $value : $this->sendError("El campo $key no puede ser vacío");
    }

    private function sendError($err_msg)
    {
        $data["error"] = $err_msg;
        echo $this->render->render("public/view/registro.mustache", $data);
    }


}