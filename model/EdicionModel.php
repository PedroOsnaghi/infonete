<?php

class EdicionModel
{
    const ESTADO_EN_EDICION = 0;
    const ESTADO_PUBLICADO = 1;
    const ESTADO_ALL = 2;

    private $id;
    private $numero;
    private $precio;
    private $fecha;
    private $estado;
    private $producto;
    private $titulo;
    private $portada;
    private $descripcion;
    private $database;

    private $nombreProducto;
    private $tipoProducto;
    private $logger;
    private $file;

    //GETTERS Y SETTERS
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function getProducto()
    {
        return $this->producto;
    }

    public function setProducto($producto)
    {
        $this->producto = $producto;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getPortada()
    {
        return $this->portada;
    }

    public function setPortada($portada)
    {
        $this->portada = $portada;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getNombreProducto()
    {
        return $this->nombreProducto;
    }

    public function setNombreProducto($nombreProducto)
    {
        $this->nombreProducto = $nombreProducto;
    }

    public function getTipoProducto()
    {
        return $this->tipoProducto;
    }

    public function setTipoProducto($tipoProducto): void
    {
        $this->tipoProducto = $tipoProducto;
    }


    public function __construct($logger, $file, $database)
    {
        $this->logger = $logger;
        $this->file = $file;
        $this->database = $database;
    }


    //METODOS
    public function guardar()
    {
        $this->guardarPortada();

        $res = $this->database->execute("INSERT INTO edicion(numero, titulo, descripcion, precio, portada, id_producto, estado) VALUES (" . $this->numero . ", '" . $this->titulo . "', '" . $this->descripcion . "'," . $this->precio . ", '" . $this->portada . "', " . $this->producto . "," . self::ESTADO_EN_EDICION . ")");

        return ($res) ? array('success' => "La edición se guardó correctamente") :
                        array('error' => "Hubo un error al guardar la edición");

    }

    public function update()
    {
        $sql = ($this->verificarCambioPortada()) ?
            "UPDATE edicion SET numero = $this->numero,
                                titulo = '$this->titulo', 
                           descripcion = '$this->descripcion', 
                                precio = $this->precio,
                               portada = '$this->portada',
                           id_producto = $this->producto WHERE id = $this->id"
            :
            "UPDATE edicion SET numero = $this->numero,
                                titulo = '$this->titulo', 
                           descripcion = '$this->descripcion', 
                                precio = $this->precio, 
                           id_producto = $this->producto WHERE id = $this->id";

        $response = $this->database->execute($sql);

        return ($response) ? array("success" => "La edición se actualizó correctamente", "edicion" => $this):
            array("error" => "Hubo un error al actualizar la edición", "edicion" => $this);
    }

    public function listarPorProducto($idProduct)
    {
        return $this->database->list("SELECT * FROM edicion WHERE id_producto = $idProduct");
    }

    public function listByState($estado = self::ESTADO_ALL)
    {
        $sql = ($estado == self::ESTADO_ALL) ?
            "SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.estado, e.id_producto, e.portada, p.nombre FROM edicion e JOIN producto p ON e.id_producto = p.id "
            :
            "SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.estado, e.id_producto, e.portada, p.nombre FROM edicion e JOIN producto p ON e.id_producto = p.id WHERE e.estado = $estado";

        return $this->database->list($sql);
    }

    public function getEdition($id)
    {
        $query = $this->database->query("SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, e.fecha, e.estado, e.id_producto, e.portada, p.nombre as 'nombre_producto', t.tipo 
                                            FROM edicion e 
                                                JOIN producto p ON e.id_producto = p.id 
                                                JOIN tipo_producto t ON p.id_tipo_producto = t.id
                                            WHERE e.id = $id");
        return $this->toEdition($query);
    }

    public function getCompra($id, $idUsuario)
    {
        $query = $this->database->query("SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, e.fecha, e.estado, e.id_producto, e.portada, p.nombre as 'nombre_producto', t.tipo 
                                            FROM edicion e 
                                                JOIN producto p ON e.id_producto = p.id 
                                                JOIN tipo_producto t ON p.id_tipo_producto = t.id
                                                JOIN compra_edicion ce ON e.id = ce.id_edicion AND ce.id_usuario = $idUsuario 
                                            WHERE e.id = $id");
        return $this->toEdition($query);
    }

    public function getSuscripcion($id, $idUsuario)
    {
        $query = $this->database->query("SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, e.fecha, e.estado, e.id_producto, e.portada, p.nombre as 'nombre_producto', t.tipo 
                                        FROM edicion e JOIN producto p on e.id_producto = p.id 
                                        JOIN tipo_producto t on p.id_tipo_producto = t.id                                  
                                        JOIN usuario_suscripcion us on us.id_producto = p.id                                      							
                                        JOIN tipo_suscripcion ts on us.id_suscripcion = ts.id 
              	                        WHERE us.id_usuario = $idUsuario and us.activa = 1 
                                        and DATE_ADD(us.fecha_inicio, INTERVAL ts.duracion DAY) >= now() 
                                        and DATE(e.fecha) BETWEEN DATE(us.fecha_inicio) AND DATE(DATE_ADD(us.fecha_inicio, INTERVAL ts.duracion DAY)) 
              	                        and e.id = $id                    
                                        ORDER BY e.fecha DESC");
        return $this->toEdition($query);
    }

    public function publicar($id)
    {
        $time = date("Y-m-d h:m");
        // Publicación de edición
        $this->database->execute("UPDATE edicion SET estado = " . self::ESTADO_PUBLICADO . ", fecha ='" . $time . "' WHERE id = $id");

        // Publicación de artículos aprobados
        $this->database->execute("UPDATE articulo a, articulo_edicion ae SET a.id_estado = " . ArticuloModel::ART_ST_PUBLICADO . ", a.update_at = now() WHERE a.id = ae.id_articulo and a.id_estado = " . ArticuloModel::ART_ST_APROBADA . " and ae.id_edicion = $id;");
        return array("publicado" => self::ESTADO_PUBLICADO,
            "date" => $time);
    }

    public function despublicar($id)
    {
        $this->database->execute("UPDATE edicion SET estado = " . self::ESTADO_EN_EDICION . ", fecha = null WHERE id = $id");

        // Despublicación de artículos publicados
        $this->database->execute("UPDATE articulo a, articulo_edicion ae SET a.id_estado = " . ArticuloModel::ART_ST_APROBADA . ", a.update_at = null WHERE a.id = ae.id_articulo and a.id_estado = " . ArticuloModel::ART_ST_PUBLICADO . " and ae.id_edicion = $id;");

        return array("publicado" => self::ESTADO_EN_EDICION);
    }

    public function listByProduct($idProduct, $idUser = null, $searchValue)
    {

        $sql = ($idUser) ?
            "SELECT m.* ,us.id_usuario as 'suscripcion' FROM (SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.portada, t.tipo, ce.id_usuario AS 'usuario', e.id_producto, e.fecha as 'f'
             FROM edicion e 
                            JOIN producto p on e.id_producto = p.id 
                            JOIN tipo_producto t on p.id_tipo_producto = t.id
                             LEFT JOIN compra_edicion ce ON ce.id_edicion = e.id AND ce.id_usuario = $idUser
             WHERE p.id= $idProduct AND e.estado = ".self::ESTADO_PUBLICADO." AND (e.numero LIKE '%$searchValue%' OR e.titulo LIKE '%$searchValue%') ORDER BY e.fecha DESC) as m 
            LEFT JOIN usuario_suscripcion us ON  us.id_producto = m.id_producto AND us.id_usuario = $idUser AND us.activa = 1 AND DATE(us.fecha_inicio) <= DATE(m.f)"
            :
            "SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.portada, t.tipo 
             FROM edicion e 
                                        JOIN producto p on e.id_producto = p.id 
                                        JOIN tipo_producto t on p.id_tipo_producto = t.id 
             WHERE p.id= $idProduct AND e.estado = ".self::ESTADO_PUBLICADO." AND (e.numero LIKE '%$searchValue%' OR e.titulo LIKE '%$searchValue%') ORDER BY e.fecha DESC";


        return $this->database->list($sql);
    }

    private function toEdition($array)
    {
        if ($array == null) return null;

        $this->id = $array['id'];
        $this->numero = $array['numero'];
        $this->titulo = $array['titulo'];
        $this->descripcion = $array['descripcion'];
        $this->precio = $array['precio'];
        $this->fecha = Fecha::longDate($array['fecha']);
        $this->estado = $array['estado'];
        $this->producto = $array['id_producto'];
        $this->nombreProducto = $array['nombre_producto'] ?? null;
        $this->tipoProducto = $array['tipo'] ?? null;
        $this->portada = $array['portada'];
        return $this;
    }

    private function guardarPortada()
    {
        $this->portada = ($this->file->uploadFile("portada") == File::UPLOAD_STATE_OK) ?
            $this->file->get_file_uploaded() :
            "default.jpg";
    }

    private function verificarCambioPortada()
    {
        if ($this->file->uploadFile("portada") > File::UPLOAD_STATE_NO_FILE) {
            $this->portada = $this->file->get_file_uploaded();
            return true;
        }
        return false;
    }
}