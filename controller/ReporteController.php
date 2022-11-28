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

    /**
     * Meteodo inicial que genera datos lanza vista Dasboard con graficos.
     * Restriccion: usuario Admin
     *
     * @return Html con resultados de operacion
     */
    public function execute()
    {
        //restricción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

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

    /**
     * Meteodo que genera reporte de compras y lanza vista.
     * Restriccion: usuario Admin
     *
     * @return Html con resultados de operacion
     */
    public function compras()
    {
        //restricción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        //intervalo de fechas
        $fechaI = $_POST['fechaI'] ??  date("Y-m-01");
        $fechaF = $_POST['fechaF'] ??  date("Y-m-t");

        $data = $this->datos(['compras_usuario' => $this->reporteModel->getComprasUsuario($fechaI,$fechaF),
                                 'fecha_inicio' => $fechaI,
                                    'fecha_fin' => $fechaF]);

        echo $this->render->render('public/view/dashboard/dashboard-compras-usuarios.mustache', $data);
    }

    /**
     * Meteodo que genera reporte y lanza vista.
     * Restriccion: usuario Admin
     *
     * @return Html con resultados de operacion
     */
    public function productos()
    {
        //restricción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        //intervalo de fechas
        $fechaI = $_POST['fechaI'] ??  date("Y-m-01");
        $fechaF = $_POST['fechaF'] ??  date("Y-m-t");

        $data = $this->datos(['productos' => $this->reporteModel->getProductos($fechaI,$fechaF),
                           'fecha_inicio' => $fechaI,
                              'fecha_fin' => $fechaF]);

        echo $this->render->render('public/view/dashboard/dashboard-productos.mustache', $data);
    }

    /**
     * Meteodo que muestra reporte de compras de los usuarios en PDF.
     * Restriccion: usuario Admin
     *
     * @return PDF
     */
    public function comprasPdf()
    {
        //restricción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        //intervalo de fechas
        $fechaI = $_GET['fi'] ??  date("Y-m-01");
        $fechaF = $_GET['ff'] ??  date("Y-m-t");

        $data = $this->datos(['compras_usuario' => $this->reporteModel->getComprasUsuario($fechaI,$fechaF),
                                 'fecha_inicio' => Fecha::longDate($fechaI),
                                    'fecha_fin' => Fecha::longDate($fechaF),
                                         'logo' => dirname(__FILE__,2) . "/public/images/logo/logo-text.png"]);

        echo $this->render->pdf('public/view/pdf/compras-usuario-pdf.mustache', $data, "compras.pdf");
    }

    /**
     * Meteodo que muestra reporte de productos en PDF.
     * Restriccion: usuario Admin
     *
     * @return PDF
     */
    public function productosPdf()
    {
        //restricción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        //intervalo de fechas
        $fechaI = $_GET['fi'] ??  date("Y-m-01");
        $fechaF = $_GET['ff'] ??  date("Y-m-t");

        $data = $this->datos(['productos' => $this->reporteModel->getProductos($fechaI,$fechaF),
                           'fecha_inicio' => Fecha::longDate($fechaI),
                              'fecha_fin' => Fecha::longDate($fechaF),
                                   'logo' => dirname(__FILE__,2) . "/public/images/logo/logo-text.png"]);

        echo $this->render->pdf('public/view/pdf/productos-pdf.mustache', $data, "productos.pdf");
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