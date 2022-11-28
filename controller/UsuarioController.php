<?php

class UsuarioController
{

    private $userModel;
    private $render;
    private $session;

    public function __construct($userModel, $session, $render)
    {
        $this->render = $render;
        $this->session = $session;
        $this->userModel = $userModel;
    }

    /**
     * Meteodo qur lanza la vista de gestion de Usuarios.
     * Restricción usuario Admin
     *
     * @return Html
     */
    public function admin()
    {
        //restricción de metodo.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $value = $_GET['value'] ?? null;

        $data = $this->dato(["users" => $this->userModel->listar($value),
            "roles" => $this->userModel->listRoles(),
            "value" => $value,
            "userAuth" => $this->session->getAuthUser()]);

        echo $this->render->render("public/view/gestion-usuario.mustache", $data);
    }

    /**
     * Meteodo que solicita cambio de estado de Cuenta Usuario a VERIFICADA.
     *
     *
     * @return JSON
     */
    public function activate()
    {
        $email = $_GET['email'] ?? null;
        $hash = $_GET['hash'] ?? null;

        $data['success'] =  ($email && $hash) ? $this->userModel->activate($email, $hash): false;

        echo $this->render->render("public/view/activate.mustache", $data);

    }

    /**
     * Meteodo que solicita cambio de estado de Usuario a ACTIVO.
     * AJAX
     *
     * @return JSON
     */
    public function bloquear()
    {
        //restricción de metodo.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $id = $_GET['id'] ?? 0;

        $res = $this->userModel->bloquear($id);

        header('Content-Type: application/json');
        echo json_encode($res, JSON_FORCE_OBJECT);
    }

    /**
     * Meteodo que solicita cambio de estado de Usuario INACTIVO.
     * AJAX
     *
     * @return JSON
     */
    public function desbloquear()
    {
        //restricción de metodo.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $id = $_GET['id'] ?? 0;

        $res = $this->userModel->desbloquear($id);

        header('Content-Type: application/json');
        echo json_encode($res, JSON_FORCE_OBJECT);
    }

    /**
     * Meteodo que solicita cambio de rol de usuario.
     * AJAX
     *
     * @return JSON
     */
    public function setRol()
    {
        $rol = $_GET['rol'];
        $id = $_GET['id'];

        $response = $this->userModel->setRolTo($id, $rol);

        header('Content-Type: application/json');
        echo json_encode($response, JSON_FORCE_OBJECT);
    }

    /**
     * Meteodo qur genera el array de Datos que sera enviado a la Vista.
     *
     * @return array
     */
    private function dato($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser(),
            "edicion" => $this->session->getParameter('activeEdition')
        ));
    }


}