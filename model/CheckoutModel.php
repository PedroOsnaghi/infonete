<?php

class CheckoutModel
{
    const TARGET_EDITION = 0;
    const TARGET_SUSCRIPTION = 1;

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registrarPago($data, $idPago)
    {
        switch ($data['type']){
            case self::TARGET_SUSCRIPTION:
                try {
                    $query = $this->database->execute("INSERT INTO usuario_suscripcion (id_usuario, id_suscripcion, id_producto, fecha_inicio, id_pago, activa) 
                                                        VALUES (" . $data['id_usuario'] ."," . $data['id_suscripcion'].",". $data['id_producto'].", now(),". $idPago.", 1)");
                    if($query) return array('success' => 'La suscripción se registró con éxito',
                        'suscripcion' => $data['id_suscripcion']);
                    return array('error' => 'No se pudo registrar la suscripción');
                } catch (exception) {
                    return array('error' => 'Ya tenés una suscripción activa para el producto seleccionado. Puedes verla en Mis Suscripciones');
                }
            case self::TARGET_EDITION:
                try {
                    $query = $this->database->execute("INSERT INTO compra_edicion (id_usuario, id_edicion, fecha, id_pago) 
                                                       VALUES (".$data['id_usuario'].",".$data['id_edicion'].", now(),". $idPago.")");
                    if($query) return array('success' => 'La compra se realizó con éxito',
                        'edicion' => $data['id_edicion']);
                    return array('error' => 'No se pudo registrar la compra');
                } catch (exception) {
                    return array('error' => 'La compra ya ha sido realizada. Puedes verla en Mis Productos');
                }
        }




    }
}