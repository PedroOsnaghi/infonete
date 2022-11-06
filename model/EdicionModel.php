<?php

class EdicionModel
{
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
        return $this->database->execute("INSERT INTO edicion(numero, titulo, descripcion, precio, portada, id_producto) VALUES (" . $this->numero . ", '" . $this->titulo . "', '" . $this->descripcion . "'," . $this->precio . ", '" . $this->portada . "', ". $this->producto . ")");
    }

    public function listBy($product)
    {
        return $this->database->list("SELECT * FROM edicion WHERE id_producto = $product");
    }
}