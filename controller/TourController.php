<?php

class TourController
{
    private $presentacionModel;
    private $render;

    public function __construct($presentacionModel, $render)
    {
        $this->presentacionModel = $presentacionModel;
        $this->render = $render;
    }

    public function execute()
    {
        $data["presentaciones"] = $this->presentacionModel->getPresentaciones();
        echo $this->render->render("view/tourView.php", $data);
    }
}