<?php

class ArticuloController
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


}