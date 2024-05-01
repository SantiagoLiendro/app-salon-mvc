<?php

namespace Model;

class AdminCita extends ActiveRecord
{
    protected static $tabla = 'citas_servicios';
    protected static $columnasDB = ['id', 'hora', 'nombre_cliente', 'telefono', 'email', 'servicio', 'precio'];

    public $id;
    public $hora;
    public $nombre_cliente;
    public $telefono;
    public $email;
    public $servicio;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->hora = $args['hora'] ?? '';
        $this->nombre_cliente = $args['nombre_cliente'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->servicio = $args['servicio'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
}
