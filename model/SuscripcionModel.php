<?php

class SuscripcionModel
{
    const TIPO_MENSUAL = 1;
    const TIPO_SEMESTRAL = 2;
    const TIPO_ANUAL = 3;

    private $id;
    private $descripcion;
    private $duracion;
    private $precio;
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

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function getDuracion()
    {
        return $this->duracion;
    }

    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function crear()
    {
        return $this->database->execute("INSERT INTO suscripcion (descripcion, id_tipo_suscripcion, precio)
                                        VALUES ('$this->descripcion', $this->duracion, $this->precio)");
    }

    public function listar()
    {
        return $this->database->list("SELECT s.*, t.duracion FROM suscripcion s JOIN tipo_suscripcion t ON s.id_tipo_suscripcion = t.id ORDER BY s.id ASC");
    }
}