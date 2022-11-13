<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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

//MODELOS
include_once("model/LoginModel.php");
include_once("model/RegisterModel.php");
include_once("model/UsuarioModel.php");
include_once("model/ProductoModel.php");
include_once("model/EdicionModel.php");
include_once("model/ArticuloModel.php");
include_once("model/SeccionModel.php");
include_once("model/SuscripcionModel.php");

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

//vendors
require('third-party/PHPMailer-master/src/Exception.php');
require('third-party/PHPMailer-master/src/PHPMailer.php');
require('third-party/PHPMailer-master/src/SMTP.php');
include_once('third-party/mustache/src/Mustache/Autoloader.php');
include_once("Router.php");

class Configuration
{

    public function getArticuloModel()
    {
        $database = $this->getDatabase();
        return new ArticuloModel($database);
    }

    public function getArticuloController()
    {
        $articuloModel = $this->getArticuloModel();
        return new ArticuloController($articuloModel, $this->getEdicionModel(), $this->getSeccionModel(),  $this->getSession(), $this->getFile(), $this->getLogger(),  $this->getRender());
    }

    public function getIndexController()
    {
        return new IndexController($this->getSession(), $this->getRender());
    }

    public function getSeccionModel()
    {
        $database = $this->getDatabase();
        return new SeccionModel($database);
    }

    public function getSeccionController()
    {
        $seccionModel = $this->getSeccionModel();
        return new SeccionController($seccionModel, $this->getRender());
    }

    public function getSuscripcionModel()
    {
        $database = $this->getDatabase();
        return new SuscripcionModel($database);
    }

    public function getSuscripcionController()
    {
        $suscripcionModel = $this->getSuscripcionModel();
        return new SuscripcionController($suscripcionModel, $this->getSession(), $this->getRender());
    }

    public function getEdicionModel()
    {
        $database = $this->getDatabase();
        return new EdicionModel($this->getLogger(), $database);
    }

    public function getEdicionController()
    {
        $edicionModel = $this->getEdicionModel();
        return new EdicionController($edicionModel, $this->getProductoModel(), $this->getSession(), $this->getFile(), $this->getRender());
    }

    public function getProductoModel()
    {
        $database = $this->getDatabase();
        return new ProductoModel($database);
    }

    public function getProductoController()
    {
        $productoModel = $this->getProductoModel();
        return new ProductoController($productoModel, $this->getFile(), $this->getSession(), $this->getRender());
    }

    public function getLoginModel()
    {
        $database = $this->getDatabase();
        return new LoginModel($database, $this->getUsuarioModel());
    }

    public function getLoginController()
    {
        $loginModel = $this->getLoginModel();
        return new LoginController($loginModel, $this->getSession(), $this->getRender());
    }

    public function getRegisterController()
    {
        $registerModel = $this->getRegisterModel();
        $usuarioModel = $this->getUsuarioModel();
        $mailer = $this->getMailer();
        $file = $this->getFile();
        return new RegisterController($registerModel, $usuarioModel, $mailer, $file, $this->getRender());
    }

    public function getUsuarioController()
    {
        return new UsuarioController($this->getUsuarioModel(), $this->getSession(), $this->getRender());
    }

    private function getRegisterModel()
    {
        return new RegisterModel($this->getDatabase());
    }

    private function getUsuarioModel()
    {
        return new UsuarioModel($this->getDatabase());
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