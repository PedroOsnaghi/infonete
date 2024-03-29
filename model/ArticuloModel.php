<?php

class ArticuloModel
{
    //CONSTANTES
    const ART_ST_DRAFT = 0;
    const ART_ST_REVISION = 1;
    const ART_ST_APROBADA = 2;
    const ART_ST_PUBLICADO = 3;
    const ART_ST_BAJA = -1;

    //PROPIEDADES
    private $id;
    private $titulo;
    private $subtitulo;
    private $contenido;
    private $link;
    private $linkvideo;
    private $ubicacion;
    private $latitud;
    private $longitud;
    private $create_at;
    private $update_at;
    private $estado;

    private $seccion;
    private $edicion;
    private $autor;
    private $autor_rol;
    private $imagen_autor;

    private $database;
    private $file;
    private $logger;
    private $user_rol;


    public function __construct($file, $logger, $database)
    {
        $this->file = $file;
        $this->logger = $logger;
        $this->database = $database;
    }

    //GETTERS Y SETTERS
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getSubtitulo()
    {
        return $this->subtitulo;
    }

    public function setSubtitulo($subtitulo)
    {
        $this->subtitulo = $subtitulo;
    }

    public function getContenido()
    {
        return $this->contenido;
    }

    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function getLinkvideo()
    {
        return $this->linkvideo;
    }

