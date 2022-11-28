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
        $res = $this->generarFactura($data['id_usuario'], $idPago, $data['concepto'], $data['cantidad'], $data['precio']);
        if ($res) {
            switch ($data['type']) {
                case self::TARGET_SUSCRIPTION:
                    try {
                        $query = $this->database->execute("INSERT INTO usuario_suscripcion (id_usuario, id_suscripcion, id_producto, fecha_inicio, id_pago, activa) 
                                                        VALUES (" . $data['id_usuario'] . "," . $data['id_suscripcion'] . "," . $data['id_producto'] . ", now()," . $idPago . ", 1)");

                        return ($query) ? array('success' => 'La suscripción se registró con éxito',
                                            'suscripcion' => $data['id_suscripcion'],
                                             'payment_id' => $idPago)
                                        :
                                          array('error' => 'No se pudo registrar la suscripción');

                    } catch (exception) {
                        return array('error' => 'Ya tenés una suscripción activa para el producto seleccionado. Puedes verla en Mis Suscripciones');
                    }
                case self::TARGET_EDITION:
                    try {
                        $query = $this->database->execute("INSERT INTO compra_edicion (id_usuario, id_edicion, fecha, id_pago) 
                                                       VALUES (" . $data['id_usuario'] . "," . $data['id_edicion'] . ", now()," . $idPago . ")");

                        return ($query) ? array('success' => 'La compra se realizó con éxito',
                                                'edicion' => $data['id_edicion'],
                                             'payment_id' => $idPago)
                                        :
                                          array('error' => 'No se pudo registrar la compra');

                    } catch (exception) {
                        return array('error' => 'La compra ya ha sido realizada. Puedes verla en Mis Productos');
                    }
            }
        } else {
            return array('error' => 'No se pudo registrar la factura de compra');
        }


    }

    public function generarFactura($idUsuario, $idPago, $concepto, $cantidad, $precio)
    {
        return $this->database->execute("INSERT INTO factura (id, cantidad, detalle, precio, id_usuario)
                                        VALUES ($idPago, $cantidad, '$concepto', $precio, $idUsuario)");
    }

    public function getFactura($payment_id)
    {
        return $this->database->query("SELECT f.*, u.* FROM factura f JOIN usuario u on f.id_usuario = u.id
                                      WHERE f.id = $payment_id");
    }
}