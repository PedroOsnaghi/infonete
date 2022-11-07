<?php

class ArticuloController
{
    //PROPIEDADES
    private $articuloModel;
    private $render;
    private $edicionModel;
    private $session;

    public function __construct($articuloModel, $edicionModel, $session, $render)
    {
        $this->articuloModel = $articuloModel;
        $this->edicionModel = $edicionModel;
        $this->session = $session;
        $this->render = $render;

    }

    public function execute()
    {

    }

    public function admin(){

        $data = $this->getData();
        echo $this->render->render("public/view/gestion-noticias.mustache", $data);
    }

    public function list()
    {
        $idEdicion = $_GET['ide'];
        $data["noticias"] = $this->articuloModel->listBy($idEdicion);
        echo $this->render->render("public/view/partial/lista-notas.mustache", $data);
    }

    public function crear()
    {
        echo $this->render->render("public/view/articulo.mustache");
    }

    public function guardar()
    {
        $this->setearArticulo();

        ($this->articuloModel->guardar()) ?
            $data['success'] = "El articulo se guardó correctamente" :
            $data['error'] = "Hubo un error al guardar el articulo";

        echo $this->render->render("public/view/articulo.mustache", $data);
    }

    private function setearArticulo()
    {
        $this->articuloModel->setTitulo($_POST['titulo']);
        $this->articuloModel->setSubtitulo($_POST['subtitulo']);
        $this->articuloModel->setContenido($_POST['contenido']);
        $this->articuloModel->setLink($_POST['link']);
        $this->articuloModel->setLinkvideo($_POST['linkvideo']);
        $this->articuloModel->setCreateAt($this->getFechaHoraActual());
        $this->articuloModel->setUpdateAt($this->getFechaHoraActual());
        $this->articuloModel->setEstado(1);
    }

    private function getFechaHoraActual()
    {
        $datetime = new DateTime();
        $datetime->setTimezone(new DateTimeZone('America/Argentina/Buenos_Aires'));
        return $datetime->format("y-m-d H:i:s");
    }

    private function getData()
    {
        return array(
            "userAuth" => $this->session->getAuthUser(),
            "ediciones" => $this->edicionModel->listByState(EdicionModel::ESTADO_EN_EDICION)
        );
    }

}