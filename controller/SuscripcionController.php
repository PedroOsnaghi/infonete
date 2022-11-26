<?php

class SuscripcionController
{
    private $suscripcionModel;
    private $productoModel;
    private $render;
    private $session;
    private $checkout;
    private $logger;

    public function __construct($suscripcionModel, $productoModel, $checkout, $session, $logger, $render)
    {
        $this->suscripcionModel = $suscripcionModel;
        $this->productoModel = $productoModel;
        $this->checkout = $checkout;
        $this->session = $session;
        $this->logger = $logger;
        $this->render = $render;
    }

    public function execute()
    {

    }

    public function admin()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $data = $this->datos(["planes" => $this->suscripcionModel->list()]);
        echo $this->render->render("public/view/gestion-suscripcion.mustache", $data);
    }

    public function crear()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);
        $data = $this->datos(["tipos" => $this->suscripcionModel->listTipos()]);
        echo $this->render->render("public/view/suscripcion.mustache", $data);
    }

    public function suscribirse()
    {
        $this->session->urlRestriction();

        $suscripcion = $this->suscripcionModel->getSuscripcion($_GET['s']);


        $data = $this->datos([ "suscripcion" => $suscripcion,
                                "productos" => $this->productoModel->list()]);

        echo $this->render->render("public/view/suscribirse.mustache", $data);
    }

    public function checkout()
    {
        $this->session->urlRestriction();

        $suscripcion = $this->suscripcionModel->getSuscripcion($_POST['s']);

        //configuracion del Checkout
        $this->checkout->target = CheckoutModel::TARGET_SUSCRIPTION;
        $this->checkout->cantidad = 1;
        $this->checkout->concepto = "Suscripcion ".$suscripcion->getDescripcion()." - ".$suscripcion->getDuracion()." dias";
        $this->checkout->precio = $suscripcion->getPrecio();
        $this->checkout->data = array("suscripcion" => $suscripcion,
                                      "producto" => $this->productoModel->getProduct($_POST['p']));

        $this->checkout->show();

    }


    public function guardar()
    {
        $this->setSuscripcion();

        $data = $this->datos($this->suscripcionModel->guardar());

        echo $this->render->render("public/view/suscripcion.mustache", $data);
    }

    public function planes(){

        $data = $this->datos(["planes" => $this->suscripcionModel->list()]);

        echo $this->render->render("public/view/planes.mustache", $data);
    }


    public function misSuscripciones()
    {
        $this->session->urlRestriction();
        $data = $this->datos(['suscripciones' => $this->suscripcionModel->listarSuscripcionesUsuario($this->session->getAuthUser()->getId())]);
        echo $this->render->render("public/view/mis-suscripciones.mustache", $data);
    }

    public function cancelar()
    {
        $this->session->urlRestriction();
        $id_suscripcion = $_GET['s'] ?? null;
        $id_producto = $_GET['p'] ?? null;
        if ($id_suscripcion && $id_producto) {
            $this->suscripcionModel->cancelarSuscripcion($id_suscripcion, $id_producto, $this->session->getAuthUser()->getId());
            $this->misSuscripciones();
        }
    }

    private function setSuscripcion()
    {
        $this->suscripcionModel->setDescripcion($_POST['descripcion']);
        $this->suscripcionModel->setDuracion($_POST['duracion']);
        $this->suscripcionModel->setPrecio($_POST['precio']);
        $this->suscripcionModel->setTag($_POST['tag']);
    }

    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }


}