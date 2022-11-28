<?php

class IndexController
{

    private $render;
    private $session;
    private $edicionModel;
    private $config;

    public function __construct($config, $edicionModel, $session, $render)
    {
        $this->config = $config;
        $this->edicionModel = $edicionModel;
        $this->session = $session;
        $this->render = $render;
    }

    public function execute()
    {
        $idUsuario = ($this->session->getAuthUser()) ? $this->session->getAuthUser()->getId() : null;
        $data = $this->datos(['novedades'=>$this->edicionModel->getNovedades($idUsuario),
                              'wather_key' => $this->config['wather_key'] ]);
        echo $this->render->render("public/view/index.mustache", $data);
    }

    public function logOut()
    {
        $this->session->closeSession();
        Redirect::doIt("/infonete");
    }

    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }

}