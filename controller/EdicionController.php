<?php

class EdicionController
{

    private $edicionModel;
    private $render;
    private $productModel;
    private $session;


    public function __construct($edicionModel, $productModel, $session, $render)
    {
        $this->edicionModel  = $edicionModel;
        $this->productModel = $productModel;
        $this->session = $session;

        $this->render = $render;
    }

    public function execute()
    {

    }

    public function crear(){
        $data = $this->datos();
        echo $this->render->render("public/view/edicion.mustache", $data);
    }

    public function guardar(){
        $this->setEdition();

        $data = ($this->edicionModel->guardar()) ?
                $this->datos(['success' => "La edición se guardó correctamente"]) :
                $this->datos(['error' => "Hubo un error al guardar la edición"]);

        echo $this->render->render("public/view/edicion.mustache",$data);
    }

    public function admin()
    {
        $data = $this->datos(["product" =>  $this->session->getParameter("activeProduct"),
                              "productos" => $this->productModel->list()]);
        echo $this->render->render("public/view/gestion-edicion.mustache", $data);
    }

    public function list()
    {
        $idProducto = $_GET['idp'];
        $this->session->setParameter('activeProduct', $this->productModel->getProduct($idProducto));
        $data = $this->datos(["ediciones" => $this->edicionModel->listBy($idProducto)]);
        echo $this->render->render("public/view/partial/lista-edicion.mustache", $data);
    }

    public function editar()
    {
        $data = $this->datos(["edicion" =>  $this->edicionModel->getEdition($_GET['id'])]);
        echo $this->render->render("public/view/editar-edicion.mustache", $data);
    }

    public function actualizar()
    {
        $this->setEdition($_POST['id']);

        $data = $this->datos($this->edicionModel->update());

        echo $this->render->render("public/view/editar-edicion.mustache",$data);
    }

    public function publicar()
    {
        $id = $_GET['id'];
        $res = $this->edicionModel->publicar($id);
        header('Content-Type: application/json');
        echo json_encode($res,JSON_FORCE_OBJECT);
    }

    public function despublicar()
    {
        $id = $_GET['id'];
        $res = $this->edicionModel->despublicar($id);
        header('Content-Type: application/json');
        echo json_encode($res,JSON_FORCE_OBJECT);
    }




    private function setEdition($id = null)
    {
        if($id != null) $this->edicionModel->setId($id);
        $this->edicionModel->setNumero($_POST['numero']);
        $this->edicionModel->setTitulo($_POST['titulo']);
        $this->edicionModel->setDescripcion($_POST['descripcion']);
        $this->edicionModel->setPrecio($_POST['precio']);
        $this->edicionModel->setProducto($this->session->getParameter('activeProduct')->getId());
        if(isset($_POST['portada'])) $this->edicionModel->setPortada($_POST['portada']);

    }



    private function datos($data = [])
    {
        return array_merge($data, array(
            "product" =>  $this->session->getParameter("activeProduct"),
            "userAuth" => $this->session->getAuthUser()
        ));
    }






}