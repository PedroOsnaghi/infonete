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
    private $logger;

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





    public function __construct($logger, $database)
    {
        $this->logger = $logger;
        $this->database = $database;
    }


    public function guardar()
    {
        return $this->database->execute("INSERT INTO edicion(numero, titulo, descripcion, precio, portada, id_producto, estado) VALUES (" . $this->numero . ", '" . $this->titulo . "', '" . $this->descripcion . "'," . $this->precio . ", '" . $this->portada . "', ". $this->producto . "," . self::ESTADO_EN_EDICION . ")");
    }

    public function listBy($product)
    {
        return $this->database->list("SELECT * FROM edicion WHERE id_producto = $product");
    }

    public function listByState($estado)
    {
        return $this->database->list("SELECT e.*, p.nombre FROM edicion e JOIN producto p ON e.id_producto = p.id WHERE e.estado = $estado");
    }

    public function getEdition($id)
    {
        $query = $this->database->query("SELECT e.*, p.nombre as 'nombre_producto' FROM edicion e JOIN producto p ON e.id_producto = p.id WHERE e.id = $id");
        return $this->toEdition($query);
    }

    public function update()
    {
        return $this->database->execute("UPDATE edicion SET numero = $this->numero, titulo = '$this->titulo', 
                                        descripcion = '$this->descripcion', precio = $this->precio, 
                                        portada = '$this->portada', id_producto = $this->producto
                                        WHERE id = $this->id");
    }

    public function publicar($id)
    {
        $time = date("Y-m-d h:m");

        $this->database->execute("UPDATE edicion SET estado = " . self::ESTADO_PUBLICADO . ", fecha ='" . $time . "' WHERE id = $id");

        return array("publicado" => self::ESTADO_PUBLICADO,
                     "date" => $time);
    }

    public function despublicar($id)
    {
        $this->database->execute("UPDATE edicion SET estado = " . self::ESTADO_EN_EDICION . ", fecha = null WHERE id = $id");
        return array("publicado" => self::ESTADO_EN_EDICION);
    }

    private function toEdition($array)
    {
        $this->id = $array['id'];
        $this->numero = $array['numero'];
        $this->titulo = $array['titulo'];
        $this->descripcion = $array['descripcion'];
        $this->precio = $array['precio'];
        $this->fecha = $array['fecha'];
        $this->estado = $array['estado'];
        $this->producto = $array['id_producto'];
        $this->nombreProducto = $array['nombre_producto'];
        $this->portada = $array['portada'];
        return $this;
    }


}