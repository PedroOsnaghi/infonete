<?php

class RegisterController{

    private $registerModel;
    private $usuarioModel;
    private $mailer;
    private $render;
    private $file;

    public function __construct($registerModel, $usuarioModel, $mailer, $file, $render)
    {
        $this->registerModel = $registerModel;
        $this->usuarioModel = $usuarioModel;
        $this->mailer = $mailer;
        $this->file = $file;
        $this->render = $render;
    }

    public function execute()
    {
        echo $this->render->render("public/view/registro.mustache");
    }

    public function validar()
    {
        $usuario = $this->usuarioValidado();


        if ($this->registerModel->registrar($usuario)){
           if($this->mailer->sendEmailVerification($usuario->getEmail(), $usuario->getHash())){
               $data['email'] = $usuario->getEmail();
               echo $this->render->render("public/view/register-success.mustache", $data);
           }

        } else{
            $data["error"] = "ocurrió un error al registrarse";
            echo $this->render->render("public/view/registro.mustache", $data);
        }

    }

    private function usuarioValidado()
    {


        $this->usuarioModel->setNombre($this->notEmpty("Nombre", $_POST['nombre']));
        $this->usuarioModel->setApellido($this->notEmpty("Apellido", $_POST['apellido']));
        $this->usuarioModel->setPass($this->notEmpty("Password", hasher::encrypt($_POST['pass'])));
        $this->usuarioModel->setEmail($this->notEmpty("Email", $_POST['email']));
        $this->usuarioModel->setDomicilio($this->notEmpty("Direccion", $_POST['direccion']));
        $this->usuarioModel->setLatitud($this->notEmpty("Latitud", $_POST['lat']));
        $this->usuarioModel->setLongitud($this->notEmpty("Longitud", $_POST['lng']));
        $this->usuarioModel->setAvatar($this->getFileName());
        $this->usuarioModel->setHash(md5(rand(0000000000, 9999999999)));
        $this->usuarioModel->setRol(UsuarioModel::ROL_LECTOR);
        $this->usuarioModel->setEstado(UsuarioModel::STATE_UNVERIFIED);
        $this->usuarioModel->setActivo(1);

        return $this->usuarioModel;
    }

    private function notEmpty($key, $value)
    {
       return !empty($value) ? $value : $this->sendError("El campo $key no puede ser vacío");
    }

    private function sendError($err_msg)
    {
        $data["error"] = $err_msg;
        echo $this->render->render("public/view/registro.mustache", $data);
    }

    private function getFileName(){
        return ($this->file->uploadFile("profiles"))?
                $this->file->get_file_uploaded():
                'default.png';
    }




}