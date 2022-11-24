<?php

class SuscripcionController
{
    private $suscripcionModel;
    private $productoModel;
    private $render;
    private $session;
    private $mercadoPago;

    public function __construct($suscripcionModel, $productoModel, $mercadoPago, $session, $render)
    {
        $this->suscripcionModel = $suscripcionModel;
        $this->productoModel = $productoModel;
        $this->mercadoPago = $mercadoPago;
        $this->session = $session;
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

        $idSuscripcion = $_GET['s'];
        $data = $this->datos(["suscripcion" => $this->suscripcionModel->getSuscripcion($idSuscripcion),
                             "productos" => $this->productoModel->list()]);
        echo $this->render->render("public/view/suscribirse.mustache", $data);
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

    public function comprar()
    {
        $this->session->urlRestriction();

        $idSuscripcion = $_POST['id'];
        $idProducto = $_POST['producto'];
        $this->comprarSuscripcion($idSuscripcion, $idProducto);
    }

    public function misSuscripciones()
    {
        $this->session->urlRestriction();
        $data = $this->datos(['suscripciones' => $this->suscripcionModel->listarSuscripcionesUsuario($this->session->getAuthUser()->getId())]);
        echo $this->render->render("public/view/mis-suscripciones.mustache", $data);
    }

    private function comprarSuscripcion($idS, $idP)
    {
        $suscripcion = $this->suscripcionModel->getSuscripcion($idS);
        $datosVenta = array('numero' => $idS,
            'concepto' => 'infonete-compra de suscripción ' . $suscripcion->getDescripcion() . ' - ' . $suscripcion->getDuracion() . ' días',
            'precio' => $suscripcion->getPrecio());

        if ($this->mercadoPago->procesarPago($datosVenta)) {
            $data = $this->datos($this->suscripcionModel->registrarCompra($this->session->getAuthUser()->getId(), $idS, $idP));
            echo $this->render->render('public/view/compra-checkout.mustache', $data);
        } else {
            $data = $this->datos(['warning' => 'No se pudo procesar el pago. Vuelva a intentarlo más tarde']);
            echo $this->render->render('public/view/compra-checkout.mustache', $data);
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