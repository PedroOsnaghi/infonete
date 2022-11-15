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
    private $create_at;
    private $update_at;
    private $estado;
    private $nombreEstado;

    private $seccion;
    private $edicion;
    private $autor;


    private $database;
    private $file;
    private $logger;

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

    public function getNombreEstado()
    {
        return $this->nombreEstado;
    }

    public function setNombreEstado($nombreEstado)
    {
        $this->nombreEstado = $nombreEstado;
    }

    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;
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



    public function guardar()
    {
        $this->logger->info("entro en Guardar Articulo");

        $res = $this->database->execute("INSERT INTO articulo(titulo, subtitulo, contenido, link, link_video, ubicacion, create_at, id_estado, id_autor) 
                                         VALUES ('$this->titulo', '$this->subtitulo', '$this->contenido', '$this->link', '$this->linkvideo', '$this->ubicacion', '$this->create_at', $this->estado, $this->autor)");

        $this->logger->info("respuesta guardar artivculo: " .  $res);

        $idArticulo = $this->database->lastInsertId();

        if($res){
            $this->guardarRelacionEdicionSeccion($idArticulo);
            $this->guardarArchivos($idArticulo);
            $response = array("success" => "La Nota fue generada con exito");
        }else{
            $response = array("error" => "Ocurrio un error al guardar la Nota");
        }

        return $response;



    }

    public function solicitarRevision($id)
    {
        $res = $this->database->execute("UPDATE articulo SET id_estado = " . self::ART_ST_REVISION . " WHERE id = $id");


        if($res){
            $response = array("state" => "En Revision");
        }else{
            $response = array("state" => false);
        }

        return $response;



    }


    public function listBy($idEdicion)
    {
        return $this->database->list("SELECT a.*, es.id as 'idEstado', es.estado as 'nombreEstado', s.nombre as 'seccion', e.id as 'id_edicion' FROM articulo a JOIN estado_articulo es JOIN articulo_edicion ae JOIN edicion e JOIN seccion s ON a.id_estado = es.id AND a.id = ae.id_articulo AND ae.id_edicion = e.id AND ae.id_seccion = s.id  WHERE e.id = $idEdicion");
    }

    public function getArticle($id)
    {
        $query = $this->database->query("SELECT a.*,  es.estado as 'nombreEstado', ae.* FROM articulo a JOIN estado_articulo es  ON a.id_estado = es.id JOIN articulo_edicion ae ON a.id = ae.id_articulo  WHERE a.id = $id");
        return $this->toArticle($query);
    }

    private function toArticle($array)
    {

        $this->id = $array['id'];
        $this->titulo = $array['titulo'];
        $this->subtitulo = $array['subtitulo'];
        $this->contenido = $array['contenido'];
        $this->link = $array['link'];
        $this->linkvideo = $array['link_video'];
        $this->ubicacion = $array['ubicacion'];
        $this->create_at = $array['create_at'];
        $this->update_at = $array['update_at'];
        $this->estado = $array['id_estado'];
        $this->nombreEstado = $array['nombreEstado'];
        $this->seccion = $array['id_seccion'];
        $this->edicion = $array['id_edicion'];
        $this->autor = $array['id_autor'];
        return $this;
    }

    private function guardarArchivos($id)
    {
        $folder = "article/" . $id;
        $this->logger->info($folder);
        $this->file->uploadFiles($folder);
        $this->file->uploadStream($folder);
    }

    private function guardarRelacionEdicionSeccion($idArticulo)
    {
        $res = $this->database->execute("INSERT INTO articulo_edicion(id_seccion, id_articulo, id_edicion) 
                                         VALUES ($this->seccion, $idArticulo, $this->edicion)");

    }


}