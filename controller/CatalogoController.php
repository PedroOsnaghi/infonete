<?php

class CatalogoController
{

    private $productModel;
    private $edicionModel;
    private $session;
    private $render;

    public function __construct($productModel, $edicionModel, $session, $render)
    {
        //setea modelos
        $this->productModel = $productModel;
        $this->edicionModel = $edicionModel;

        //setea sesion y render
        $this->session = $session;
        $this->render = $render;
    }

    /**
     * Metodo inicial- Lanza vista principal del catalogo.
     *
     * @return Html
     */
    public function execute()
    {
        $data = $this->datos(["productos" => $this->productModel->list()]);
        echo $this->render->render("public/view/catalog/catalogo.mustache", $data);
    }

    /**
     * Metodo que Lanza la vista con el listado de Ediciones según el producto seleccionado.
     *
     * @return Html
     */
    public function list()
    {
        $idProducto = $_GET['p'] ?? 0;

        //valor de busqueda
        $searchValue = $_GET['value'] ?? '';

        $idUsuario = ($this->session->getAuthUser()) ? $this->session->getAuthUser()->getId() : null;

        $data = $this->datos(["producto" => $this->productModel->getProduct($idProducto),
                             "ediciones" => $this->edicionModel->listByProduct($idProducto, $idUsuario, $searchValue),
                                "search" => $searchValue]);

        echo $this->render->render("public/view/catalog/catalogo-list.mustache",$data);
    }

    /**
     * Meteodo qur genera el array de Datos que sera enviado a la Vista.
     *
     * @return array
     */
    private function datos($msg = [])
    {
        return array_merge($msg, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }

}