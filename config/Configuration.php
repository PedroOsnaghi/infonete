<?php

use PHPMailer\PHPMailer\PHPMailer;
use Dompdf\Dompdf;

//HELPERS
include_once("helper/MysqlDatabase.php");
include_once("helper/Render.php");
include_once("helper/UrlHelper.php");
include_once("helper/Redirect.php");
include_once("helper/hasher.php");
include_once("helper/Mailer.php");
include_once("helper/File.php");
include_once("helper/Session.php");
include_once("helper/Logger.php");
include_once("helper/Fecha.php");

//MODELOS
include_once("model/HomeModel.php");
include_once("model/UsuarioModel.php");
include_once("model/ProductoModel.php");
include_once("model/EdicionModel.php");
include_once("model/ArticuloModel.php");
include_once("model/SeccionModel.php");
include_once("model/PlanesModel.php");
include_once("model/CheckoutModel.php");
include_once("model/ReporteModel.php");
include_once("model/SuscripcionModel.php");
include_once("model/MisProductosModel.php");

//CONTROLADORES
include_once("controller/HomeController.php");
include_once("controller/LoginController.php");
include_once("controller/RegisterController.php");
include_once("controller/UsuarioController.php");
include_once("controller/ProductoController.php");
include_once("controller/EdicionController.php");
include_once("controller/ArticuloController.php");
include_once("controller/SeccionController.php");
include_once("controller/PlanesController.php");
include_once("controller/ViewerController.php");
include_once("controller/CheckoutController.php");
include_once("controller/ReporteController.php");
include_once("controller/CatalogoController.php");
include_once("controller/MisProductosController.php");
include_once("controller/MisSuscripcionesController.php");

//VENDORS
include_once('vendor/PHPMailer-master/src/Exception.php');
include_once('vendor/PHPMailer-master/src/PHPMailer.php');
include_once('vendor/PHPMailer-master/src/SMTP.php');
include_once('vendor/mustache/src/Mustache/Autoloader.php');
include_once("Router.php");
include_once("vendor/autoload.php");


class Configuration
{
    //Controllers
    public function getMisSuscripcionesController()
    {
        return new MisSuscripcionesController($this->getSuscripcionModel(), $this->getSession(), $this->getRender());
    }

    public function getMisProductosController()
    {
        return new MisProductosController($this->getMisProductosModel(), $this->getSession(), $this->getRender());
    }

    public function getReporteController()
    {
        return new ReporteController($this->getReporteModel(), $this->getSession(), $this->getRender());
    }

    public function getViewerController()
    {
        return new ViewerController($this->getEdicionModel(), $this->getSeccionModel(), $this->getArticuloModel(), $this->getCheckoutController(), $this->getLogger(), $this->getSession(), $this->getRender());
    }

    public function getCatalogoController()
    {
        return new CatalogoController($this->getProductoModel(), $this->getEdicionModel(), $this->getSession(), $this->getRender());
    }

    public function getArticuloController()
    {
        return new ArticuloController($this->getArticuloModel(), $this->getEdicionModel(), $this->getSeccionModel(),  $this->getSession(),  $this->getRender());
    }

    public function getHomeController()
    {
        $config = $this->getConfig();
        $cfg = array('wather_key' => $config['wather_api_key']);
        return new HomeController($cfg, $this->getHomeModel(), $this->getSession(), $this->getRender());
    }

    public function getSeccionController()
    {
        return new SeccionController($this->getSeccionModel(), $this->getSession(), $this->getRender());
    }

    public function getPlanesController()
    {
        return new PlanesController($this->getPlanesModel(), $this->getCheckoutController(), $this->getSession(),  $this->getRender());
    }

    public function getEdicionController()
    {
        return new EdicionController($this->getEdicionModel(), $this->getProductoModel(), $this->getCheckoutController(),  $this->getSession(), $this->getRender());
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

    public function getCheckoutController()
    {
        $config = $this->getConfig();
        $cfgCheckout = array("token" => $config['mp_token'],
            "publicKey" => $config['mp_public_key'] );
        return new CheckoutController($cfgCheckout, $this->getCheckoutModel(), $this->getSession(), $this->getRender());
    }


    //Models
    private function getHomeModel()
    {
        return new HomeModel($this->getDatabase());
    }

    private function getReporteModel()
    {
        return new ReporteModel($this->getDatabase(), $this->getLogger());
    }

    private function getArticuloModel()
    {
        return new ArticuloModel($this->getFile(), $this->getLogger(), $this->getDatabase());
    }

    private function getSeccionModel()
    {
        return new SeccionModel($this->getLogger(), $this->getDatabase());
    }

    private function getPlanesModel()
    {
        return new PlanesModel($this->getDatabase());
    }

    private function getEdicionModel()
    {
        return new EdicionModel($this->getLogger(),$this->getFile(), $this->getDatabase());
    }

    private function getProductoModel()
    {
        return new ProductoModel($this->getFile(), $this->getDatabase());
    }

    private function getUsuarioModel()
    {
        return new UsuarioModel($this->getFile(), $this->getMailer(), $this->getDatabase());
    }

    private function getCheckoutModel()
    {
        return new CheckoutModel($this->getDatabase());
    }

    private function getMisProductosModel()
    {
        return new MisProductosModel($this->getDatabase());
    }

    private function getSuscripcionModel()
    {
        return new SuscripcionModel($this->getDatabase());
    }

    //Helpers
    private function getConfig()
    {
        return parse_ini_file("config/config.ini");
    }

    public function getRender()
    {
        return new Render('public/view/partial', $this->getPdf());
    }

    public function getRouter()
    {
        return new Router($this);
    }

    public function getUrlHelper()
    {//interpreta la ruta
        return new UrlHelper();
    }

    public function getPdf()
    {
        return new Dompdf();
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