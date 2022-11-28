<?php

class HomeModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * Metodo que consulta las ediciones publicadas en los ultimos 3 dias.
     *
     * @return array
     */
    public function getNovedades($idUsuario = null)
    {

        $sql = ($idUsuario) ?
            "SELECT m.* ,us.id_usuario as 'suscripcion' FROM (SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.portada, t.tipo , ce.id_usuario AS 'usuario', e.id_producto, e.fecha as 'f'
                                                               FROM edicion e 
                                                                   JOIN producto p on e.id_producto = p.id 
                                                                   JOIN tipo_producto t on p.id_tipo_producto = t.id 
                                                                   LEFT JOIN compra_edicion ce ON ce.id_edicion = e.id  AND ce.id_usuario = $idUsuario
                                                                   WHERE datediff(now(), e.fecha) <= 3 and e.estado =".EdicionModel::ESTADO_PUBLICADO." ORDER BY e.fecha DESC) as m 
            LEFT JOIN usuario_suscripcion us ON  us.id_producto = m.id_producto AND us.id_usuario = $idUsuario AND us.activa = 1 AND DATE(us.fecha_inicio) <= DATE(m.f)"
            :
            "SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.portada, t.tipo 
            FROM edicion e JOIN producto p on e.id_producto = p.id 
										JOIN tipo_producto t on p.id_tipo_producto = t.id 
             WHERE datediff(now(), e.fecha) <= 3 and e.estado =".EdicionModel::ESTADO_PUBLICADO." ORDER BY e.fecha DESC";

        // Retorna las publicaciones de los últimos 3 días
        return $this->database->list($sql);
    }
}