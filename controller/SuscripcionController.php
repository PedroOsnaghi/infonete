<?php

class SuscripcionController
{
    private $suscripcionModel;
    private $render;
    private $session;

    public function __construct($suscripcionModel, $session, $render)
    {
        $this->suscripcionModel = $suscripcionModel;
        $this->session = $session;
        $this->render = $render;
    }

    public function execute()
    {

    }

    public function admin()
    {
        $data = $this->datos(["planes" => $this->suscripcionModel->list()]);
        echo $this->render->render("public/view/gestion-suscripcion.mustache", $data);
    }

    public function crear()
    {
        $data = $this->datos(["tipos" => $this->suscripcionModel->listTipos()]);
        echo $this->render->render("public/view/suscripcion.mustache", $data);
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