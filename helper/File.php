<?php

class File
{
    const UPLOAD_STATE_NO_FILE = 0;
    const UPLOAD_STATE_OK = 1;
    const UPLOAD_STATE_ERROR = -1;

    private $uploadDir;
    private $fileName;

    public function __construct($uploadDir = "uploads"){
        $this->uploadDir = $uploadDir;
    }

    /**
     * Subida de archivo único
     * Requiere que el name del input sea 'file'
     *
     * @param String $folder nombre de la carpeta donde se guarda el archivo
     * @return int 1:subuda correcta
     */
    public  function uploadFile($folder = ''){
        if(isset($_FILES) && $_FILES['file']['error'] == UPLOAD_ERR_OK){
            $this->fileName = $_FILES['file']['name'];

            //verificamos que exista directorio
            //sino se crea
            $directorio = $this->uploadDir . (empty($folder) ? '/' : "/" .  $folder  . "/");
            if(!file_exists($directorio))
                mkdir($directorio,0777);


            //movemos de temporal a fisico
            $ruta = $directorio . "/" . $_FILES['file']['name'];
            if(move_uploaded_file($_FILES['file']['tmp_name'], $ruta )){
                   $response = self::UPLOAD_STATE_OK;
            }else{
                 $response = self::UPLOAD_STATE_ERROR;
            }


        }else{
            $response = self::UPLOAD_STATE_NO_FILE;
        }

        return $response;
    }

    public function get_file_uploaded(){
        return $this->fileName;
    }

}