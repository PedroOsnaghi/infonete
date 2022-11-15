<?php

class ArticuloController
{
    //PROPIEDADES
    private $articuloModel;
    private $render;
    private $edicionModel;
    private $session;
    private $seccionModel;

    private $logger;
    private $usuarioModel;

    public function __construct($articuloModel, $edicionModel, $seccionModel, $usuarioModel,  $session, $logger, $render)
    {
        $this->articuloModel = $articuloModel;
        $this->edicionModel = $edicionModel;
        $this->seccionModel = $seccionModel;
        $this->usuarioModel = $usuarioModel;
        $this->session = $session;
        $this->logger = $logger;
        $this->render = $render;

    }

    public function execute()
    {

    }

    public function admin(){

        $data = $this->dato(["ediciones" => $this->edicionModel->listByState(EdicionModel::ESTADO_EN_EDICION)]);
        echo $this->render->render("public/view/gestion-noticias.mustache", $data);
    }

    public function list()
    {
        $idEdicion = $_GET['ide'];
        $this->session->setParameter('activeEdition', $this->edicionModel->getEdition($idEdicion));
        $data = $this->dato(["noticias" => $this->articuloModel->listBy($idEdicion)]);
        echo $this->render->render("public/view/partial/lista-notas.mustache", $data);
    }

    public function crear()
    {

        $data = $this->dato(["secciones" => $this->seccionModel->list()]);
        echo $this->render->render("public/view/articulo.mustache", $data);


    }

    public function preview()
    {
        $data = $this->dato(["articulo" => $this->articuloModel->getArticlePreview($_GET['id'])]);
        echo $this->render->render("public/view/articulo-preview.mustache", $data);
    }

    public function guardar()
    {
        $this->setearArticulo();

        $response = $this->articuloModel->guardar();

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);

    }

    public function revision()
    {
        $response = $this->articuloModel->solicitarRevision($_GET['id']);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    private function setearArticulo()
    {
        $this->articuloModel->setTitulo($_POST['titulo']);
        $this->articuloModel->setSubtitulo($_POST['subtitulo']);
        $this->articuloModel->setContenido($_POST['contenido']);
        $this->articuloModel->setLink($_POST['link']);
        $this->articuloModel->setLinkvideo($_POST['linkvideo']);
        $this->articuloModel->setUbicacion($_POST['ubicacion']);
        $this->articuloModel->setCreateAt($this->getFechaHoraActual());
        $this->articuloModel->setUpdateAt($this->getFechaHoraActual());
        $this->articuloModel->setEstado(0);
        $this->articuloModel->setAutor($this->session->getAuthUser()->getId());
        $this->articuloModel->setSeccion($_POST['seccion']);
        $this->articuloModel->setEdicion($this->session->getParameter('activeEdition')->getId());

    }

    private function getFechaHoraActual()
    {
        $datetime = new DateTime();
        $datetime->setTimezone(new DateTimeZone('America/Argentina/Buenos_Aires'));
        return $datetime->format("y-m-d H:i:s");
    }

    private function dato($data = [])
    {
        return array_merge($data,  array(
            "userAuth" => $this->session->getAuthUser(),
            "edicion" => $this->session->getParameter('activeEdition')
        ));
    }






}