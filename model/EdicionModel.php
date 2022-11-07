<?php

class EdicionModel
{
    const ESTADO_EN_EDICION = 0;
    const ESTADO_PUBLICADO = 1;

    private $id;
    private $numero;
    private $precio;
    private $fecha;
    private $producto;
    private $titulo;
    private $portada;
    private $descripcion;
    private $database;


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



    public function __construct($database)
    {
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
}