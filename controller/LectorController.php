<?php

class LectorController{

    private $lectorModel;
    private $render;

    public function __construct($lectorModel, $render)
    {
        $this->lectorModel = $lectorModel;
        $this->render = $render;
    }

    public function login(){
        echo $this->render->render("view/login.php");
    }
}