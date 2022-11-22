<?php

class UsuarioModel
{

    //constantes de Roles de Usuario
    const ROL_LECTOR = 1;
    const ROL_REDACTOR = 2;
    const ROL_EDITOR = 3;
    const ROL_ADMIN = 4;

    //constantes de estado
    const STATE_UNVERIFIED = 0;
    const STATE_VERIFIED = 1;

    //Propiedades
    private $id;
    private $nombre;
    private $apellido;
    private $pass;
    private $email;
    private $avatar;
    private $domicilio;
    private $latitud;
    private $longitud;
    private $activo;
    private $estado;
    private $hash;
    private $rol;
    private $rol_name;

    private $database;
    private $file;
    private $mailer;
    //Getters & Setters


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getLongitud()
    {
        return $this->longitud;
    }

    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function getDomicilio()
    {
        return $this->domicilio;
    }

    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;
    }

    public function getLatitud()
    {
        return $this->latitud;
    }

    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
    }

    public function getRolName()
    {
        return $this->rol_name;
    }

    public function setRolName($rol_name)
    {
        $this->rol_name = $rol_name;
    }

    public function getActivo()
    {
        return $this->activo;
    }

    public function setActivo($activo)
    {
        $this->activo = $activo;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }


    public function __construct($file, $mailer, $database)
    {
        $this->file = $file;
        $this->mailer = $mailer;
        $this->database = $database;
    }

    public function registrar()
    {
        //guarda imagen avatar
        $this->avatar = $this->guardarAvatar();

        //guardamos el usuario en BD
        $response = $this->database->execute("INSERT INTO usuario (nombre, apellido, email, pass, domicilio, latitud, longitud, avatar, vhash, rol, estado, activo)
                                        VALUES('$this->nombre',
                                               '$this->apellido',
                                               '$this->email', 
                                               '$this->pass', 
                                               '$this->domicilio',
                                               '$this->latitud',
                                               '$this->longitud',
                                               '$this->avatar',
                                               '$this->hash',
                                                $this->rol, 
                                                $this->estado, 
                                                $this->activo)");

        if($response){
            if ($this->mailer->sendEmailVerification($this->email, $this->hash))
                return array('status' => 'success', 'email' => $this->email);
            return array('status' => 'error', 'error' => 'No pudimos enviar el email de verificación.');

        }

        return array('status'=>'error', "error" => 'El registro no se pudo completar debido a un error');
    }

    public function activate($email, $hash)
    {
        return $this->database->execute("UPDATE usuario SET estado =" . self::STATE_VERIFIED . " WHERE email ='" . $email . "' AND vhash = '" . $hash . "'");
    }

    public function existeEmail($email)
    {
        return $this->database->query("SELECT COUNT(email) 'email' FROM usuario WHERE email='" . $email . "' GROUP BY email");
    }

    public function listRoles()
    {
        return $this->database->list("SELECT id as id_rol, rol_name FROM rol ORDER BY id ASC");
    }

    public function listAll()
    {
        return $this->database->list("SELECT * FROM usuario  ORDER BY rol DESC, apellido ASC");
    }

    public function searchBy($value)
    {
        return $this->database->list("SELECT * FROM usuario WHERE nombre like '%$value%' OR apellido like '%$value%' ORDER BY rol DESC , apellido ASC");
    }

    public function setRolTo($id, $rol)
    {
        return $this->database->execute("UPDATE usuario SET rol = $rol  WHERE id = $id ");
    }

    public function bloquear($id)
    {
        return $this->database->execute("UPDATE usuario SET activo = 0   WHERE id = $id ");
    }

    public function desbloquear($id)
    {
        return $this->database->execute("UPDATE usuario SET activo = 1  WHERE id = $id ");
    }

    public function autenticar($email, $pass)
    {
        $query = $this->database->query("SELECT u.*,r.rol_name FROM usuario u JOIN rol r ON u.rol = r.id WHERE u.email = '$email' AND u.pass = '$pass'");

        return $this->toUsuario($query);
    }

    public function getUsuario($id)
    {
        $query = $this->database->query("SELECT u.*,r.rol_name FROM usuario u JOIN rol r ON u.rol = r.id WHERE u.id = $id");
        return $this->toUsuario($query);
    }



    private function toUsuario($array)
    {

        if($array == null) return null;

        $this->id = $array['id'];
        $this->nombre = $array['nombre'];
        $this->apellido = $array['apellido'];
        $this->pass = $array['pass'];
        $this->email = $array['email'];
        $this->domicilio = $array['domicilio'];
        $this->latitud = $array['latitud'];
        $this->longitud = $array['longitud'];
        $this->avatar = $array['avatar'];
        $this->hash = $array['vhash'];
        $this->rol = $array['rol'];
        $this->rol_name = $array['rol_name'];
        $this->estado = $array['estado'];
        $this->activo = $array['activo'];

        return $this;
    }


    public function rolTools()
    {
        switch ($this->getRol()){
            case self::ROL_ADMIN:
                $menu = file_get_contents("public/view/partial/admin.mustache");
                break;
            case self::ROL_EDITOR:
                $menu = file_get_contents("public/view/partial/editor.mustache");
                break;
            case self::ROL_REDACTOR:
                $menu = file_get_contents("public/view/partial/redactor.mustache");
                break;
            default:
                $menu = null;
        }

        return $menu;
    }

    private function guardarAvatar(){
        return ($this->file->uploadFile("profiles"))?
            $this->file->get_file_uploaded():
            'default.png';
    }
}

// []
