<?php

class EdicionController
{

    private $edicionModel;
    private $render;
    private $productModel;
    private $session;
    private $checkout;


    public function __construct($edicionModel, $productModel, $checkout, $session, $render)
    {
        $this->edicionModel = $edicionModel;
        $this->productModel = $productModel;
        $this->checkout = $checkout;
        $this->session = $session;
        $this->render = $render;
    }


    public function crear()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);
        $data = $this->datos();
        echo $this->render->render("public/view/edicion.mustache", $data);
    }

    public function guardar()
    {

        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $this->setEdition();

        $data = ($this->edicionModel->guardar()) ?
            $this->datos(['success' => "La edición se guardó correctamente"]) :
            $this->datos(['error' => "Hubo un error al guardar la edición"]);

        echo $this->render->render("public/view/edicion.mustache", $data);
    }

    public function admin()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $data = $this->datos(["product" => $this->session->getParameter("activeProduct"),
            "productos" => $this->productModel->list()]);
        echo $this->render->render("public/view/gestion-edicion.mustache", $data);
    }

    public function list()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $idProducto = $_GET['idp'];
        $this->session->setParameter('activeProduct', $this->productModel->getProduct($idProducto));
        $data = $this->datos(["ediciones" => $this->edicionModel->listBy($idProducto)]);
        echo $this->render->render("public/view/partial/lista-edicion.mustache", $data);
    }

    public function editar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $data = $this->datos(["edicion" => $this->edicionModel->getEdition($_GET['id'])]);
        echo $this->render->render("public/view/editar-edicion.mustache", $data);
    }

    public function actualizar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $this->setEdition($_POST['id']);

        $data = $this->datos($this->edicionModel->update());

        echo $this->render->render("public/view/editar-edicion.mustache", $data);
    }

    public function publicar()
    {
        $id = $_GET['id'];
        $res = $this->edicionModel->publicar($id);
        header('Content-Type: application/json');
        echo json_encode($res, JSON_FORCE_OBJECT);
    }

    public function despublicar()
    {
        $id = $_GET['id'];
        $res = $this->edicionModel->despublicar($id);
        header('Content-Type: application/json');
        echo json_encode($res, JSON_FORCE_OBJECT);
    }

    public function checkout()
    {
        $this->session->urlRestriction();

        $edicion = $this->edicionModel->getEdition($_GET['id']);

        //configuracion del Checkout
        $this->checkout->target = CheckoutModel::TARGET_EDITION;
        $this->checkout->cantidad = 1;
        $this->checkout->concepto = "Edicion Nº ".$edicion->getNumero()." - ".$edicion->getTipoProducto()." ".$edicion->getNombreProducto();
        $this->checkout->precio = $edicion->getPrecio();
        $this->checkout->data = array("edicion" => $edicion);

        $this->checkout->show();
    }



    public function misEdiciones()
    {
        $this->session->urlRestriction();
        $data = $this->datos(['ediciones' => $this->edicionModel->listarCompras($this->session->getAuthUser()->getId())]);
        echo $this->render->render('public/view/mis-productos.mustache', $data);
    }

    public function catalog()
    {
        $idProducto = $_GET['p'];

        $data = $this->datos(["producto" => $this->productModel->getProduct($idProducto),
                                "ediciones" => $this->edicionModel->listCatalogBy($idProducto, $this->session)]);

        echo $this->render->render("public/view/catalog/catalogo-list.mustache",$data);
    }



    private function setEdition($id = null)
    {
        if ($id != null) $this->edicionModel->setId($id);
        $this->edicionModel->setNumero($_POST['numero']);
        $this->edicionModel->setTitulo($_POST['titulo']);
        $this->edicionModel->setDescripcion($_POST['descripcion']);
        $this->edicionModel->setPrecio($_POST['precio']);
        $this->edicionModel->setProducto($this->session->getParameter('activeProduct')->getId());
        if (isset($_POST['portada'])) $this->edicionModel->setPortada($_POST['portada']);

    }


    private function datos($data = [])
    {
        return array_merge($data, array(
            "product" => $this->session->getParameter("activeProduct"),
            "userAuth" => $this->session->getAuthUser()
        ));
    }


}