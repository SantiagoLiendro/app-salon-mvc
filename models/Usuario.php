<?php

namespace Model;


class Usuario extends ActiveRecord
{
    //Base de Datos 
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'telefono', 'password', 'token', 'confirmado', 'admin'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $password;
    public $token;
    public $confirmado;
    public $admin;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->admin = $args['admin'] ?? 0;
    }

    //Mensajes de validacion
    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = "El nombre es obligatorio.";
        }

        if (!$this->apellido) {
            self::$alertas['error'][] = "El apellido es obligatorio.";
        }

        if (!$this->telefono) {
            self::$alertas['error'][] = "El telefono es obligatorio.";
        }

        if (!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio.";
        }

        if (!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio.";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe ser de al menos 6 caracteres.";
        }

        return self::$alertas;
    }

    public function existeUsuario()
    {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email= '" . $this->email . "' LIMIT 1;";
        $resultado = self::$db->query($query);

        if ($resultado->num_rows) {
            self::$alertas['error'][] = "El usuario ya esta registrado.";
        }

        return $resultado;
    }

    public function validarLogin()
    {
        if (!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio.";
        }

        if (!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio.";
        }

        return self::$alertas;
    }

    public function validarEmail()
    {
        if (!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio.";
        }
        return self::$alertas;
    }

    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio.";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe ser de al menos 6 caracteres.";
        }
        return self::$alertas;
    }

    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken()
    {
        $this->token = uniqid();
    }

    public function comprobarPasswordandVerificado(string $password)
    {
        $resultado = password_verify($password, $this->password);

        if (!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }
}
