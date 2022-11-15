<?php

class SuscripcionModel
{
    const TIPO_MENSUAL = 1;
    const TIPO_SEMESTRAL = 2;
    const TIPO_ANUAL = 3;

    private $id;
    private $descripcion;
    private $duracion;
    private $tag;
    private $precio;
    private $database;

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

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag): void
    {
        $this->tag = $tag;
    }

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function guardar()
    {
        $response = $this->database->execute("INSERT INTO suscripcion (descripcion, id_tipo_suscripcion, tag, precio)
                                        VALUES ('$this->descripcion', $this->duracion, '$this->tag',  $this->precio)");

        if ($response) return array('success' => "La suscripción se guardó correctamente");

        return array('error' => "Hubo un error al guardar la suscripción");

    }

    public function list()
    {
        return $this->database->list("SELECT s.*, t.duracion as 'dias', t.descripcion as 'tipo' FROM suscripcion s JOIN tipo_suscripcion t ON s.id_tipo_suscripcion = t.id ORDER BY s.id ASC");
    }

    public function listTipos()
    {
        return $this->database->list("SELECT id as 'idTipo', duracion as 'dias', descripcion as 'tipo' FROM tipo_suscripcion  ORDER BY  id ASC");
    }
}