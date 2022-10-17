<?php
include_once("helper/MysqlDatabase.php");
include_once("helper/Render.php");
include_once("helper/UrlHelper.php");

//MODELOS
include_once("model/LoginModel.php");


//CONTROLADORES
include_once("controller/LoginController.php");


include_once('third-party/mustache/src/Mustache/Autoloader.php');
include_once("Router.php");

class Configuration
{
    public function getLoginModel()
    {
        $database = $this->getDatabase();
        return new LoginModel($database);
    }

    public function getLoginController()
    {
        $loginModel = $this->getLoginModel();
        return new LoginController($loginModel, $this->getRender());
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

}