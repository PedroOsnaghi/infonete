<?php

class CheckoutController
{

    private $mpPreference;
    private $publicKey;

    //atributos publicos requeridos
    public $target; //tipo de checkout se lanzará
    public $concepto;
    public $cantidad;
    public $precio;
    public $data;
    

    private $session;
    private $logger;
    private $checkoutModel;
    private $render;



    

    public function __construct($config, $checkoutModel, $session, $logger, $render)
    {
        $this->checkoutModel = $checkoutModel;
        $this->render = $render;
        $this->session = $session;

        //Configuraciones MP
        $this->publicKey = $config["publicKey"];
        $this->token = $config["token"];

        $this->setearCredenciales();

        $this->setearPreferencias();


    }



    public function show()
    {
        switch ($this->target){
            case CheckoutModel::TARGET_EDITION:
                $this->guardarDatosCompra(["type" => $this->target,
                                            "id_edicion" => $this->data['edicion']->getId(),
                                            "id_usuario" => $this->session->getAuthUser()->getId(),
                                            "concepto" => $this->concepto,
                                            "precio" => $this->precio,
                                            "cantidad" => $this->cantidad]);

                echo $this->render->render("public/view/checkout/edicion-checkout.mustache", $this->generarDatosCheckout());
                break;

            case CheckoutModel::TARGET_SUSCRIPTION:
                $this->guardarDatosCompra(["type" => $this->target,
                                            "id_suscripcion" => $this->data['suscripcion']->getId(),
                                            "id_producto" => $this->data['producto']->getId(),
                                            "id_usuario" => $this->session->getAuthUser()->getId(),
                                            "concepto" => $this->concepto,
                                            "precio" => $this->precio,
                                            "cantidad" => $this->cantidad]);

                echo $this->render->render("public/view/checkout/suscripcion-checkout.mustache", $this->generarDatosCheckout());
                break;
        }
    }

   
    
    public function success(){

        $status = $_GET['status'] ?? null;
        $payment_id = $_GET['payment_id'] ?? null;

        ($status && $payment_id) ?
            $this->informarPago($payment_id):
            $this->redirect404();

    }

    public function mostrarFactura()
    {
        $paymentId = $_GET['payment_id'];
        $data = $this->datos(['factura' => $this->checkoutModel->getFactura($paymentId),
                             'logo' => dirname(__FILE__,2) . "/public/images/logo/logo-text.png"]);
        $this->render->pdf('public/view/pdf/factura.mustache', $data, 'factura-' . $paymentId . '.pdf');
    }

    private function informarPago($payment_id)
    {
        $data = ($payment_id) ?
            $this->datos($this->checkoutModel->registrarPago($this->session->getParameter('compra'),$payment_id)):
            $this->datos(['error' => 'Acceso denegado']);

        echo $this->render->render('public/view/compra-checkout.mustache', $data);
    }

    private function guardarDatosCompra($datos)
    {
        $this->session->setParameter('compra', $datos);
    }


    private function generarDatosCheckout()
    {
        return array_merge($this->data,["pk" => $this->publicKey,
                                        "pid" => $this->cargarItem(),
                                        "userAuth" => $this->session->getAuthUser()]);
    }

    private function cargarItem()
    {
        // Crea un ítem en la preferencia
        $item = new MercadoPago\Item();
        $item->title = $this->concepto;
        $item->quantity = $this->cantidad;
        $item->unit_price = $this->precio;
        $this->mpPreference->items = array($item);
        $this->mpPreference->save();
        return $this->mpPreference->id;
    }

    private function setearCredenciales()
    {
        // Agrega credenciales
        MercadoPago\SDK::setAccessToken($this->token);
    }

    private function setearPreferencias()
    {
        $this->mpPreference = new MercadoPago\Preference();
        //redirecciones
        $this->mpPreference->back_urls = array(
            "success" => "https://localhost/infonete/checkout/success",
            //"failure" => "https://localhost/infonete/checkout/informar",
            //"pending" => "https://localhost/infonete/checkout/informar"
        );
        $this->mpPreference->auto_return = "approved";
    }

    private function redirect404()
    {
        echo $this->render->render("public/view/404/404.mustache",$this->datos());
    }

    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }


}