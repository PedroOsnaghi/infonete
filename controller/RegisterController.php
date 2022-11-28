<?php

class RegisterController{

    private $usuarioModel;
    private $render;


    public function __construct($usuarioModel, $render)
    {
        $this->usuarioModel = $usuarioModel;
        $this->render = $render;
    }

    /**
     * Meteodo inicial que lanza la vista de Reguistro de Usuario.
     * Restriccion: Publico.
     *
     * @return Html.
     */
    public function execute()
    {
        echo $this->render->render("public/view/registro.mustache");
    }

    /**
     * Meteodo que solicita el registro de datos POST.
     * Restriccion: Publico.
     *
     * @return success | error.
     */
    public function validar()
    {
        $usuario = $this->setearUsuario();

        $response = $usuario->registrar();

        ($response['status'] == 'success') ?
                $this->sendSuccess($response):
                $this->sendError($response);

    }

    /**
     * Meteodo que verifica email existente.
     * AJAX.
     *
     * @return Json respuesta.
     */
    public function existeEmail()
    {
        $email = $_GET['email'];
        $res = $this->userModel->existeEmail($email);
        header('Content-Type: application/json');
        echo json_encode($res,JSON_FORCE_OBJECT);

    }

    /**
     * Meteodo privado que setea el objeto usuario.
     * Restriccion: N/A.
     *
     * @return Html.
     */
    private function setearUsuario()
    {
        $this->usuarioModel->setNombre($_POST['nombre']);
        $this->usuarioModel->setApellido($_POST['apellido']);
        $this->usuarioModel->setPass(hasher::encrypt($_POST['pass']));
        $this->usuarioModel->setEmail($_POST['email']);
        $this->usuarioModel->setDomicilio($_POST['direccion']);
        $this->usuarioModel->setLatitud($_POST['lat']);
        $this->usuarioModel->setLongitud($_POST['lng']);
        $this->usuarioModel->setHash(md5(rand(0000000000, 9999999999)));
        $this->usuarioModel->setRol(UsuarioModel::ROL_LECTOR);
        $this->usuarioModel->setEstado(UsuarioModel::STATE_UNVERIFIED);
        $this->usuarioModel->setActivo(1);

        return $this->usuarioModel;
    }

    /**
     * Meteodo privado que lanza la vista con datos de error.
     * Restriccion: N/A.
     *
     * @return Html con error.
     */
    private function sendError($data)
    {
        echo $this->render->render("public/view/registro.mustache", $data);
    }

    /**
     * Meteodo privado que lanza la vista con datos Success.
     * Restriccion: N/A.
     *
     * @return Html con datos success.
     */
    private function sendSuccess($response)
    {
        echo $this->render->render("public/view/register-success.mustache", $response);
    }






}