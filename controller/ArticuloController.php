<?php

class ArticuloController
{
    //PROPIEDADES
    private $articuloModel;
    private $render;

    public function __construct($articuloModel, $render)
    {
        $this->articuloModel = $articuloModel;
        $this->render = $render;
    }

    public function execute()
    {

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

}