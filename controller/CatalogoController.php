<?php

class CatalogoController
{

    private $productModel;
    private $edicionModel;
    private $session;
    private $render;

    public function __construct($productModel, $edicionModel, $session, $render)
    {
        $this->productModel = $productModel;
        $this->edicionModel = $edicionModel;
        $this->session = $session;
        $this->render = $render;
    }

    public function execute()
    {

        $data = $this->datos(["productos" => $this->productModel->list()]);
        echo $this->render->render("public/view/catalog/catalogo.mustache", $data);

    }


    public function list()
    {
        $idProducto = $_GET['p'] ?? 0;
        $searchValue = $_GET['value'] ?? '';
        $idUsuario = ($this->session->getAuthUser()) ? $this->session->getAuthUser()->getId() : null;

        $data = $this->datos(["producto" => $this->productModel->getProduct($idProducto),
            "ediciones" => $this->edicionModel->listByProduct($idProducto, $idUsuario, $searchValue),
            "search" => $searchValue]);

        echo $this->render->render("public/view/catalog/catalogo-list.mustache",$data);
    }

    private function datos($msg = [])
    {
        return array_merge($msg, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }

}