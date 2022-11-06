<?php

class ProductoController
{
    //PROPIEDADES
    private $productoModel;
    private $file;
    private $render;
    private $session;


    public function __construct($productoModel, $file, $session, $render)
    {
        $this->productoModel = $productoModel;
        $this->file = $file;
        $this->session = $session;
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

    public function admin()
    {
        $data = $this->getData();
        echo $this->render->render("public/view/gestion-producto.mustache", $data);
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
        return ($this->file->uploadFile("portada")) ?
            $this->file->get_file_uploaded() :
            'default.png';
    }

    private function getData()
    {
        return array(
            "productos" => $this->productoModel->list(),
            "userAuth" => $this->session->getAuthUser()
        );
    }




}