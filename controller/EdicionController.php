<?php

class EdicionController
{

    private $edicionModel;
    private $render;
    private $productModel;
    private $session;
    private $file;

    public function __construct($edicionModel, $productModel, $session, $file, $render)
    {
        $this->edicionModel  = $edicionModel;
        $this->productModel = $productModel;
        $this->session = $session;
        $this->file = $file;
        $this->render = $render;
    }

    public function execute()
    {

    }

    public function crear(){

        $this->session->setParameter('activeProduct', $this->productModel->getProduct($_GET["idp"]));
        $data = $this->getData();
        echo $this->render->render("public/view/edicion.mustache", $data);
    }

    public function guardar(){
        $this->setEdicionValidada();

        $data = ($this->edicionModel->guardar()) ?
                $this->getData(['success' => "La edición se guardó correctamente"]) :
                $this->getData(['error' => "Hubo un error al guardar la edición"]);

        echo $this->render->render("public/view/edicion.mustache",$data);
    }

    public function admin()
    {
        $data = $this->getDataList();
        echo $this->render->render("public/view/gestion-edicion.mustache", $data);
    }

    public function list()
    {
        $idProducto = $_GET['idp'];
        $data["ediciones"] = $this->edicionModel->listBy($idProducto);
        echo $this->render->render("public/view/partial/lista-edicion.mustache", $data);
    }

    public function editar()
    {
        $data = $this->getDataEdicion($_GET['id']);
        echo $this->render->render("public/view/editar-edicion.mustache", $data);
    }

    public function actualizar()
    {
        $id = $_POST['id'];
        $this->setEdicionValidada($id);

        $data = ($this->edicionModel->update()) ?
            $this->getDataEdicion($id, ['success' => "La edición se actualizó correctamente"]) :
            $this->getDataEdicion($id, ['error' => "Hubo un error al actualizar la edición"]);

        echo $this->render->render("public/view/edicion.mustache",$data);
    }



    private function setEdicionValidada($id = null)
    {
        if($id != null) $this->edicionModel->setId($id);
        $this->edicionModel->setNumero($_POST['numero']);
        $this->edicionModel->setTitulo($_POST['titulo']);
        $this->edicionModel->setDescripcion($_POST['descripcion']);
        $this->edicionModel->setPrecio($_POST['precio']);
        $this->edicionModel->setProducto($this->session->getParameter('activeProduct')->getId());
        $this->edicionModel->setPortada($this->getFileName());
    }

    private function getDataList()
    {
        return array(
            "productoActivo" => $this->session->getParameter('activeProduct'),
            "productos" => $this->productModel->list(),
            "userAuth" => $this->session->getAuthUser()
        );
    }

    private function getData($msg = [])
    {
        return array_merge($msg, array(
            "product" =>  $this->session->getParameter("activeProduct"),
            "userAuth" => $this->session->getAuthUser()
        ));
    }

    private function getDataEdicion($id, $msg = [])
    {
        return array_merge($msg, array(
            "edicion" => $this->edicionModel->getEdition($id),
            "product" =>  $this->session->getParameter("activeProduct"),
            "userAuth" => $this->session->getAuthUser()
        ));
    }

    private function getFileName()
    {
        return ($this->file->uploadFile("portada"))?
                $this->file->get_file_uploaded():
                "default.jpg";
    }


}