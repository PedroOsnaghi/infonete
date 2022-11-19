<?php

class Session{


    public function __construct($lifetime)
    {
        if($lifetime) ini_set("session.cookie_lifetime",$lifetime);

        if(session_status() == PHP_SESSION_NONE)
            session_start();
    }

    public function setAuthUser($user){
        $_SESSION['user'] = $user;
    }

    public function getAuthUser(){
        return $_SESSION['user'] ?? false;
    }

    public function setParameter($key, $value){
        $_SESSION[$key] = $value;
    }

    public function getParameter($key){
        return $_SESSION[$key] ?? false;
    }

    public function urlRestriction($roles = []){
        //Verifica usuario logueado
        if (!isset($_SESSION['user'])) Redirect::doIt("/infonete");

        //si no se especifican roles lo deja pasar
        if(sizeof($roles) == 0) return true;

        //verifica Rol
        foreach ($roles as $rol){
            if(($_SESSION['user'])->getRol() == $rol) return true;
        }

        Redirect::doIt("/infonete");

    }


    public function closeSession(){
        session_unset();
        session_destroy();
    }
    
}