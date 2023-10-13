<?php 

namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'correo', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $correo;
    public $password;
    public $password2;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? null;
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // validacion para cuentas nuevas
    public function validarNuevaCuenta () {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Usuario es Obligatorio';
        }
        if(!$this->correo) {
            self::$alertas['error'][] = 'El Correo Electrónico del Usuario es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'La Contraseña es Obligatoria';
        } else if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La Contraseña Debe Tener al Menos 6 Caracteres';
        } else if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Las Contraseñas No Coinciden';
        }
        
        return self::$alertas;
    }
    
    public function validarEmail() {
        if(!$this->correo) {
            self::$alertas['error'][] = 'El correo electrónico es obligatorio';
        }
        if(!filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El correo electrónico no es válido';
        }
        
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'La Contraseña es Obligatoria';
        } else if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La Contraseña Debe Tener al Menos 6 Caracteres';
        } else if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Las Contraseñas No Coinciden';
        }
        
        return self::$alertas;
    }

    public function hashPassword () {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        // $this->token = md5(uniqid()); return 32 caracteres
        $this->token = uniqid();
    }
}