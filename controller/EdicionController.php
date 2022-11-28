<?php

class EdicionController
{
    //modelos
    private $edicionModel;
    private $productModel;
    //helper
    private $checkout;

    private $session;
    private $render;


    public function __construct($edicionModel, $productModel, $checkout, $session, $render)
    {
        //setea modelos
        $this->edicionModel = $edicionModel;
        $this->productModel = $productModel;

        $this->checkout = $checkout;
        $this->session = $session;
        $this->render = $render;
    }

    /**
     * Metodo que Lanza la vista de Nueva Edicion
     * Restricción: Rol Editor
     *
     * @return Html
     */
    public function crear()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);
        $data = $this->datos();
        echo $this->render->render("public/view/edicion.mustache", $data);
    }

    /**
     * Metodo que solicita el guardado de datos
     * Restricción: Rol Editor
     *
     * @return Html con el resultado de la operación
     */
    public function guardar()
    {
        //restricción de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $this->setearEdicion();

        $data = $this->datos($this->edicionModel->guardar());

        echo $this->render->render("public/view/edicion.mustache", $data);
    }

    /**
     * Metodo que lanza la vista de administración de Ediciones
     * Restricción: Rol Editor
     *
     * @return Html
     */
    public function admin()
    {
        //restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $data = $this->datos(["selected" => $this->session->getParameter("activeProduct"),
                             "productos" => $this->productModel->list()]);
        echo $this->render->render("public/view/gestion-edicion.mustache", $data);
    }

    /**
     * Metodo que solicita el listado de Ediciones
     * Restricción: Rol Editor
     *
     * @return Html Parcial
     */
    public function list()
    {
        //restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $idProducto = $_GET['idp'];

        //guarda un objeto producto seleccionado en la sesion
        $this->session->setParameter('activeProduct', $this->productModel->getProduct($idProducto));

        $data = $this->datos(["ediciones" => $this->edicionModel->listarPorProducto($idProducto)]);

        echo $this->render->render("public/view/partial/lista-edicion.mustache", $data);
    }

    /**
     * Metodo que lanza la vista de Editar una Edicion
     * Restricción: Rol Editor
     *
     * @return Html con el resultado de la operación
     */
    public function editar()
    {
        //restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $data = $this->datos(["edicion" => $this->edicionModel->getEdition($_GET['id'])]);

        echo $this->render->render("public/view/editar-edicion.mustache", $data);
    }

    /**
     * Metodo que solicita la Actualización de datos
     * Restricción: Rol Editor
     *
     * @return Html con el resultado de la operación
     */
    public function actualizar()
    {
        //restriccion del metodo
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $this->setearEdicion();

        $data = $this->datos($this->edicionModel->update());

        echo $this->render->render("public/view/editar-edicion.mustache", $data);
    }

    /**
     * Metodo que cambia el estado de la Edicion a 'PUBLICADA'
     * Restricción: Rol Editor
     *
     * @return JSON con el resultado de la operación
     */
    public function publicar()
    {
        $id = $_GET['id'];
        $res = $this->edicionModel->publicar($id);
        header('Content-Type: application/json');
        echo json_encode($res, JSON_FORCE_OBJECT);
    }

    /**
     * Metodo que cambia el estado de la Edicion a 'EN EDICION'
     * Restricción: Rol Editor
     *
     * @return JSON con el resultado de la operación
     */
    public function despublicar()
    {
        $id = $_GET['id'];
        $res = $this->edicionModel->despublicar($id);
        header('Content-Type: application/json');
        echo json_encode($res, JSON_FORCE_OBJECT);
    }

    /**
     * Metodo que setea el Checkout de Edicion y lo lanza
     * Restricción: Rol Editor
     *
     * @return void
     */
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

    /**
     * Metodo Private que setea el objeto Edicion
     *
     *
     * @return void
     */
    private function setearEdicion()
    {
        $this->edicionModel->setId($_POST['id'] ?? null);
        $this->edicionModel->setNumero($_POST['numero']);
        $this->edicionModel->setTitulo($_POST['titulo']);
        $this->edicionModel->setDescripcion($_POST['descripcion']);
        $this->edicionModel->setPrecio($_POST['precio']);
        $this->edicionModel->setProducto($this->session->getParameter('activeProduct')->getId());
        $this->edicionModel->setPortada($_POST['portada'] ?? null);

    }

    /**
     * Meteodo qur genera el array de Datos que sera enviado a la Vista.
     *
     * @return array
     */
    private function datos($data = [])
    {
        return array_merge($data, array(
            "product" => $this->session->getParameter("activeProduct"),
            "userAuth" => $this->session->getAuthUser()
        ));
    }


}