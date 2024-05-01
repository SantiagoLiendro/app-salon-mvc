<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController
{
    public static function index(Router $router)
    {
        session_start();
        isAdmin();

        //Consultar la DB
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechaCortada = explode('-', $fecha);

        if (!checkdate($fechaCortada[1], $fechaCortada[2], $fechaCortada[0])) {
            header('Location: /404');
        }
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) AS nombre_cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre AS servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuario_id=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citas_servicios ";
        $consulta .= " ON citas_servicios.cita_id=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citas_servicios.servicios_id ";
        $consulta .= " WHERE fecha = '$fecha';";

        $citas = AdminCita::SQL($consulta);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}
