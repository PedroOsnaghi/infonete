<?php

class ArchivoModel
{
    private $id;
    private $nombre;
    private $size;
    private $tipo_archivo;
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

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

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getTipoArchivo()
    {
        return $this->tipo_archivo;
    }

    public function setTipoArchivo($tipo_archivo)
    {
        $this->tipo_archivo = $tipo_archivo;
    }

    public function guardar()
    {
        return $this->database->execute("INSERT INTO archivo(nombre, size, id_tipo) 
                                         VALUES('$this->nombre', '$this->size', '$this->tipo_archivo')");
    }
}