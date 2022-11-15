<?php

class SeccionModel
{
    private $id;
    private $nombre;
    private $descripcion;
    private $database;
    private $logger;

    public function __construct($logger, $database)
    {
        $this->logger = $logger;
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

    public function update()
    {
        $response = $this->database->execute("UPDATE seccion SET nombre = '$this->nombre', descripcion = '$this->descripcion' WHERE id = $this->id");

        $this->logger->info($response);

        if ($response) return array("success" => "La sección se actualizó correctamente",
                                     "seccion" => $this);

                       return array("error" => "No se modificaron datos",
                                     "seccion" => $this);



    }

    public function list()
    {
        return $this->database->list("SELECT * FROM seccion ORDER BY nombre ASC");
    }

    public function getSection($id)
    {
        $query = $this->database->query("SELECT * FROM seccion WHERE id = $id");
        return $this->toSection($query);
    }

    private function toSection($array)
    {
        $this->id = $array['id'];
        $this->nombre = $array['nombre'];
        $this->descripcion = $array['descripcion'];

        return $this;
    }



}