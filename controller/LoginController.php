<?php

class LoginController{

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
    public function excecute(){
        echo $this->render->render("public/view/login.php");
    }

    public function validar(){
        //llamar a auth de Login Model
        $result = $this->loginModel->auth();
        // $result es un arreglo con la cantidad de elementos encontrados

    }

}