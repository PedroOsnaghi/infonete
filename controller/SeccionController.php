<?php

class SeccionController
{
    private $seccionModel;
    private $render;

    public function __construct($seccionModel, $render)
    {
        $this->seccionModel = $seccionModel;
        $this->render = $render;
    }

    public function execute()
    {

    }

    public function crear()
    {
        echo $this->render->render("public/view/seccion.mustache");
    }

    public function guardar()
    {
        $this->setSeccionValidada();

        ($this->seccionModel->guardar()) ?
            $data['success'] = "La seccion se guardó correctamente" :
            $data['error'] = "Hubo un error al guardar la seccion";

        echo $this->render->render("public/view/seccion.mustache", $data);
    }

    private function setSeccionValidada()
    {
        $this->seccionModel->setNombre($_POST['nombre']);
        $this->seccionModel->setDescripcion($_POST['descripcion']);
    }

}