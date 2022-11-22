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
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);
        $data = $this->getData();
        echo $this->render->render("public/view/gestion-producto.mustache", $data);
    }

    public function guardar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $this->setearProducto();

       $data = ($this->productoModel->guardar()) ?
             $this->getDataTipo(["success" => "El producto se guardó correctamente"]):
             $this->getDataTipo(["error" => "Hubo un error al guardar el producto"]);

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
        $data = ($this->productoModel->update()) ?
            $this->getDataEditar($id, ["success" => "El producto se actualizó correctamente"]):
            $this->getDataEditar($id, ["error" => "Hubo un error al actualizar el producto"]);

        echo $this->render->render("public/view/editar-producto.mustache", $data);

    }

    private function setearProducto($id = null)
    {
        if($id != null) $this->productoModel->setId($id);
        $this->productoModel->setNombre($_POST['nombre']);
        $this->productoModel->setTipo($this->valida($_POST['tipo']));
        if (isset($_POST['imagen'])) $this->productoModel->setImagen($_POST['imagen']);
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

    private function getDataEditar($id, $msg = [])
    {
        return array_merge($msg, array(
            "producto" => $this->productoModel->getProduct($id),
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