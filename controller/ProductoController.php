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
        $data = $this->getDataTipo();
        echo $this->render->render("public/view/producto.mustache", $data);
    }

    public function search()
    {
        $value = $_GET['value'];
        $data = $this->getProductSearchList($value);
        echo $this->render->render("public/view/gestion-producto.mustache", $data);
    }

    public function admin()
    {
        $data = $this->getData();
        echo $this->render->render("public/view/gestion-producto.mustache", $data);
    }

    public function guardar()
    {
        $this->setearProducto();

       $data = ($this->productoModel->guardar()) ?
             $this->getDataTipo(["success" => "El producto se guardó correctamente"]):
             $this->getDataTipo(["error" => "Hubo un error al guardar el producto"]);

        echo $this->render->render("public/view/producto.mustache", $data);
    }

    private function setearProducto()
    {
        $this->productoModel->setNombre($_POST['nombre']);
        $this->productoModel->setTipo($this->valida($_POST['tipo']));
        $this->productoModel->setImagen($this->getFileName());
    }

    private function getFileName()
    {
        return ($this->file->uploadFile("product")) ?
            $this->file->get_file_uploaded() :
            'default.jpg';
    }

    private function getData($msg = [])
    {
        return array_merge($msg, array(
            "productos" => $this->productoModel->list(),
            "userAuth" => $this->session->getAuthUser()
        ));
    }

    private function getDataTipo($msg = [])
    {
        return array_merge($msg, array(
            "tipo" => $this->productoModel->getTipoProductList(),
            "userAuth" => $this->session->getAuthUser(),
            $msg
        ));
    }

    private function valida($value)
    {
        if (isset($value))
            return $value;
        $this->sendError();
    }

    private function sendError()
    {
        $data = $this->getDataTipo(["error" => "Debe seleccionar un tipo"]);
        echo $this->render->render("public/view/producto.mustache", $data);
    }

    private function getProductSearchList($value)
    {
        return array(
            "productos" => $this->productoModel->searchList($value),
            "userAuth" => $this->session->getAuthUser()
        );
    }




}