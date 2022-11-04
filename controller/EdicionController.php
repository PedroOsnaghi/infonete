<?php

class EdicionController
{

    private $edicionModel;
    private $render;

    public function __construct($edicionModel, $render)
    {
        $this->edicionModel  = $edicionModel;
        $this->render = $render;
    }

    public function execute()
    {

    }

    public function crear(){
        echo $this->render->render("public/view/edicion.mustache");
    }

    public function guardar(){
        $this->setEdicionValidada();

        ($this->edicionModel->guardar()) ?
            $data['success'] = "La edición se guardó correctamente" :
            $data['error'] = "Hubo un error al guardar la edición";

        echo $this->render->render("public/view/edicion.mustache",$data);
    }

    private function setEdicionValidada()
    {
        $this->edicionModel->setNumero($_POST['numero']);
        $this->edicionModel->setPrecio($_POST['precio']);
        $this->edicionModel->setFecha($_POST['fecha']);
        $this->edicionModel->setProducto($_POST['producto']);
    }


}