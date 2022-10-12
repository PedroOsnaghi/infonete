<?php

class QuieroSerParteController{
    private $render;

    public function __construct($render){
        $this->render = $render;
    }

    public function execute(){
        echo $this->render->render("view/quieroSerParteView.php");
    }

    public function procesarFormulario(){
        $data["nombre"] = $_POST["nombre"];
        $data["instrumento"]  = $_POST["instrumento"];
        echo $this->render->render( "view/quiereSerParteView.php", $data);
    }
}