<?php

class CheckoutController
{
    //modelo
    private $checkoutModel;

    //configuraciones
    private $mpPreference;
    private $publicKey;

    //atributos publicos de configuracion

    //$target: establece el tipo de operacion: TARGET_EDITION o TARGET_SUSCRIPTION
    public $target;
    //$concepto: establece una descripcion a la operacion de pago
    public $concepto;
    //$cantidad: especifica la cantidad de elementos vendidos
    public $cantidad;
    //$precio: valor de la venta
    public $precio;
    //$data: array de datos que se pasaran a la vista del Checkout
    public $data;
    

    private $session;
    private $render;



    public function __construct($config, $checkoutModel, $session, $render)
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

    /**
     * Meteodo inicial que redirecciona a 404.
     *
     * @return Html
     */
    public function execute()
    {
       $this->redirect404();
    }

    /**
     * Metodo que Lanza la vista de Checkaut segun el tipo de compra.
     *
     * Requiere haber seteado previamente las propiedades de configuracion
     *
     * @return Html
     */
    public function show()
    {
        switch ($this->target){
            case CheckoutModel::TARGET_EDITION:
               $this->lanzarCheckoutEdicion();
                break;

            case CheckoutModel::TARGET_SUSCRIPTION:
              $this->lanzarCheckoutSuscripcion();
                break;
        }
    }

    /**
     * Meteodo que de ingreso desde Api MP para caso de pago 'apporved'
     *
     * @return void | 404
     */
    public function success(){

        $status = $_GET['status'] ?? null;
        $payment_id = $_GET['payment_id'] ?? null;

        ($status && $payment_id) ?
            $this->informarPago($payment_id):
            $this->redirect404();

    }

    /**
     * Meteodo que Imprime en PDF la factura de Compra
     *
     * @return PDF
     */
    public function mostrarFactura()
    {
        $paymentId = $_GET['payment_id'];
        $data = $this->datos(['factura' => $this->checkoutModel->getFactura($paymentId),
                             'logo' => dirname(__FILE__,2) . "/public/images/logo/logo-text.png"]);
        $this->render->pdf('public/view/pdf/factura.mustache', $data, 'factura-' . $paymentId . '.pdf');
    }

    /**
     * Meteodo que lanza la vista de Checkout para una Edicion
     *
     * @return Html
     */
    private function lanzarCheckoutEdicion()
    {
        $this->guardarDatosCompra(["type" => $this->target,
                             "id_edicion" => $this->data['edicion']->getId(),
                             "id_usuario" => $this->session->getAuthUser()->getId(),
                               "concepto" => $this->concepto,
                                 "precio" => $this->precio,
                               "cantidad" => $this->cantidad]);


        echo $this->render->render("public/view/checkout/edicion-checkout.mustache", $this->generarDatosCheckout());
    }

    /**
     * Meteodo que lanza la vista de Checkout para una Suscripcion
     *
     * @return Html
     */
    private function lanzarCheckoutSuscripcion()
    {
        $this->guardarDatosCompra(["type" => $this->target,
                         "id_suscripcion" => $this->data['suscripcion']->getId(),
                            "id_producto" => $this->data['suscripcion']->getProducto()['id'],
                             "id_usuario" => $this->session->getAuthUser()->getId(),
                               "concepto" => $this->concepto,
                                 "precio" => $this->precio,
                               "cantidad" => $this->cantidad]);

        echo $this->render->render("public/view/checkout/suscripcion-checkout.mustache", $this->generarDatosCheckout());
    }

    /**
     * Meteodo que se encarga de solicitar al Modelo el registro de la Compra
     * sea SUSCRIPCION o EDICION
     *
     * @return Html Vista con la respuesta de success o error
     */
    private function informarPago($payment_id)
    {
        $data = ($payment_id) ?
            $this->datos($this->checkoutModel->registrarPago($this->session->getParameter('compra'),$payment_id)):
            $this->datos(['error' => 'Acceso denegado']);

        echo $this->render->render('public/view/checkout/compra-checkout.mustache', $data);
    }

    /**
     * Meteodo que guarda en la Session del usuario los datos de la Compra
     *
     * @return void
     */
    private function guardarDatosCompra($datos)
    {
        $this->session->setParameter('compra', $datos);
    }

    /**
     * Meteodo que genera array de datos que se envian a la vista Checkout
     *
     * @return array
     */
    private function generarDatosCheckout()
    {
        return array_merge($this->data,["pk" => $this->publicKey,
                                        "pid" => $this->cargarItem(),
                                        "userAuth" => $this->session->getAuthUser()]);
    }

    /**
     * Meteodo que setea un nuevo item de MercadoPago
     *
     * @return long preference_id
     */
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

    /**
     * Meteodo que setea el Token al objeto MercadoPago
     *
     * @return void
     */
    private function setearCredenciales()
    {
        // Agrega credenciales
        MercadoPago\SDK::setAccessToken($this->token);
    }

    /**
     * Meteodo que instancia un objeto Preference de MercadoPago
     *
     * @return void
     */
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

    /**
     * Redireccion 404
     *
     * @return Html
     */
    private function redirect404()
    {
        echo $this->render->render("public/view/404/404.mustache",$this->datos());
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