    public function setLinkvideo($linkvideo)
    {
        $this->linkvideo = $linkvideo;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getUpdateAt()
    {
        return $this->update_at;
    }

    public function setUpdateAt($update_at)
    {
        $this->update_at = $update_at;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;
    }

    public function getLatitud()
    {
        return $this->latitud;
    }

    public function setLatitud($latitud): void
    {
        $this->latitud = $latitud;
    }

    public function getLongitud()
    {
        return $this->longitud;
    }

    public function setLongitud($longitud): void
    {
        $this->longitud = $longitud;
    }

    public function getSeccion()
    {
        return $this->seccion;
    }

    public function setSeccion($seccion)
    {
        $this->seccion = $seccion;
    }

    public function getEdicion()
    {
        return $this->edicion;
    }

    public function setEdicion($edicion)
    {
        $this->edicion = $edicion;
    }

    public function getAutor()
    {
        return $this->autor;
    }

    public function setAutor($autor)
    {
        $this->autor = $autor;
    }

    public function getAutorRol()
    {
        return $this->autor_rol;
    }

    public function setAutorRol($autor_rol): void
    {
        $this->autor_rol = $autor_rol;
    }

    public function getImagenAutor()
    {
        return $this->imagen_autor;
    }

    public function setImagenAutor($imagen_autor): void
    {
        $this->imagen_autor = $imagen_autor;
    }



    //METODOS
    public function guardar()
    {


        $res = $this->database->execute("INSERT INTO articulo(titulo, subtitulo, contenido, link, link_video, ubicacion, latitud, longitud, create_at, id_estado, id_autor) 
                                         VALUES ('$this->titulo', '$this->subtitulo', '$this->contenido', '$this->link', '$this->linkvideo', '$this->ubicacion', $this->latitud, $this->longitud, '$this->create_at', $this->estado, $this->autor)");


        $this->id = $this->database->lastInsertId();

        if ($res) {
            $this->guardarRelacionEdicionSeccion($this->id);
            $this->guardarArchivos($this->id);
            $response = array("success" => "La Nota fue generada con exito");
        } else {
            $response = array("error" => "Ocurrio un error al guardar la Nota");
        }

        return $response;


    }

    public function update()
    {


        $resQuery = $this->database->execute("UPDATE articulo SET titulo= '$this->titulo', 
                                                            subtitulo='$this->subtitulo', 
                                                            contenido='$this->contenido',
                                                            link='$this->link',
                                                            link_video='$this->linkvideo',
                                                            ubicacion='$this->ubicacion',
                                                            latitud=$this->latitud,
                                                            longitud=$this->longitud
                                          WHERE id = $this->id");

        $resRel = $this->actualizarRelacionEdicionSeccion($this->id);
        $resFile = $this->guardarArchivos($this->id);

        if ($resQuery || $resRel || $resFile)
            return array("success" => "La Nota se Actualizó con exito");

        return array("error" => "No se registraron Cambios la Nota");


    }

    public function cambiarEstado($idNota, $estado)
    {
        $res = $this->database->execute("UPDATE articulo SET id_estado = " . $estado . " WHERE id = $idNota");

        return ($res) ? array("state" => $this->getNombreEstado($estado)) : array("state" => false);

    }

    public function list($idEdicion, $user)
    {
        switch ($user->getRol()) {
            case UsuarioModel::ROL_REDACTOR:
                $condicion = 'a.id_estado >= 0 AND a.id_autor='. $user->getId().' AND';
                break;
            case UsuarioModel::ROL_EDITOR:
                $condicion = 'a.id_estado > 0 AND';
                break;
            default:
                $condicion = '';
        }
        return $this->database->list("SELECT a.*, es.id as 'idEstado', es.estado as 'estado', s.nombre as 'seccion', e.id as 'id_edicion' 
                                        FROM articulo a 
                                            JOIN estado_articulo es ON a.id_estado = es.id
                                            JOIN articulo_edicion ae ON a.id = ae.id_articulo
                                            JOIN edicion e ON ae.id_edicion = e.id
                                            JOIN seccion s ON ae.id_seccion = s.id 
                                        WHERE $condicion e.id = $idEdicion");
    }

    public function listBy($idSeccion, $idEdicion){
        return $this->database->list("SELECT a.*, f.nombre as 'thumb'
			                            FROM articulo a 
			                                            JOIN archivo f ON f.id_articulo = a.id
                                                        JOIN articulo_edicion ae ON a.id = ae.id_articulo
                                        WHERE a.id_estado = ".self::ART_ST_PUBLICADO." AND ae.id_seccion = $idSeccion AND ae.id_edicion = $idEdicion  GROUP BY a.id");

    }

    public function getArticulo($id)
    {
        $query = $this->database->query("SELECT a.*,  es.estado as 'estado', ae.* FROM articulo a JOIN estado_articulo es  ON a.id_estado = es.id JOIN articulo_edicion ae ON a.id = ae.id_articulo  WHERE a.id = $id");
        return $this->toArticulo($query);
    }

    public function getArticuloPreview($id)
    {
        $query = $this->database->query("SELECT a.id, a.titulo, a.subtitulo, a.contenido, a.link, a.link_video, a.create_at, a.update_at, a.ubicacion, a.latitud, a.longitud,
	                                               s.nombre as 'nombre_seccion', u.nombre as 'nombre_autor', u.apellido as 'apellido_autor', u.avatar,
	                                               r.rol_name as 'rol_autor', ea.estado 
                                            FROM articulo a JOIN articulo_edicion ae ON a.id = ae.id_articulo
						                                    JOIN seccion s ON ae.id_seccion = s.id
                                                            JOIN usuario u ON a.id_autor = u.id
                                                            JOIN rol r ON u.rol = r.id
                                                            JOIN estado_articulo ea ON a.id_estado = ea.id
                                                            WHERE a.id = $id");
        return $this->toArticuloPreview($query);
    }

    public function getImagenes()
    {
        return $this->file->getFiles("article/$this->id");
    }

    public function getStreamFile()
    {
        return $this->file->getFiles("article/$this->id/stream");
    }

    public function eliminarImagen($idArticulo, $filename)
    {
        $response = $this->file->eliminar("article/$idArticulo/$filename");
        if($response){
             $this->database->execute("DELETE FROM archivo WHERE nombre = '$filename' AND id_articulo = $idArticulo");
             return array("success"=> "El archivo fue eliminado con exito");
        }
        return array("error"=> "No se pudo eliminar el archivo");
    }

    public function eliminarStream($idArticulo, $filename)
    {
        $response = $this->file->eliminar("article/$idArticulo/stream/$filename");
        if($response)
           return array("success"=> "El archivo Stream fue eliminado con exito");

        return array("error"=> "No se pudo eliminar el archivo de Stream");
    }

    private function toArticuloPreview($array)
    {
        $this->id = $array['id'];
        $this->titulo = $array['titulo'];
        $this->subtitulo = $array['subtitulo'];
        $this->contenido = $array['contenido'];
        $this->link = $array['link'];
        $this->linkvideo = $array['link_video'];
        $this->ubicacion = $array['ubicacion'];
        $this->latitud = $array['latitud'];
        $this->longitud = $array['longitud'];
        $this->create_at = Fecha::longDate($array['create_at']);
        $this->update_at = Fecha::longDate($array['update_at']);
        $this->estado = $array['estado'];
        $this->seccion = $array['nombre_seccion'];
        $this->autor = $array['nombre_autor'] . " " . $array['apellido_autor'];
        $this->autor_rol = $array['rol_autor'];
        $this->imagen_autor = $array['avatar'];
        return $this;
    }

    private function toArticulo($array)
    {

        $this->id = $array['id'];
        $this->titulo = $array['titulo'];
        $this->subtitulo = $array['subtitulo'];
        $this->contenido = $array['contenido'];
        $this->link = $array['link'];
        $this->linkvideo = $array['link_video'];
        $this->ubicacion = $array['ubicacion'];
        $this->latitud = $array['latitud'];
        $this->longitud = $array['longitud'];
        $this->create_at = Fecha::longDate($array['create_at']);
        $this->update_at = Fecha::longDate($array['update_at']);
        $this->estado = $array['id_estado'];
        $this->seccion = $array['id_seccion'];
        $this->edicion = $array['id_edicion'];
        $this->autor = $array['id_autor'];
        return $this;
    }

    private function guardarArchivos($id)
    {
        $folder = "article/" . $id;

        $resFile =$this->file->uploadFiles($folder, function ($file){
            $this->guardarDatosArchivo($file['name']);
        });
        $resStr = $this->file->uploadStream($folder);

        if ($resFile || $resStr) return true;
        return false;
    }

    private function guardarDatosArchivo($filename)
    {
        return $this->database->execute("INSERT INTO archivo(id_articulo, id_tipo, nombre) 
                                         VALUES ($this->id, 1 , '$filename')");

    }

    private function guardarRelacionEdicionSeccion($idArticulo)
    {
        return $this->database->execute("INSERT INTO articulo_edicion(id_seccion, id_articulo, id_edicion) 
                                         VALUES ($this->seccion, $idArticulo, $this->edicion)");

    }

    private function actualizarRelacionEdicionSeccion($id)
    {
        return $this->database->execute("UPDATE articulo_edicion SET id_seccion = $this->seccion WHERE id_articulo = $id");
    }

    private function getNombreEstado($id)
    {
        $query = $this->database->query("SELECT estado FROM ESTADO_ARTICULO  WHERE id = $id");
        //["estado" => "Aprobado"]
        return ($query) ? $query["estado"] : false;

    }

}