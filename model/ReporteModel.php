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

    public function getVentasSuscripciones($fechaI , $fechaF )
    {

         return $this->database->list("SELECT s.descripcion, count(s.id) as 'cantVenta'
                                            FROM suscripcion s
                                            JOIN usuario_suscripcion us ON us.id_suscripcion = s.id
                                            WHERE DATE(us.fecha_inicio) BETWEEN DATE('$fechaI') AND DATE('$fechaF') GROUP BY s.descripcion");

       // {"basico" => 3, "premium" => 10, "pro" => 15}
    }

    public function getVentasProductos($fechaI , $fechaF )
    {

        return $this->database->list("SELECT p.nombre, count(p.id) as 'cantVenta'
                                            FROM producto p
                                            JOIN edicion e ON e.id_producto = p.id
                                            JOIN compra_edicion ce ON ce.id_edicion = e.id
                                            WHERE DATE(ce.fecha) BETWEEN DATE('$fechaI') AND DATE('$fechaF') GROUP BY p.nombre");
    }

    public function getComprasUsuario($fechaI , $fechaF )
    {
        $sql= "SELECT m.id, m.avatar, m.nombre, m.apellido, m.email, m.suscripcion, count(ce.id_usuario) as 'edicion' FROM (SELECT u.*, count(d.id_usuario) as 'suscripcion'
                                            FROM usuario u
                                            LEFT JOIN (SELECT id_usuario FROM usuario_suscripcion WHERE DATE(fecha_inicio) BETWEEN DATE('$fechaI') AND DATE('$fechaF') ) d ON d.id_usuario = u.id
											GROUP BY u.id) m  LEFT JOIN compra_edicion ce ON ce.id_usuario = m.id
                                            WHERE DATE(ce.fecha) BETWEEN DATE('$fechaI') AND DATE('$fechaF') GROUP BY m.id";

        $this->logger->info($sql);
        return $this->database->list($sql);
    }
}