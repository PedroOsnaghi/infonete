<?php

class MisProductosModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function listarCompras($idUsuario)
    {
        return $this->database->list("SELECT e.id, e.numero, e.titulo, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.portada, t.tipo
                                        FROM edicion e JOIN producto p on e.id_producto = p.id 
                                        JOIN tipo_producto t on p.id_tipo_producto = t.id                                  
                                        JOIN compra_edicion ce on ce.id_edicion = e.id                                        
                                        WHERE ce.id_usuario = $idUsuario
                                        ORDER BY e.fecha DESC");
    }

    public function listarEdicionesSuscriptas($idUsuario)
    {
        return $this->database->list("SELECT e.id, e.numero, e.titulo, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.portada, t.tipo, DATE_ADD(us.fecha_inicio, INTERVAL ts.duracion DAY) as vencimiento, us.fecha_inicio
                                        FROM edicion e JOIN producto p on e.id_producto = p.id 
                                        JOIN tipo_producto t on p.id_tipo_producto = t.id                                  
                                        JOIN usuario_suscripcion us on us.id_producto = p.id                                      							
                                            JOIN tipo_suscripcion ts on us.id_suscripcion = ts.id 
              	                        WHERE us.id_usuario = $idUsuario and us.activa = 1 
                                        and DATE_ADD(us.fecha_inicio, INTERVAL ts.duracion DAY) >= now() 
                                        and DATE(e.fecha) BETWEEN DATE(us.fecha_inicio) AND DATE(DATE_ADD(us.fecha_inicio, INTERVAL ts.duracion DAY))
                                        ORDER BY e.fecha DESC");
    }

}