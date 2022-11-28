<?php

class SuscripcionModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function listarSuscripcionesUsuario($idUsuario)
    {
        return $this->database->list("SELECT DATE_FORMAT(us.fecha_inicio, '%d de %b del %Y') as 'fecha_inicio', DATE_FORMAT(DATE_ADD(us.fecha_inicio, INTERVAL ts.duracion DAY), '%d de %b del %Y') as 'fecha_vencimiento',
                                    p.nombre, p.imagen, tp.tipo, us.activa as 'estado', s.descripcion, s.tag, s.precio, ts.duracion, s.id as 'ids', p.id as 'idp'
                                    FROM usuario_suscripcion us JOIN producto p on us.id_producto = p.id
                                    JOIN tipo_producto tp on p.id_tipo_producto = tp.id       
                                    JOIN suscripcion s on us.id_suscripcion = s.id
                                    JOIN tipo_suscripcion ts on s.id_tipo_suscripcion = ts.id 
                                    WHERE us.id_usuario = $idUsuario
                                    ORDER BY us.activa DESC, us.fecha_inicio DESC");
    }

    public function cancelarSuscripcion($idSuscripcion, $idProducto, $idUsuario)
    {
        return $this->database->execute("UPDATE usuario_suscripcion SET activa = 0 
                                        WHERE id_suscripcion = $idSuscripcion 
                                        and id_producto = $idProducto
                                        and id_usuario = $idUsuario");
    }

}