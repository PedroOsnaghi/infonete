<?php

class IndexController
{

    private $render;
    private $session;

    public function __construct($session, $render)
    {
        $this->session = $session;
        $this->render = $render;
    }

    public function execute()
    {
        $user = $this->session->getAuthUser();

        echo $this->render->render("public/view/index.mustache", $user);
    }

    public function logOut()
    {
        $this->session->closeSession();
        Redirect::doIt("/infonete");
    }

}