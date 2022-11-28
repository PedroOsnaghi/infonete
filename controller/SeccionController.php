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

    /**
     * Meteodo que lanza la vista de gestion de Secciones.
     * Restricción: usuario Editor
     *
     * @return Html
     */
    public function admin()
    {
        //resticción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $data = $this->datos(["secciones" =>  $this->seccionModel->list()]);

        echo $this->render->render("public/view/gestion-seccion.mustache", $data);
    }

    /**
     * Meteodo que lanza la vista Nueva Sección.
     * Restricción: usuario Editor
     *
     * @return Html
     */
    public function crear()
    {
        //resticción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $data = $this->datos();

        echo $this->render->render("public/view/seccion.mustache", $data);
    }

    /**
     * Meteodo que solicita el registro de una nueva Sección con datos POST.
     * Restricción: usuario Editor
     *
     * @return Html con resultado de operación
     */
    public function guardar()
    {
        //resticción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $this->setearSeccion();

        ($this->seccionModel->guardar()) ?
            $data['success'] = "La seccion se guardó correctamente" :
            $data['error'] = "Hubo un error al guardar la seccion";

        echo $this->render->render("public/view/seccion.mustache", $data);
    }

    /**
     * Meteodo que lanza vista de Editar Sección.
     * Restricción: usuario Editor
     *
     * @return Html
     */
    public function editar()
    {
        //resticción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $data = $this->datos(["seccion" =>  $this->seccionModel->getSection($_GET['id'])]);

        echo $this->render->render("public/view/editar-seccion.mustache", $data);
    }

    /**
     * Meteodo que solicita la actualizacion de datos de Seccion.
     * Restricción: usuario Editor
     *
     * @return Html con resultado de operación
     */
    public function actualizar()
    {
        //resticción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $this->setearSeccion();

        $data = $this->datos($this->seccionModel->update());

        echo $this->render->render("public/view/editar-seccion.mustache", $data);
    }

    /**
     * Meteodo privado que setea el objeto Seccion.
     *
     * @return void
     */
    private function setearSeccion()
    {
        $this->seccionModel->setId($_GET['id'] ?? null);
        $this->seccionModel->setNombre($_POST['nombre']);
        $this->seccionModel->setDescripcion($_POST['descripcion']);
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