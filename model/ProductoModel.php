<?php

class ProductoModel
{
    //CONSTANTES DE TIPO
    const TIPO_DIARIO = 1;
    const TIPO_REVISTA = 2;

    //PROPIEDADES
    private $id;
    private $tipo;
    private $nombre;
    private $portada;
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    //GETTERS AND SETTERS
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getPortada()
    {
        return $this->portada;
    }

    public function setPortada($portada)
    {
        $this->portada = $portada;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function setDatabase($database)
    {
        $this->database = $database;
    }

    public function guardar()
    {
        return $this->database->execute("INSERT INTO producto(id_tipo_producto, nombre, portada) 
                                  VALUES($this->tipo, '$this->nombre', '$this->portada')");
    }

}