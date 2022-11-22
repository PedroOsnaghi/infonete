<?php

class LoginController
{

    private $render;//render llama a la vista
    private $usuarioModel;
    private $session;
    private $logguer;

    public function __construct($usuarioModel, $session, $render, $logger)
    {
        $this->usuarioModel = $usuarioModel;
        $this->session = $session;
        $this->render = $render;
        $this->logguer = $logger;
    }

    public function execute()
    {
        echo $this->render->render("public/view/login.mustache");
    }

    public function validar()
    {
        //validar info
        $email = $_POST['email'];
        $pass = $_POST['pass'] ;

        $usuarioAuth = $this->usuarioModel->autenticar($email, hasher::encrypt($pass));

        // $usuarioAuth es un objeto usuario o null
        if ($usuarioAuth != null){
            $this->validateActivation($usuarioAuth);
        }else{
            $this->errorAccess();
        }



    }

    private function validateActivation($user)
    {
        //verificar que esta activo para ingresar
        if($user->getActivo() == 0){
            $data['error'] = "Disculpe, su cuenta se ecuentra inhabilitada para acceder al sistema";
            echo $this->render->render("public/view/login.mustache", $data);
            exit();
        }
        //verificar activacion de cuenta
        if($user->getEstado() == UsuarioModel::STATE_UNVERIFIED){
            $data['error'] = "La cuenta aún no fue verificada. se envio un correo a " . $user->getEmail() ." con el link de verificación";
            echo $this->render->render("public/view/login.mustache", $data);
            exit();
        }
        //si esta todo ok
        $this->iniciarSession($user);
        $this->goToHome();
    }

    private function errorAccess(){
        $data['error'] = "Correo y/o contraseña incorrecta";
        echo $this->render->render("public/view/login.mustache", $data);
    }

    private function iniciarSession($user){
        //guardamos los datos del usuario en la session para poder
        //accederlos a lo largo de la aplicacion
        $this->session->setAuthUser($user);
    }

    private function goToHome(){
        Redirect::doIt("/infonete");
    }

}