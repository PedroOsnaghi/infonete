<?php

use PHPMailer\PHPMailer\PHPMailer;

//HELPERS
include_once("helper/MysqlDatabase.php");
include_once("helper/Render.php");
include_once("helper/UrlHelper.php");
include_once("helper/Redirect.php");
include_once("helper/hasher.php");
include_once("helper/Client.php");
include_once("helper/GeoPosition.php");
include_once("helper/Mailer.php");
include_once("helper/File.php");
include_once("helper/Session.php");
include_once("helper/Logger.php");
include_once("helper/Fecha.php");


//MODELOS
include_once("model/UsuarioModel.php");
include_once("model/ProductoModel.php");
include_once("model/EdicionModel.php");
include_once("model/ArticuloModel.php");
include_once("model/SeccionModel.php");
include_once("model/SuscripcionModel.php");
include_once("model/CheckoutModel.php");

//CONTROLADORES
include_once("controller/IndexController.php");
include_once("controller/LoginController.php");
include_once("controller/RegisterController.php");
include_once("controller/UsuarioController.php");
include_once("controller/ProductoController.php");
include_once("controller/EdicionController.php");
include_once("controller/ArticuloController.php");
include_once("controller/SeccionController.php");
include_once("controller/SuscripcionController.php");
include_once("controller/ViewerController.php");
include_once("controller/CheckoutController.php");

//vendors
include_once('vendor/PHPMailer-master/src/Exception.php');
include_once('vendor/PHPMailer-master/src/PHPMailer.php');
include_once('vendor/PHPMailer-master/src/SMTP.php');
include_once('vendor/mustache/src/Mustache/Autoloader.php');
include_once("Router.php");
include_once ("vendor/autoload.php");

class Configuration
{

    public function getViewerController()
    {
        return new ViewerController($this->getEdicionModel(), $this->getSeccionModel(), $this->getArticuloModel(), $this->getLogger(), $this->getSession(), $this->getRender());
    }

    public function getArticuloModel()
    {
        return new ArticuloModel($this->getFile(), $this->getLogger(), $this->getDatabase());
    }

    public function getArticuloController()
    {
        return new ArticuloController($this->getArticuloModel(), $this->getEdicionModel(), $this->getSeccionModel(), $this->getUsuarioModel(),  $this->getSession(),  $this->getLogger(),  $this->getRender());
    }

    public function getIndexController()
    {
        return new IndexController($this->getEdicionModel(), $this->getSession(), $this->getRender());
    }

    public function getSeccionModel()
    {
        return new SeccionModel($this->getLogger(), $this->getDatabase());
    }

    public function getSeccionController()
    {
        return new SeccionController($this->getSeccionModel(), $this->getSession(), $this->getRender());
    }

    public function getSuscripcionModel()
    {
        return new SuscripcionModel($this->getDatabase());
    }

    public function getSuscripcionController()
    {
        return new SuscripcionController($this->getSuscripcionModel(), $this->getProductoModel(), $this->getCheckoutController(), $this->getSession(), $this->getLogger(),  $this->getRender());
    }

    public function getEdicionModel()
    {
        return new EdicionModel($this->getLogger(),$this->getFile(), $this->getDatabase());
    }

    public function getEdicionController()
    {
        return new EdicionController($this->getEdicionModel(), $this->getProductoModel(), $this->getCheckoutController(),  $this->getSession(), $this->getRender());
    }

    public function getProductoModel()
    {
        return new ProductoModel($this->getFile(), $this->getDatabase());
    }

    public function getProductoController()
    {
        return new ProductoController($this->getProductoModel(), $this->getSession(), $this->getRender());
    }

    public function getLoginController()
    {
        return new LoginController( $this->getUsuarioModel(), $this->getSession(), $this->getRender(), $this->getLogger());
    }

    public function getRegisterController()
    {
        return new RegisterController($this->getUsuarioModel(), $this->getRender());
    }

    public function getUsuarioController()
    {
        return new UsuarioController($this->getUsuarioModel(), $this->getSession(), $this->getRender());
    }


    private function getUsuarioModel()
    {
        return new UsuarioModel($this->getFile(), $this->getMailer(), $this->getDatabase());
    }

    public function getCheckoutController()
    {
        $config = $this->getConfig();
        $cfgCheckout = array("token" => $config['mp_token'],
                            "publicKey" => $config['mp_public_key'] );
        return new CheckoutController($cfgCheckout, $this->getCheckoutModel(), $this->getSession(), $this->getLogger(), $this->getRender());
    }

    public function getCheckoutModel()
    {
        return new CheckoutModel($this->getDatabase());
    }

    private function getConfig()
    {
        return parse_ini_file("config/config.ini");
    }

    public function getRender()
    {
        return new Render('public/view/partial');
    }

    public function getRouter()
    {
        return new Router($this);
    }

    public function getUrlHelper()
    {//interpreta la ruta
        return new UrlHelper();
    }

    private function getDatabase()
    {
        $config = $this->getConfig();
        return new MysqlDatabase(
            $config["servername"],
            $config["username"],
            $config["password"],
            $config["dbname"]
        );
    }

    //TODO agregar apiKey al archivo config
    private function getGeoposition()
    {
        $config = $this->getConfig();

        return new GeoPosition($this->getClient(), $config["google_maps_api_key"]);
    }

    private function getClient()
    {
        return new Client();
    }

    private function getMailer()
    {
        $config = $this->getConfig();
        return new Mailer(new PHPMailer(true),
            $config['email_user'],
            $config['email_pass'],
            $config['smtp_host'],
            $config['smtp_port']);
    }



    private function getFile()
    {
        $config = $this->getConfig();
        return new File($this->getLogger(), $config['upload_root_dir']);
    }

    private function getSession()
    {
        $config = $this->getConfig();
        return new Session($config['session_lifetime']);
    }

    private function getLogger()
    {
        return new Logger();
    }




}