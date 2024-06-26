<?php 

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController {

    public static function Login(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {

                $usuario = Usuario::where('correo', $usuario->correo);

                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    
                    if( password_verify($_POST['password'], $usuario->password)) {

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['correo'] = $usuario->correo;
                        $_SESSION['login'] = true;

                        header('Location: /dashboard');
                        
                    } else {
                        Usuario::setAlerta('error', 'Contraseña incorrecta');
                    }
                }
            }
            $alertas = Usuario::getAlertas();
        }


        $router->render('auth/login', [
            'title' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router) {

        $usuario = new Usuario();
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            
            if(empty($alertas)) {
                
                $existeUsuario = Usuario::where('correo', $usuario->correo);
                
                if(!$existeUsuario) {   //if false entonces...
                
                    $usuario->hashPassword();

                   //Eliminar el campo password2 del Objeto Usuario
                    unset($usuario->password2);

                    $usuario->crearToken();

                    $email = new Email($usuario->correo, $usuario->nombre, $usuario->token);
                    $enviarEmail = $email->enviarConfirmacion();
                    $resultadoSave = $usuario->guardar();

                    if($enviarEmail && $resultadoSave['resultado']) {
                        header('Location: /mensaje');
                    } else if(!$resultadoSave) {
                        Usuario::setAlerta('error', 'No se ha podido guardar el usuario, inténtelo nuevamente.');
                    } else if(!$enviarEmail) {
                        Usuario::setAlerta('error', 'No se ha podido enviar el correo, por favor contacte a soporte.');
                    }

                } else {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                }
            }

            $alertas = Usuario::getAlertas();
        }
        

        $router->render('auth/crear', [
            'title' => 'Crear Cuenta',
            'usuario' => $usuario, 
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = new Usuario($_POST);
            
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {

                $usuario = Usuario::where('correo', $usuario->correo);
                
                if($usuario && $usuario->confirmado == 1) {

                    $usuario->crearToken();
                    unset($usuario->password2);

                    $usuario->guardar();

                    $email = new Email($usuario->correo, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                }

                $alertas = Usuario::getAlertas();
            }
        }

        $router->render('auth/olvide', [
            'title' => 'Recuperar Contraseña', 
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router) {

        $alertas = [];
        $token = s($_GET['token']);
        $mostrar = true;
        $resultado = false; 

        if(!$token) header('Location: /');

        $usuario = Usuario::where('token', $token);

        if($usuario && $usuario->confirmado == 1) {

            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                $usuario->sincronizar($_POST);
                $alertas = $usuario->validarPassword();

                if(empty($alertas)) {

                    $usuario->hashPassword();
                    unset($usuario->password2);
                    $usuario->token = null;
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta('exito', 'La contraseña ha sido cambiada correctamente');
                    }
                    else {
                        Usuario::setAlerta('error', 'La contraseña no se ha podido guardar, intentelo de nuevo');
                    }
                }
            }
        } else {
            Usuario::setAlerta('error', 'Token no válido');
        }
        
        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'title' => 'Reestablecer Contraseña',
            'alertas' => $alertas, 
            'mostrar' => $mostrar,
            'resultado' => $resultado 
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

        $alertas = [];
        $token = s($_GET['token']);

        if(!$token) header('Location: /'); 

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);
            
            $usuario->guardar();

            if($usuario) {
                Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
            } else {
                Usuario::setAlerta('error', 'La cuenta no ha podido ser comprobada, inténtelo nuevamente');
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'title' => 'Confirmar Cuenta', 
            'alertas' => $alertas
        ]);
    }
}