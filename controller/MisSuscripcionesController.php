<?php

class MisSuscripcionesController
{

    private $suscripcionModel;
    private $session;
    private $render;

    public function __construct($suscripcionModel, $session, $render)
    {
        //setea modelo
        $this->suscripcionModel = $suscripcionModel;

        $this->session = $session;
        $this->render = $render;
    }

    /**
     * Meteodo inicial que lanza la Vista de 'Mis Suscripciones'.
     * Restriccion: usuario logueado
     *
     * @return Html
     */
    public function execute()
    {
        //restriccion de metodo
        $this->session->urlRestriction();

        $data = $this->datos(['suscripciones' => $this->suscripcionModel->listarSuscripcionesUsuario($this->session->getAuthUser()->getId())]);

        echo $this->render->render("public/view/mis-suscripciones.mustache", $data);
    }

    /**
     * Meteodo que solicita la cancelación de la suscripcion.
     * Restriccion: usuario logueado
     *
     * @return Html
     */
    public function cancelar()
    {
        //restriccion de metodo
        $this->session->urlRestriction();

        $id_suscripcion = $_GET['s'] ?? null;

        $id_producto = $_GET['p'] ?? null;

        if ($id_suscripcion && $id_producto) {
            $this->suscripcionModel->cancelarSuscripcion($id_suscripcion, $id_producto, $this->session->getAuthUser()->getId());
            $this->execute();
        }
    }

    /**
     * Meteodo qur genera el array de Datos que sera enviado a la Vista.
     *
     * @return array
     */
    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }

}