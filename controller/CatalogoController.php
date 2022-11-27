<?php

class CatalogoController
{

    private $productoModel;
    private $edicionModel;
    private $session;
    private $render;

    public function __construct($productoModel, $edicionModel, $session, $render)
    {
        $this->productoModel = $productoModel;
        $this->edicionModel = $edicionModel;
        $this->session = $session;
        $this->render = $render;
    }

    public function execute()
    {

        $data = $this->datos(["productos" => $this->productoModel->list()]);
        echo $this->render->render("public/view/catalog/catalogo.mustache", $data);

    }


    public function list()
    {
        $idProducto = $_GET['p'] ?? 0;
        $searchValue = $_GET['v'] ?? '';

        $data = $this->datos(["producto" => $this->productModel->getProduct($idProducto),
            "ediciones" => $this->edicionModel->listCatalogBy($idProducto, $this->session)]);

        echo $this->render->render("public/view/catalog/catalogo-list.mustache",$data);
    }

    private function datos($msg = [])
    {
        return array_merge($msg, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }

}