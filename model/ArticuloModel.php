<?php

class ArticuloModel
{
    //CONSTANTES
    const ART_ST_DRAFT = 1;
    const ART_ST_A_PUBLICAR = 2;
    const ART_ST_PUBLICADO = 3;
    const ART_ST_BAJA = 0;

    //PROPIEDADES
    private $id;
    private $titulo;
    private $subtitulo;
    private $contenido;
    private $link;
    private $linkvideo;
    private $create_at;
    private $update_at;
    private $estado;
    private $database;

    public function __construct($database)
    {
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

    public function guardar()
    {
        return $this->database->execute("INSERT INTO articulo(titulo, subtitulo, contenido, link, link_video, create_at, id_estado, update_at) 
                                         VALUES ('$this->titulo', '$this->subtitulo', '$this->contenido', '$this->link', '$this->linkvideo', '$this->create_at', $this->estado, '$this->update_at')");
    }

    public function listBy($idEdicion)
    {
        return $this->database->list("SELECT a.*, es.estado, s.nombre as 'seccion', e.id as 'id_edicion' FROM articulo a JOIN estado_articulo es JOIN articulo_edicion ae JOIN edicion e JOIN seccion s ON a.id_estado = es.id AND a.id = ae.id_articulo AND ae.id_edicion = e.id AND ae.id_seccion = s.id  WHERE e.id = $idEdicion");
    }

}