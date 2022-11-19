<?php

class SeccionController
{
    private $seccionModel;
    private $render;
    private $session;

    public function __construct($seccionModel, $session, $render)
    {
        $this->seccionModel = $seccionModel;
        $this->session = $session;
        $this->render = $render;
    }

    public function execute()
    {

    }

    public function admin()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $data = $this->datos(["secciones" =>  $this->seccionModel->list()]);
        echo $this->render->render("public/view/gestion-seccion.mustache", $data);
    }

    public function crear()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $data = $this->datos();
        echo $this->render->render("public/view/seccion.mustache", $data);
    }

    public function guardar()
    {
        $this->setSection();

        ($this->seccionModel->guardar()) ?
            $data['success'] = "La seccion se guardó correctamente" :
            $data['error'] = "Hubo un error al guardar la seccion";

        echo $this->render->render("public/view/seccion.mustache", $data);
    }

    public function editar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $data = $this->datos(["seccion" =>  $this->seccionModel->getSection($_GET['id'])]);
        echo $this->render->render("public/view/editar-seccion.mustache", $data);
    }

    public function actualizar()
    {
        $this->setSection($_GET['id']);

        $data = $this->datos($this->seccionModel->update());

        echo $this->render->render("public/view/editar-seccion.mustache", $data);
    }

    private function setSection($id = null)
    {
        if ($id != null) $this->seccionModel->setId($id);
        $this->seccionModel->setNombre($_POST['nombre']);
        $this->seccionModel->setDescripcion($_POST['descripcion']);
    }

    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }

}