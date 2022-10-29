<?php

class ArchivoController
{
    //CONSTANTES
    const TIPO_FILE_IMAGEN = 1;
    const TIPO_FILE_VIDEO = 2;
    const TIPO_FILE_AUDIO = 3;

    //PROPIEDADES
    private $id;
    private $tipo;
    private $nombre;
    private $size;
    private $articulo; //Relacion con Articulo
}