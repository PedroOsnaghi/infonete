<?php

class LoginController
{

    private $render;//render llama a la vista
    private $loginModel;
    private $session;

    public function __construct($loginModel, $session, $render)
    {
        $this->loginModel = $loginModel;
        $this->session = $session;
        $this->render = $render;
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

        $usuarioAuth = $this->loginModel->auth($email, hasher::encrypt($pass));

        // $usuarioAuth es un objeto usuario o null
        if ($usuarioAuth != null){
            $this->validateActivation($usuarioAuth);
        }else{
            $this->errorAccess();
        }



    }

    private function validateActivation($user)
    {
        if($user->getEstado() == UsuarioModel::STATE_UNVERIFIED){
            $data['error'] = "La cuenta aún no fue verificada. se envio un correo a " . $user->getEmail() ." con el link de verificación";
            echo $this->render->render("public/view/login.mustache", $data);
        }
        //si esta verificada
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
        echo $this->render->render("public/view/home.mustache");
    }

}