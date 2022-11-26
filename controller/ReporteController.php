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
        $data = $this->datos(['chartDataSus' => $this->reporteModel->getVentasSuscripciones()]);
        echo $this->render->render('public/view/dashboard.mustache', $data);
    }

    public function getDataSus()
    {
        $res = $this->reporteModel->getVentasSuscripciones();
        header('Content-Type: application/json');
        echo json_encode($res);
    }

    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }

}