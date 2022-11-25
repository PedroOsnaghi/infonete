<?php

class CheckoutController
{

    private $mpPreference;
    private $product = [];
    private $publicKey;
    
    //recibe un array de datos del producto:
    // $product['cantidad']
    // $product['concepto']
    // $product['precio']
    private $session;
    private $logger;


    public function setProduct($product): void
    {
        $this->product = $product;
    }
    

    public function __construct($token, $publicKey, $session, $logger)
    {
        $this->publicKey = $publicKey;
        $this->session = $session;
        $this->logger = $logger;

        
        // Agrega credenciales
        MercadoPago\SDK::setAccessToken($token);
        $this->mpPreference = new MercadoPago\Preference();
        $this->mpPreference->back_urls = array(
            "success" => "https://localhost/infonete/checkout/informar",
            "failure" => "https://localhost/infonete/checkout/informar",
            "pending" => "https://localhost/infonete/checkout/informar"
        );
        $this->mpPreference->auto_return = "approved";
    }

    public function checkOut(){

            // Crea un ítem en la preferencia
            $item = new MercadoPago\Item();
            $item->title = $this->product['concepto'];
            $item->quantity = $this->product['cantidad'];
            $item->unit_price = $this->product['precio'];
            $this->mpPreference->items = array($item);
            $this->mpPreference->save();

            return array("id" => $this->mpPreference->id, "publickey" => $this->publicKey);

    }

   
    
    public function informar(){

        $respuestaMP = $_GET['status'];
        $payment_id = $_GET['payment_id'];

        $this->logger->info("respuesta de MP: " . $respuestaMP);

        switch ($respuestaMP){
            case "approved":
                $target = $this->session->getParameter('compra')['target'];
                Redirect::doIt("/infonete/$target/registrarCompra?pi=$payment_id");
            case "pending":
        }


    }
}