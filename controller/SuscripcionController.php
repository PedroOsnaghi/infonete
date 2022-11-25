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

    public function generarDatosPago()
    {
        $this->session->urlRestriction();

        $idSuscripcion = $_POST['id'];
        $idProducto = $_POST['producto'];

        $this->logger->info("generacion de Datos $idSuscripcion - $idProducto");

        $this->startCheckout($idSuscripcion, $idProducto);
    }

    public function misSuscripciones()
    {
        $this->session->urlRestriction();
        $data = $this->datos(['suscripciones' => $this->suscripcionModel->listarSuscripcionesUsuario($this->session->getAuthUser()->getId())]);
        echo $this->render->render("public/view/mis-suscripciones.mustache", $data);
    }

    private function startCheckout($idS, $idP)
    {

        $this->guardarDatosEnSession($idS, $idP);

        $suscripcion = $this->suscripcionModel->getSuscripcion($idS);

        $this->checkout->setProduct(['cantidad' => 1,
                                    'concepto' => 'Suscripción ' . $suscripcion->getDescripcion() . ' - ' . $suscripcion->getDuracion() . ' días',
                                    'precio' => $suscripcion->getPrecio()]);

        header('Content-Type: application/json');
        echo json_encode($this->checkout->checkOut(), JSON_FORCE_OBJECT);
    }

    public function registrarCompra(){

        $idPago = $_GET['pi'] ?? null;

        if($idPago) {

            $idS = $this->session->getParameter('compra') !== null ?  ($this->session->getParameter('compra'))['idS'] : null;
            $idP = $this->session->getParameter('compra') !== null ?  ($this->session->getParameter('compra'))['idP'] : null;

                $data = ($idS && $idP) ?
                    $this->datos($this->suscripcionModel->registrarCompra($this->session->getAuthUser()->getId(), $idS, $idP, $idPago)):
                    $this->datos(['error' => 'Acceso denegado']);

        } else {
            $data = $this->datos(['error' => 'Acceso denegado']);
        }
        echo $this->render->render('public/view/compra-checkout.mustache', $data);
    }

    private function guardarDatosEnSession($idSuscripcion, $idProducto){
        $compra = array("target" => "suscripcion", "idS" => $idSuscripcion, "idP" => $idProducto);
        $this->session->setParameter('compra', $compra);
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