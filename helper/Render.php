<?php

class Render{
    private $mustache;
    private $pdf;

    public function __construct($partialsPathLoader, $pdf){
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            array(
            'partials_loader' => new Mustache_Loader_FilesystemLoader( $partialsPathLoader )
        ));
        $this->pdf = $pdf;
    }

    public function render($contentFile , $data = array() ){
        $contentAsString =  file_get_contents($contentFile);
        return  $this->mustache->render($contentAsString, $data);
    }

    public function pdf($contentFile, $data = array(), $fileName)
    {
        $contentAsString =  file_get_contents($contentFile);
        $this->pdf->loadHtml($this->mustache->render($contentAsString, $data));
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        $this->pdf->stream($fileName);
    }
}