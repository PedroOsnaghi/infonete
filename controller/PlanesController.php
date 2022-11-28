<?php

class PlanesController
{
    private $planesModel;


    private $render;
    private $session;
    private $checkout;


    public function __construct($planesModel, $checkout, $session, $render)
    {
        //setea modelo
        $this->planesModel = $planesModel;

        $this->checkout = $checkout;
        $this->session = $session;
        $this->render = $render;
    }


    /**
     * Metodo inicial que lanza la vista de Planes de Suscripción
     * Restricción: publico
     *
     * @return Html
     */
    public function execute(){

        $data = $this->datos(["planes" => $this->planesModel->list()]);

        echo $this->render->render("public/view/planes.mustache", $data);
    }

    /**
     * Metodo que Lanza la vista de Crear un nuevo Plan
     * Restricción: Rol Administrador
     *
     * @return Html
     */
    public function crear()
    {
        //Restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $data = $this->datos(["tipos" => $this->planesModel->listTipos()]);

        echo $this->render->render("public/view/suscripcion.mustache", $data);
    }

    /**
     * Metodo que solicita el guardar un Nuevo Plan
     * Restricción: Rol Administrador
     *
     * @return Html con el resultado de la operación
     */
    public function guardar()
    {
        //Restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $this->setearPlan();

        $data = $this->datos($this->planesModel->guardar());

        echo $this->render->render("public/view/suscripcion.mustache", $data);
    }

    /**
     * Metodo que lanza la vista de Gestión de Planes
     * Restricción: Rol Administrador
     *
     * @return Html
     */
    public function admin()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $data = $this->datos(["planes" => $this->planesModel->list()]);
        echo $this->render->render("public/view/gestion-suscripcion.mustache", $data);
    }

    /**
     * Metodo que lanza la vista de suscripción al Plan seleccionado
     * Restricción: usuario Logueado
     *
     * @return Html
     */
    public function suscribirse()
    {
        //restriccion de metodo
        $this->session->urlRestriction();

        $suscripcion = $this->planesModel->getPlan($_GET['s'] ?? 0);

        $data = $this->datos([ "suscripcion" => $suscripcion,
                                "productos" => $this->planesModel->listProductosDisponibles($this->session->getAuthUser()->getId(), $suscripcion->getId())]);

        echo $this->render->render("public/view/suscribirse.mustache", $data);
    }

    /**
     * Metodo que lanza el Checkout de Suscripcion
     * Restricción: usuario Logueado
     *
     * @return void
     */
    public function checkout()
    {
        //restriccion de metodo
        $this->session->urlRestriction();

        $id_suscripcion = $_POST['s'] ?? Redirect::doIt("/infonete/planes");
        $id_producto = $_POST['p'] ?? Redirect::doIt("/infonete/planes");

        //obtener objeto Plan
        $suscripcion = $this->planesModel->generarSuscripcion($id_suscripcion, $id_producto);

        $this->lanzarCheckout($suscripcion);

    }

    /**
     * Metodo privado que setea el Checkout
     *
     *
     * @return void
     */
    private function lanzarCheckout($suscripcion)
    {
        //configuracion del Checkout
        $this->checkout->target = CheckoutModel::TARGET_SUSCRIPTION;
        $this->checkout->cantidad = 1;
        $this->checkout->concepto = "Suscripcion ".$suscripcion->getDescripcion()." - ".$suscripcion->getDuracion()." dias";
        $this->checkout->precio = $suscripcion->getPrecio();
        $this->checkout->data = array("suscripcion" => $suscripcion);

        $this->checkout->show();
    }

    /**
     * Metodo privado que setea el objeto Plan con los datos recibido por Post
     * Restricción: usuario Logueado
     *
     * @return void
     */
    private function setearPlan()
    {
        $this->planesModel->setDescripcion($_POST['descripcion']);
        $this->planesModel->setDuracion($_POST['duracion']);
        $this->planesModel->setPrecio($_POST['precio']);
        $this->planesModel->setTag($_POST['tag']);
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