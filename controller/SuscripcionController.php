<?php

class SuscripcionController
{
    private $suscripcionModel;
    private $productoModel;
    private $render;
    private $session;

    public function __construct($suscripcionModel, $productoModel, $session, $render)
    {
        $this->suscripcionModel = $suscripcionModel;
        $this->productoModel = $productoModel;
        $this->session = $session;
        $this->render = $render;
    }

    public function execute()
    {

    }

    public function admin()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $data = $this->datos(["planes" => $this->suscripcionModel->list()]);
        echo $this->render->render("public/view/gestion-suscripcion.mustache", $data);
    }

    public function crear()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);
        $data = $this->datos(["tipos" => $this->suscripcionModel->listTipos()]);
        echo $this->render->render("public/view/suscripcion.mustache", $data);
    }

    public function suscribirse()
    {
        $this->session->urlRestriction();

        $idSuscripcion = $_GET['s'];
        $data = $this->datos(["suscripcion" => $this->suscripcionModel->getSuscripcion($idSuscripcion),
                             "productos" => $this->productoModel->list()]);
        echo $this->render->render("public/view/suscribirse.mustache", $data);
    }

    public function guardar()
    {
        $this->setSuscripcion();

        $data = $this->datos($this->suscripcionModel->guardar());

        echo $this->render->render("public/view/suscripcion.mustache", $data);
    }

    public function planes(){

        $data = $this->datos(["planes" => $this->suscripcionModel->list()]);

        echo $this->render->render("public/view/planes.mustache", $data);
    }

    private function setSuscripcion()
    {
        $this->suscripcionModel->setDescripcion($_POST['descripcion']);
        $this->suscripcionModel->setDuracion($_POST['duracion']);
        $this->suscripcionModel->setPrecio($_POST['precio']);
        $this->suscripcionModel->setTag($_POST['tag']);
    }

    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }


}