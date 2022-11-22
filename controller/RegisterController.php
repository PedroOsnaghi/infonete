<?php

class RegisterController{

    private $usuarioModel;
    private $render;


    public function __construct($usuarioModel, $render)
    {
        $this->usuarioModel = $usuarioModel;

        $this->render = $render;
    }

    public function execute()
    {
        echo $this->render->render("public/view/registro.mustache");
    }

    public function validar()
    {
        $usuario = $this->setearUsuario();

        $response = $usuario->registrar();

        if($response['status'] == 'success')
            echo $this->render->render("public/view/register-success.mustache", $response);

        $this->sendError("ocurrió un error al registrarse");


    }

    private function setearUsuario()
    {
        $this->usuarioModel->setNombre($_POST['nombre']);
        $this->usuarioModel->setApellido($_POST['apellido']);
        $this->usuarioModel->setPass(hasher::encrypt($_POST['pass']));
        $this->usuarioModel->setEmail($_POST['email']);
        $this->usuarioModel->setDomicilio($_POST['direccion']);
        $this->usuarioModel->setLatitud($_POST['lat']);
        $this->usuarioModel->setLongitud($_POST['lng']);
        $this->usuarioModel->setHash(md5(rand(0000000000, 9999999999)));
        $this->usuarioModel->setRol(UsuarioModel::ROL_LECTOR);
        $this->usuarioModel->setEstado(UsuarioModel::STATE_UNVERIFIED);
        $this->usuarioModel->setActivo(1);

        return $this->usuarioModel;
    }


    private function sendError($err_msg)
    {
        $data["error"] = $err_msg;
        echo $this->render->render("public/view/registro.mustache", $data);
    }






}