<?php 

namespace Controllers;

use MVC\Router;
use Model\Usuario;

class LoginController {

    public static function Login(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        $router->render('auth/login', [
            'title' => 'Iniciar Sesión'
        ]);
    }

    public static function logout() {

    }

    public static function crear(Router $router) {

        $usuario = new Usuario();
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta();
        }

        $router->render('auth/crear', [
            'title' => 'Crear Cuenta',
            'usuario' => $usuario, 
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        $router->render('auth/olvide', [
            'title' => 'Recuperar Contraseña'
        ]);
    }

    public static function reestablecer(Router $router) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        $router->render('auth/reestablecer', [
            'title' => 'Reestablecer Contraseña'
        ]);
    }

    public static function mensaje(Router $router) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        $router->render('auth/mensaje', [
            'title' => 'Instrucciones'
        ]);
    }

    public static function confirmar(Router $router) {
        $router->render('auth/confirmar', [
            'title' => 'Confirmar Cuenta'
        ]);
    }
}