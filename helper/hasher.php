<?php

class hasher
{
    public static function encrypt($value)
    {
        return md5($value);
    }

    /****
     * @param $hash string hash a desencriptar
     * @return string cadena desencriptada
     */

    public static function decrypt($hash)
    {

    }

    /***
     * @param $value string cadena a comparar
     * @param $hash string hash de comparacion
     * @return bool true en caso de ser iguales y false en caso de ser distintos
     */
    public static function compare($value, $hash)
    {
        return (md5(value) == $hash) ? true : false;
    }


}