<?php

class HomeController
{

    private $render;
    private $session;
    private $homeModel;
    private $config;

    public function __construct($config, $homeModel, $session, $render)
    {
        $this->config = $config;
        $this->homeModel = $homeModel;
        $this->session = $session;
        $this->render = $render;
    }

    /**
     * Meteodo inicial que lanza la vista 'Home'.
     *Restricción: publico
     *
     * @return Html
     */
    public function execute()
    {

        $idUsuario = ($this->session->getAuthUser()) ? $this->session->getAuthUser()->getId() : null;

        $data = $this->datos(['novedades'=>$this->homeModel->getNovedades($idUsuario),
                              'wather_key' => $this->config['wather_key'] ]);

        echo $this->render->render("public/view/home.mustache", $data);
    }

    /**
     * Meteodo que solicita el Cierre de Sesion de usuario.
     *
     * @return void Redirecciona al Home
     */
    public function logOut()
    {
        $this->session->closeSession();
        Redirect::doIt("/infonete");
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