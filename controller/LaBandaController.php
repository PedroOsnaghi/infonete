<?php

class LaBandaController
{
    private $render;

    public function __construct($render)
    {
        $this->render = $render;
    }

    public function execute()
    {
        echo $this->render->render("view/labanda.php");
    }
}