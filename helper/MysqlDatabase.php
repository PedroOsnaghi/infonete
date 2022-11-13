<?php

class MysqlDatabase
{
    private $connection;

    public function __construct($servername, $username, $password, $dbname)
    {
        $conn = mysqli_connect(
            $servername,
            $username,
            $password,
            $dbname
        );

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $this->connection = $conn;
    }

    //solo consulta y retorna un array asociativo con los resultados
    public function query($sql)
    {
        return $this->connection->query($sql)->fetch_assoc();
    }

    public function list($sql)
    {
        return $this->connection->query($sql)->fetch_all(MYSQLI_ASSOC);
    }


    //insertar, actualizar, eliminar y retorna la cantidad de registros afectados (valor entero o 0)
    public function execute($sql)
    {
        $this->connection->query($sql);
        return $this->connection->affected_rows;
    }

    public function lastInsertId()
    {
        return $this->connection->insert_id;
    }

}

