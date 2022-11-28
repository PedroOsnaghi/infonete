<?php

class MisProductosController
{
    private $misProductosModel;
    private $session;
    private $render;


    public function __construct($misProductosModel, $session, $render)
    {
        //setea modelos
        $this->misProductosModel = $misProductosModel;

        $this->session = $session;
        $this->render = $render;
    }

    /**
     * Meteodo inicial que lanza la vista de Mis Productos.
     *
     * @return Html
     */
    public function execute()
    {
        //restriccion de metodo
        $this->session->urlRestriction();

        $data = $this->datos(['ediciones' => $this->misProductosModel->listarCompras($this->session->getAuthUser()->getId()),
                             'suscriptos' => $this->misProductosModel->listarEdicionesSuscriptas($this->session->getAuthUser()->getId())]);

        echo $this->render->render('public/view/mis-productos.mustache', $data);
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