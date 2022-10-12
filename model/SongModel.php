<?php

class SongModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getCanciones(){
        return $this->database->query("SELECT * FROM canciones");
    }

    public function getCancion($id){
        $sql = "SELECT * FROM canciones where idCancion = " . $id;
        return $this->database->query($sql);
    }
}