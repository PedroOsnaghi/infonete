<?php

class ProductoModel
{
    //CONSTANTES DE TIPO
    const TIPO_DIARIO = 1;
    const TIPO_REVISTA = 2;

    //PROPIEDADES
    private $id;
    private $tipo;
    private $nombre_Tipo;
    private $nombre;
    private $descripcion;
    private $imagen;
    private $database;
    private $file;

    public function __construct($file, $database)
    {
        $this->file = $file;
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

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    public function getNombreTipo()
    {
        return $this->nombre_Tipo;
    }

    public function setNombreTipo($nombre_Tipo)
    {
        $this->nombre_Tipo = $nombre_Tipo;
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
        return $this->database->list("SELECT p.*, t.tipo, COUNT(e.id_producto) as 'ediciones' FROM producto p 
                                        JOIN tipo_producto t ON p.id_tipo_producto = t.id 
                                        LEFT JOIN edicion e ON e.id_producto = p.id
                                        GROUP BY e.id_producto               
                                        ORDER BY t.tipo ASC, p.nombre ASC");
    }

    public function listProductosDisponibles($idUsuario, $idSuscripcion)
    {
        return $this->database->list("SELECT p.*, t.tipo FROM producto p 
                                        JOIN tipo_producto t ON p.id_tipo_producto = t.id 
                                        WHERE p.id NOT IN(SELECT id_producto FROM usuario_suscripcion WHERE id_suscripcion = $idSuscripcion AND id_usuario = $idUsuario)
											
                                        ORDER BY t.tipo ASC, p.nombre ASC");
    }

    public function getTipoProductList()
    {
        return $this->database->list("SELECT * FROM tipo_producto ORDER BY id ASC");
    }

    public function guardar()
    {
        $this->guardarImagen();
        $res = $this->database->execute("INSERT INTO producto(id_tipo_producto, nombre, descripcion, imagen) 
                                  VALUES($this->tipo, '$this->nombre', '$this->descripcion', '$this->imagen')");

        return $res ? array("success" => "El producto se guardó con exito.") : array("error" => "No se pudo agregar el producto");
    }

    public function search($value)
    {
        return $this->database->list("SELECT p.*, t.tipo FROM producto p JOIN tipo_producto t ON p.id_tipo_producto = t.id WHERE p.nombre LIKE '%$value%' OR t.tipo LIKE '%$value%' ORDER BY t.tipo ASC, p.nombre ASC");
    }

    public function getProduct($id)
    {
        $query = $this->database->query("SELECT p.*, t.tipo FROM producto p JOIN tipo_producto t ON p.id_tipo_producto = t.id WHERE p.id = $id");
        return $this->toProduct($query);
    }

    public function update()
    {
        $sql_imagen = ($this->verificarCambioImagen()) ? ", imagen = '$this->imagen' " : "";
        $res = $this->database->execute("UPDATE producto SET id_tipo_producto = $this->tipo, 
                                        nombre = '$this->nombre',
                                        descripcion = '$this->descripcion'
                                        $sql_imagen
                                        WHERE id = $this->id");

        return $res ? array("success" => "El producto se actualizó con exito.") : array("error" => "No se realizaron cambios en el producto");
    }

    private function toProduct($array)
    {
        $this->id = $array['id'];
        $this->tipo = $array['id_tipo_producto'];
        $this->nombre_Tipo = $array['tipo'];
        $this->nombre = $array['nombre'];
        $this->descripcion = $array['descripcion'];
        $this->imagen = $array['imagen'];

        return $this;
    }

    private function guardarImagen()
    {
        $this->imagen = ($this->file->uploadFile("product") == File::UPLOAD_STATE_OK) ?
            $this->file->get_file_uploaded() :
            "default.jpg";
    }

    private function verificarCambioImagen()
    {
        if ($this->file->uploadFile("product") > File::UPLOAD_STATE_NO_FILE) {
            $this->imagen = $this->file->get_file_uploaded();
            return true;
        }
        return false;
    }

}