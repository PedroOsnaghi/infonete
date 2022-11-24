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
    private $id_tipo;
    private $tipo;

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

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function getIdTipo()
    {
        return $this->id_tipo;
    }

    public function setIdTipo(mixed $id_tipo)
    {
        $this->id_tipo = $id_tipo;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo(mixed $tipo)
    {
        $this->tipo = $tipo;
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

    public function getSuscripcion($idSuscripcion)
    {
        $query = $this->database->query("SELECT s.id, s.descripcion, s.tag, s.precio, t.id as 'id_tipo',
                                        t.duracion,t.descripcion as 'tipo'
                                        FROM suscripcion as s
                                        JOIN tipo_suscripcion as t 
                                        ON s.id_tipo_suscripcion = t.id
                                        WHERE s.id = $idSuscripcion");

        return $this->toSuscripcion($query);
    }

    private function toSuscripcion($query)
    {
        if($query == null) return null;

        $this->id = $query['id'];
        $this->descripcion = $query['descripcion'];
        $this->tag = $query['tag'];
        $this->precio = $query['precio'];
        $this->id_tipo = $query['id_tipo'];
        $this->duracion = $query['duracion'];
        $this->tipo = $query['tipo'];

        return $this;
    }
}