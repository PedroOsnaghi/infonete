<?php

class ReporteController
{

    private $reporteModel;
    private $session;
    private $render;

    public function __construct($reporteModel, $session, $render)
    {
        $this->reporteModel = $reporteModel;
        $this->session = $session;
        $this->render = $render;
    }

    public function execute()
    {
        //intervalo de fechas suscripciones
        $fechaIs = $_POST['fechaIs'] ??  date("Y-m-01");
        $fechaFs = $_POST['fechaFs'] ??  date("Y-m-t");

        //intervalod de fechas productos
        $fechaIp = $_POST['fechaIp'] ??  date("Y-m-01");
        $fechaFp = $_POST['fechaFp'] ??  date("Y-m-t");


        $data = $this->datos(['datos_suscripcion' => $this->reporteModel->getVentasSuscripciones($fechaIs,$fechaFs),
                              'datos_productos' => $this->reporteModel->getVentasProductos($fechaIp,$fechaFp),
                                'fecha_inicio_s' => $fechaIs,
                                'fecha_fin_s' => $fechaFs,
                                'fecha_inicio_p' => $fechaIp,
                                'fecha_fin_p' => $fechaFp]);

        echo $this->render->render('public/view/dashboard/dashboard.mustache', $data);
    }

    public function compras(){
        //intervalo de fechas
        $fechaI = $_POST['fechaI'] ??  date("Y-m-01");
        $fechaF = $_POST['fechaF'] ??  date("Y-m-t");

        $data = $this->datos(['compras_usuario' => $this->reporteModel->getComprasUsuario($fechaI,$fechaF),
                                'fecha_inicio' => $fechaI,
                                'fecha_fin' => $fechaF]);

        echo $this->render->render('public/view/dashboard/dashboard-compras-usuarios.mustache', $data);
    }

    public function productos(){
        //intervalo de fechas
        $fechaI = $_POST['fechaI'] ??  date("Y-m-01");
        $fechaF = $_POST['fechaF'] ??  date("Y-m-t");

        $data = $this->datos(['productos' => $this->reporteModel->getProductos($fechaI,$fechaF),
            'fecha_inicio' => $fechaI,
            'fecha_fin' => $fechaF]);

        echo $this->render->render('public/view/dashboard/dashboard-productos.mustache', $data);
    }

    public function comprasPdf(){
        //intervalo de fechas

        $fechaI = $_GET['fi'] ??  date("Y-m-01");
        $fechaF = $_GET['ff'] ??  date("Y-m-t");

        $data = $this->datos(['compras_usuario' => $this->reporteModel->getComprasUsuario($fechaI,$fechaF),
            'fecha_inicio' => Fecha::longDate($fechaI),
            'fecha_fin' => Fecha::longDate($fechaF),
            'logo' => dirname(__FILE__,2) . "/public/images/logo/logo-text.png"]);

        echo $this->render->pdf('public/view/pdf/compras-usuario-pdf.mustache', $data, "compras.pdf");
    }

    public function productosPdf(){
        //intervalo de fechas

        $fechaI = $_GET['fi'] ??  date("Y-m-01");
        $fechaF = $_GET['ff'] ??  date("Y-m-t");

        $data = $this->datos(['productos' => $this->reporteModel->getProductos($fechaI,$fechaF),
            'fecha_inicio' => Fecha::longDate($fechaI),
            'fecha_fin' => Fecha::longDate($fechaF),
            'logo' => dirname(__FILE__,2) . "/public/images/logo/logo-text.png"]);

        echo $this->render->pdf('public/view/pdf/productos-pdf.mustache', $data, "productos.pdf");
    }



    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }

}