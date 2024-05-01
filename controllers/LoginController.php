<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    //Verificar que el usuario este confirmado
                    if ($usuario->comprobarPasswordandVerificado($auth->password)) {
                        //Autenticar 
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Rediroccionar

                        if ($usuario->admin === '1') {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /citas');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado.');
                }
            }

            $alertas = Usuario::getAlertas();
        }
        $router->render('/auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logaut()
    {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->confirmado === '1') {
                    $usuario->crearToken();
                    $usuario->guardar();
                    // Enviar Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    //Enviar Alerta
                    Usuario::setAlerta('exito', 'Revisa tu email.');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('/auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        $error = false;

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido.');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Leer el nuevo password y guardarlo
            $password  = new Usuario($_POST);
            $password->validarPassword();

            if (empty($alertas)) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();

                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuario($_POST);
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que alerta este vacio
            if (empty($alertas)) {
                //Verificar que el usuario no este 
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //No esta registrado
                    $usuario->hashPassword();
                    //Generar token unico
                    $usuario->crearToken();
                    //Enviar EMail
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    //Guardar en la DB
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }
        $router->render('/auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        if (!$usuario) {
            Usuario::setAlerta('error', 'Token no valido.');
        } else {
            $usuario->confirmado = 1;
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente.');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
