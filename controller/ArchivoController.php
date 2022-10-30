<?php

class ArchivoController
{
    //CONSTANTES
    const TIPO_FILE_IMAGEN = 1;
    const TIPO_FILE_VIDEO = 2;
    const TIPO_FILE_AUDIO = 3;

    private $archivoModel;
    private $render;

    public function __construct($archivoModel, $render)
    {
        $this->archivoModel = $archivoModel;
        $this->render = $render;
    }

    public function agregar()
    {
        echo $this->render->render("public/view/archivo.mustache");
    }

    public function guardar()
    {
        $this->setearArchivo();

        ($this->archivoModel->guardar()) ?
            $data['success'] = "El archivo se guardó correctamente" :
            $data['error'] = "Hubo un error al guardar el archivo";

        echo $this->render->render("public/view/archivo.mustache", $data);
    }

    private function setearArchivo()
    {
        $this->archivoModel->setNombre($_POST['nombre']);
        $this->archivoModel->setSize($_POST['size']);
        $this->archivoModel->setTipoArchivo($_POST['tipo_archivo']);
    }
}