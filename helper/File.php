<?php

class File
{
    const UPLOAD_STATE_NO_FILE = 0;
    const UPLOAD_STATE_OK = 1;
    const UPLOAD_STATE_ERROR = -1;

    private $uploadDir;
    private $fileName;
    private $logger;

    public function __construct($logger, $uploadDir = "uploads"){
        $this->uploadDir = $uploadDir;
        $this->logger = $logger;
    }

    /**
     * Subida de archivo único
     * Requiere que el name del input sea 'file'
     *
     * @param String $folder nombre de la carpeta donde se guarda el archivo
     * @return int 1:subuda correcta
     */
    public  function uploadFile($folder = '')
    {
        if(isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK){
            $this->fileName = $_FILES['file']['name'];

            $directorio = $this->verificarDirectorio($folder);


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

    //prototipo: function callback($dato)


    /**
     *
     * @param callable $callback($data) funcion que recibe por parametro los datos del archivo guardado
     * **/

    public function uploadFiles($folder = '', callable $callback = null)
    {
        if(isset($_FILES['file'])){
            $directorio = $this->verificarDirectorio($folder);


            foreach ($_FILES['file']['tmp_name'] as $key => $file){

                //movemos de temporal a fisico
                $ruta = $directorio . "/" . $_FILES['file']['name'][$key];


                $dataFile = (move_uploaded_file($file, $ruta )) ?
                    $this->getDataFile($key, self::UPLOAD_STATE_OK):
                    $this->getDataFile($key, self::UPLOAD_STATE_ERROR);

                if($callback != null) $callback($dataFile);
            }
            return self::UPLOAD_STATE_OK;
        }else{
            return self::UPLOAD_STATE_NO_FILE;
        }


    }

    public function uploadStream($folder)
    {
        if(isset($_FILES['video']) && $_FILES['video']['error'] == UPLOAD_ERR_OK){
            $this->fileName = uniqid() . ".webm";

            $directorio = $this->verificarDirectorio($folder . "/stream");


            //movemos de temporal a fisico
            $ruta = $directorio . $this->fileName;


            if(move_uploaded_file($_FILES['video']['tmp_name'], $ruta )){
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

    public function getFiles($folder)
    {
        $arrFiles = array();
        $fulldir = dirname(__FILE__,2) . "/public/uploads/$folder";


        //verifica que exista el directorio o retorna false
        if(file_exists($fulldir)){
            $files = new FilesystemIterator($fulldir);
        }else{
            return false;
        }


        //carga arreglo de archivos
        foreach($files as $file) {
            if(is_file($file)){
                $nombre = array("archivo" => $file->getFilename());
                array_push($arrFiles, $nombre );


                //[{"archivo"=>"aaa.jpg"},{"archivo"=>"bb.jpg"}]
            }
        }

        return $arrFiles;
    }

    public function eliminar($file)
    {
        $fulldir = dirname(__FILE__,2) . "/public/uploads/$file";

        if(unlink($fulldir)) return array("status" => "archivo eliminado");

        return array("status" => "No se pudo eliminar el archivo");
    }

    private function verificarDirectorio($folder)
    {
        $dir = $this->uploadDir . (empty($folder) ? '/' : "/" .  $folder  . "/");


            if(!file_exists($dir)) {
                if (!mkdir($dir, 0777, true)){
                    $this->logger->error("No se creo el directorio: " . $dir);
                    return false;
                }else{
                    $this->logger->info("Se creo el directorio:" . $dir);
                    opendir($dir);
                }


            }

        return $dir;
    }

    private function getDataFile($key, $stateResult)
    {
        return array("name" => $_FILES["file"]["name"][$key],
                     "size" =>  $_FILES["file"]["size"][$key],
                    "type" => $_FILES["file"]["type"][$key],
                    "error" => $stateResult);
    }
}