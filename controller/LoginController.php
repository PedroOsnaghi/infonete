<?php

class LoginController
{

    private $render;//render llama a la vista
    private $loginModel;

    public function __construct($loginModel, $render)
    {
        $this->loginModel = $loginModel;
        $this->render = $render;
    }

    //cuando se ejecuta excecute muestra la vista de Login
    //se ejecuta cuando no especificamos un metodo por la url
    //infonete.com/login
    public function execute()
    {
        echo $this->render->render("public/view/login.mustache");
    }

    public function validar()
    {
        //validar info
        $nombreUsuario = $_POST['usuario'] ?? false; //si esta seteado pone lo que recibe por post y si no false
        $pass = $_POST['pass'] ?? false; //TODO enviar mensaje de error, falta validar

        //llamar a auth de Login Model
        $result = $this->loginModel->auth($nombreUsuario, $pass);

        // $result es un arreglo con la cantidad de elementos encontrados
        if (sizeof($result) > 0) {
            echo $this->render->render("public/view/home.mustache");
        } else {
            $data['error'] = "Nombre de usuario y/o contraseña incorrecta";
            echo $this->render->render("public/view/login.mustache", $data);
        }
    }

}