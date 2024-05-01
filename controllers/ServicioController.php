<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController
{
    public static function index(Router $router)
    {
        session_start();
        isAdmin();

        $servicios = Servicio::all();
        $router->render('servicios/index', [
            'servicios' => $servicios,
            'nombre' => $_SESSION['nombre']
        ]);
    }

    public static function crear(Router $router)
    {
        session_start();
        isAdmin();

        $servicio = new Servicio();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router)
    {
        session_start();
        isAdmin();

        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /404');
            return;
        }

        $servicio = Servicio::find($id);

        if (!$servicio) {
            header('Location: /404');
            return;
        }

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }


        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar()
    {
        session_start();
        isAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

            if (!$id) {
                header('Location: /404');
                return;
            }

            $servicio = Servicio::find($id);

            if (!$servicio) {
                header('Location: /404');
                return;
            }

            $servicio->eliminar();
            header('Location: /servicios');
        }
    }
}
