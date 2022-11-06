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
    private $imagen;
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

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function setDatabase($database)
    {
        $this->database = $database;
    }

    public function list()
    {
        return $this->database->list("SELECT p.*, t.tipo FROM producto p JOIN tipo_producto t ON p.id_tipo_producto = t.id ORDER BY t.tipo ASC, p.nombre ASC");
    }

    public function getTipoProductList()
    {
        return $this->database->list("SELECT * FROM tipo_producto ORDER BY id ASC");
    }

    public function guardar()
    {
        return $this->database->execute("INSERT INTO producto(id_tipo_producto, nombre, imagen) 
                                  VALUES($this->tipo, '$this->nombre', '$this->imagen')");
    }

    public function searchList($value)
    {
        return $this->database->list("SELECT p.*, t.tipo FROM producto p JOIN tipo_producto t ON p.id_tipo_producto = t.id WHERE p.nombre LIKE '%$value%' OR t.tipo LIKE '%$value%' ORDER BY t.tipo ASC, p.nombre ASC");
    }

}