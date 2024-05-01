<?php


namespace Model;

class CitaServicio extends ActiveRecord
{
    protected static $tabla = 'citas_servicios';
    protected static $columnasDB = ['id', 'cita_id', 'servicios_id'];

    public $id;
    public $cita_id;
    public $servicios_id;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->cita_id = $args['cita_id'] ?? '';
        $this->servicios_id = $args['servicios_id'] ?? '';
    }
}
