<?php

class ReporteModel
{

    private $database;
    private $logger;

    public function __construct($database, $logger)
    {
        $this->database = $database;
        $this->logger = $logger;
    }

    public function getVentasSuscripciones($fechaI = null, $fechaF = null)
    {
         if (!$fechaI) $fechaI = date("Y-m-01");
         if (!$fechaF) $fechaF = date("Y-m-t");
         $this->logger->info('fi: ' . $fechaI);
         $this->logger->info('ff: ' . $fechaF);
            return $this->database->query("SELECT s.descripcion, count(s.id) as cantVenta
                                            FROM suscripcion s
                                            JOIN usuario_suscripcion us ON us.id_suscripcion = s.id
                                            WHERE DATE(us.fecha_inicio) BETWEEN DATE($fechaI) AND DATE($fechaF)
                                            GROUP BY s.descripcion");
    }
}