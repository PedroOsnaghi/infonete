<?php

class ProductoController
{
    //PROPIEDADES
    private $productoModel;
    private $render;
    private $session;


    public function __construct($productoModel, $session, $render)
    {
        $this->productoModel = $productoModel;
        $this->session = $session;
        $this->render = $render;
    }

    public function execute()
    {
    }

    //muestra el formulario
    public function agregar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);
        $data = $this->datos(["tipo" => $this->productoModel->getTipoProductList()]);
        echo $this->render->render("public/view/producto.mustache", $data);
    }

    public function search()
    {
        $value = $_GET['value'];
        $data = $this->datos(["productos" => $this->productoModel->search($value)]);
        echo $this->render->render("public/view/gestion-producto.mustache", $data);
    }

    public function admin()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);
        $data = $this->datos([ "productos" => $this->productoModel->list()]);
        echo $this->render->render("public/view/gestion-producto.mustache", $data);
    }

    public function guardar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $this->setearProducto();

        $data = $this->datos([$this->productoModel->guardar(),
                           "tipo" => $this->productoModel->getTipoProductList()]);


        echo $this->render->render("public/view/producto.mustache", $data);
    }

    public function editar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $data = $this->getDataEditar($_GET['id']);
        echo $this->render->render("public/view/editar-producto.mustache",$data);
    }

    public function actualizar()
    {
        $id = $_POST['id'];
        $this->setearProducto($id);
        $data = $this->datos([$this->productoModel->update(),
                                "producto" => $this->productoModel->getProduct($id),
                                    "tipo" => $this->productoModel->getTipoProductList()]);

        echo $this->render->render("public/view/editar-producto.mustache", $data);

    }

    public function nuestrosProductos()
    {
        $data = $this->datos(["productos" => $this->productoModel->list()]);
        echo $this->render->render("public/view/catalog/catalogo.mustache", $data);
    }

    private function setearProducto($id = null)
    {
        if($id != null) $this->productoModel->setId($id);
        $this->productoModel->setNombre($_POST['nombre']);
        $this->productoModel->setDescripcion($_POST['descripcion']);
        $this->productoModel->setTipo($this->valida($_POST['tipo']));
        if (isset($_POST['imagen'])) $this->productoModel->setImagen($_POST['imagen']);
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



    private function datos($msg = [])
    {
        return array_merge($msg, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }




}