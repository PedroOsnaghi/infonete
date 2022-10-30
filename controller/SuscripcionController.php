<?php

class SuscripcionController
{
    private $suscripcionModel;
    private $render;

    public function __construct($suscripcionModel, $render)
    {
        $this->suscripcionModel = $suscripcionModel;
        $this->render = $render;
    }

    public function execute()
    {

    }

    public function crear()
    {
        echo $this->render->render("public/view/suscripcion.mustache");
    }

    public function guardar()
    {
        $this->setSuscripcionValidada();

        ($this->suscripcionModel->crear()) ?
            $data['success'] = "La suscripción se guardó correctamente" :
            $data['error'] = "Hubo un error al guardar la suscripción";

        echo $this->render->render("public/view/suscripcion.mustache", $data);
    }

    private function setSuscripcionValidada()
    {
        $this->suscripcionModel->setDescripcion($_POST['descripcion']);
        $this->suscripcionModel->setDuracion($_POST['duracion']);
        $this->suscripcionModel->setPrecio($_POST['precio']);
    }
}