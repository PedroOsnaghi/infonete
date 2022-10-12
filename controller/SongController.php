<?php

class SongController {
    private $songModel;
    private $render;

    public function __construct($songModel, $render){
        $this->songModel = $songModel;
        $this->render = $render;
    }

    public function execute(){
        $data["canciones"] = $this->songModel->getCanciones();
        echo $this->render->render("view/songView.php", $data);
    }

    public function description(){
        $id = $_GET["id"];
        $data["cancion"] = $this->songModel->getCancion($id);
        echo $this->render->render("view/songDescriptionView.php", $data);
    }
}