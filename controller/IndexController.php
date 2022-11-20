<?php

class IndexController
{

    private $render;
    private $session;
    private $edicionModel;

    public function __construct($edicionModel, $session, $render)
    {
        $this->edicionModel = $edicionModel;
        $this->session = $session;
        $this->render = $render;
    }

    public function execute()
    {
        $data = $this->datos(['novedades'=>$this->edicionModel->getNovedades()]);
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