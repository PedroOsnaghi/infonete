<?php

class SeccionModel
{
    private $id;
    private $nombre;
    private $descripcion;
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

//GETTER Y SETTER

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function guardar()
    {
        return $this->database->execute("INSERT INTO seccion (nombre, descripcion) 
                                         VALUES ('$this->nombre', '$this->descripcion')");
    }

    public function list()
    {
        return $this->database->list("SELECT * FROM seccion ORDER BY nombre ASC");
    }


}