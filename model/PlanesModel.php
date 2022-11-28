<?php

class PlanesModel
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
    private $producto;

    //GETTERS ANS SETTERS
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

    public function getProducto()
    {
        return $this->producto;
    }

    public function setProducto($producto): void
    {
        $this->producto = $producto;
    }


    public function __construct($database)
    {
        $this->database = $database;
    }


    //METODOS
    public function guardar()
    {
        $response = $this->database->execute("INSERT INTO suscripcion (descripcion, id_tipo_suscripcion, tag, precio)
                                        VALUES ('$this->descripcion', $this->duracion, '$this->tag',  $this->precio)");

        return ($response) ? array('success' => "La suscripción se guardó correctamente") :
                             array('error' => "Hubo un error al guardar la suscripción");

    }

    public function list()
    {
        return $this->database->list("SELECT s.*, t.duracion as 'dias', t.descripcion as 'tipo' FROM suscripcion s JOIN tipo_suscripcion t ON s.id_tipo_suscripcion = t.id ORDER BY s.id ASC");
    }

    public function listTipos()
    {
        return $this->database->list("SELECT id as 'idTipo', duracion as 'dias', descripcion as 'tipo' FROM tipo_suscripcion  ORDER BY  id ASC");
    }

    public function listProductosDisponibles($idUsuario, $idSuscripcion)
    {
        return $this->database->list("SELECT p.*, t.tipo FROM producto p 
                                        JOIN tipo_producto t ON p.id_tipo_producto = t.id 
                                        WHERE p.id NOT IN(SELECT id_producto FROM usuario_suscripcion WHERE id_suscripcion = $idSuscripcion AND id_usuario = $idUsuario)
											
                                        ORDER BY t.tipo ASC, p.nombre ASC");
    }

    public function getPlan($idSuscripcion)
    {
        $query = $this->database->query("SELECT s.id, s.descripcion, s.tag, s.precio, t.id as 'id_tipo',
                                        t.duracion,t.descripcion as 'tipo'
                                        FROM suscripcion as s
                                        JOIN tipo_suscripcion as t 
                                        ON s.id_tipo_suscripcion = t.id
                                        WHERE s.id = $idSuscripcion");

        return $this->toPlan($query);
    }

    public function generarSuscripcion($idSuscripcion, $idProducto)
    {
        $query_plan = $this->database->query("SELECT s.id, s.descripcion, s.tag, s.precio, t.id as 'id_tipo',
                                        t.duracion,t.descripcion as 'tipo'
                                        FROM suscripcion as s
                                        JOIN tipo_suscripcion as t 
                                        ON s.id_tipo_suscripcion = t.id
                                        WHERE s.id = $idSuscripcion");

        $query_product = $this->database->query("SELECT p.*, t.tipo FROM producto p JOIN tipo_producto t ON p.id_tipo_producto = t.id WHERE p.id = $idProducto");

        return $this->toPlan($query_plan, $query_product);
    }

    private function toPlan($query, $prod = null)
    {
        if($query == null) return null;

        $this->id = $query['id'];
        $this->descripcion = $query['descripcion'];
        $this->tag = $query['tag'];
        $this->precio = $query['precio'];
        $this->id_tipo = $query['id_tipo'];
        $this->duracion = $query['duracion'];
        $this->tipo = $query['tipo'];
        $this->producto = $prod;

        return $this;
    }
}