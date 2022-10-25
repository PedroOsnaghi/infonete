<?php

class GeoPosition
{
    private $client;
    private $apiKey;
    private $calle;
    private $numero;
    private $ciudad;
    private $provincia;
    private $pais;
    private $query;


    //Getters & Setters
    public function getCalle()
    {
        return $this->calle;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function getCiudad()
    {
        return $this->ciudad;
    }

    public function getProvincia()
    {
        return $this->provincia;
    }

    public function getPais()
    {
        return $this->pais;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getLatitud()
    {
        return $this->latitud;
    }

    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    }


    public function __construct($client, $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;

    }

    public function getPosition()
    {
        $this->client->setUrlBase('https://maps.googleapis.com/maps/api/geocode/json');

        $data = ['address' => $this->query, 'key' => $this->apiKey];

        return $this->client->request(Client::GET, $data);



    }

    public function getDirFromPosition($position = [])
    {

    }
}