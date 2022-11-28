<?php

class LoginController
{

    private $render;
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

    /**
     * Metodo inicial que lanza la vista Loguin.
     *
     * @return Html
     */
    public function execute()
    {
        echo $this->render->render("public/view/login.mustache");
    }

    /**
     * Metodo que recibe correo y pass y solicita autenticacion.
     *
     * @return Acceso | error
     */
    public function validar()
    {
        //validar info
        $email = $_POST['email'];
        $pass = $_POST['pass'] ;

        $usuarioAuth = $this->usuarioModel->autenticar($email, hasher::encrypt($pass));

        ($usuarioAuth) ? $this->getAccess($usuarioAuth) : $this->errorAccess();

    }

    /**
     * Metodo privado que se ejecuta en caso de validacion exitosa.
     *
     * @return void
     */
    private function getAccess($user)
    {
        //si esta todo ok
        $this->iniciarSession($user);
        $this->goToHome();
    }

    /**
     * Metodo privado que retorna a la vista loguin con el error de la autenticacion.
     *
     * @return Html
     */
    private function errorAccess()
    {
        $data['error'] = $this->usuarioModel->getErrorAccess();
        echo $this->render->render("public/view/login.mustache", $data);
    }

    /**
     * Metodo privado que carga al usuario autenticado en la sesion.
     *
     * @return void
     */
    private function iniciarSession($user)
    {
        $this->session->setAuthUser($user);
    }

    /**
     * Metodo privado que redirecciona al 'Home'.
     *
     * @return void
     */
    private function goToHome()
    {
        Redirect::doIt("/infonete");
    }

}