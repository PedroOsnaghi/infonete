<?php

class EdicionModel
{
    private $id;
    private $numero;
    private $precio;
    private $fecha;
    private $producto;
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

    public function __construct($database)
    {
        $this->database = $database;
    }


    public function guardar()
    {
        return $this->database->execute("INSERT INTO edicion (numero, precio, fecha, id_producto) VALUES ($this->numero, $this->precio, '$this->fecha', $this->producto)");
    }
}