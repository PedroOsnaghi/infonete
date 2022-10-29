<?php

class ProductoController
{
    //PROPIEDADES
    private $productoModel;
    private $file;
    private $render;


    public function __construct($productoModel, $file, $render)
    {
        $this->productoModel = $productoModel;
        $this->file = $file;
        $this->render = $render;
    }

    public function execute()
    {
    }

    //muestra el formulario
    public function agregar()
    {
        echo $this->render->render("public/view/producto.mustache");
    }

    public function guardar()
    {
        $this->setearProducto();

        ($this->productoModel->guardar()) ?
            $data['success'] = "El producto se guardó correctamente" :
            $data['error'] = "Hubo un error al guardar el producto";

        echo $this->render->render("public/view/producto.mustache", $data);
    }

    private function setearProducto()
    {
        $this->productoModel->setNombre($_POST['nombre']);
        $this->productoModel->setTipo($_POST['tipo']);
        $this->productoModel->setPortada($this->getFileName());
    }

    private function getFileName()
    {
        return ($this->file->uploadFile("portadas")) ?
            $this->file->get_file_uploaded() :
            'default.png';
    }


}