<?php

class EdicionModel
{
    const ESTADO_EN_EDICION = 0;
    const ESTADO_PUBLICADO = 1;

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


    //GETTERS Y SETTERS

    private $nombreProducto;
    private $tipoProducto;
    private $logger;
    private $file;


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


    public function guardar()
    {
        $this->guardarPortada();
        return $this->database->execute("INSERT INTO edicion(numero, titulo, descripcion, precio, portada, id_producto, estado) VALUES (" . $this->numero . ", '" . $this->titulo . "', '" . $this->descripcion . "'," . $this->precio . ", '" . $this->portada . "', " . $this->producto . "," . self::ESTADO_EN_EDICION . ")");
    }

    public function listBy($product)
    {
        return $this->database->list("SELECT * FROM edicion WHERE id_producto = $product");
    }

    public function listByState($estado)
    {
        return $this->database->list("SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.estado, e.id_producto, e.portada, p.nombre FROM edicion e JOIN producto p ON e.id_producto = p.id WHERE e.estado = $estado");
    }

    public function listCatalogBy($idProduct, $session)
    {
        if($session->getAuthUser())
        {
            $join = "LEFT JOIN compra_edicion ce 
                     ON ce.id_edicion = e.id 
                     AND ce.id_usuario = " . $session->getAuthUser()->getId();
            $col = ", ce.id_usuario AS 'usuario'";
        }else
        {
            $join = "";
            $col = "";
        }
        return $this->database->list("SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.portada, t.tipo " . $col .
                                        " FROM edicion e JOIN producto p on e.id_producto = p.id 
                                                                    JOIN tipo_producto t on p.id_tipo_producto = t.id "
                                        . $join .
                                        " WHERE p.id= $idProduct AND e.estado =" . self::ESTADO_PUBLICADO .
                                        " ORDER BY e.fecha DESC");
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

    public function update()
    {
        $sql_portada = ($this->verificarCambioPortada()) ? ", portada = '$this->portada'" : "";

        $response = $this->database->execute("UPDATE edicion SET numero = $this->numero, titulo = '$this->titulo', 
                                        descripcion = '$this->descripcion', precio = $this->precio, 
                                        id_producto = $this->producto" . $sql_portada . "  WHERE id = $this->id");

        if ($response) return array("success" => "La edición se actualizó correctamente",
            "edicion" => $this);

        return array("error" => "Hubo un error al actualizar la edición",
            "edicion" => $this);
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

    //devuelve un array con las Ediciones publicadas en los ultimos 3 dias.
    //recibe la session como parametro para verificar si hay compras para el usuario que visita el sitio
    public function getNovedades($session)
    {
        if($session->getAuthUser())
        {
            $join = "LEFT JOIN compra_edicion ce 
                     ON ce.id_edicion = e.id 
                     AND ce.id_usuario = " . $session->getAuthUser()->getId();
            $col = ", ce.id_usuario AS 'usuario'";
        }else
        {
            $join = "";
            $col = "";
        }

        // Retorna las publicaciones de los últimos 3 días
        return $this->database->list("SELECT e.id, e.numero, e.titulo, e.descripcion, e.precio, DATE_FORMAT(e.fecha, '%d de %b del %Y') as 'fecha', e.portada, t.tipo " . $col .
                                      " FROM edicion e JOIN producto p on e.id_producto = p.id 
										JOIN tipo_producto t on p.id_tipo_producto = t.id "
										. $join .
                                        " WHERE datediff(now(), e.fecha) <= 3 and e.estado =" . self::ESTADO_PUBLICADO .
                                        " ORDER BY e.fecha DESC");
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