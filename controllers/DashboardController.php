<?php 

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Model\Proyecto;

class DashboardController {

    public static function index(Router $router) {

        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'title' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }
    
    public static function crear_proyecto(Router $router) {

        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            $alertas = $proyecto->validar_usuarioid();

            if(empty($alertas)) {

                $hash = md5(uniqid());
                $proyecto->url = $hash;

                $proyecto->propietarioId = $_SESSION['id'];

                $proyecto->guardar();

                header('Location: /proyecto?id=' . $proyecto->url);
            }

            $alertas = $proyecto->getAlertas();
        }

        $router->render('dashboard/crear-proyecto', [
            'title' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) {
        
        isAuth();
        $alertas = [];

        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $token);

        if($proyecto->propietarioId !== $_SESSION['id']) header('Location: /dashboard');


        $router->render('dashboard/proyecto', [
            'title' => $proyecto->proyecto, 
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router) {

        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            

            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->validar_perfil();
            $alertas = $usuario->validar_usuarioid();

            if(empty($alertas)) {

                $existeUsuario = Usuario::where('correo', $usuario->correo);

                if($existeUsuario && $existeUsuario->id !== $usuario->id) {

                    Usuario::setAlerta('error', 'Correo electrónico no válido');
                    $alertas = $usuario->getAlertas();

                } else {

                    $resultado = $usuario->guardar();
                                
                    if($resultado) {
                        
                        Usuario::setAlerta('exito', 'Guardado Correctamente');
                        $alertas = $usuario->getAlertas();
                        
                        $_SESSION['nombre'] = $usuario->nombre;
                    }
                }
            }
        }

        $router->render('dashboard/perfil', [
            'title' => 'Perfil',
            'usuario' => $usuario, 
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router) {
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_usuarioid();
            $alertas = $usuario->nuevo_password();

            if(empty($alertas)) {
                $resultado = $usuario->comprobar_password();

                if($resultado) {

                    $usuario->password = $usuario->password_nuevo;
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    $usuario->hashPassword();

                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta('exito', 'La contraseña ha sido cambiada correctamente');
                        $alertas = $usuario->getAlertas();
                    }

                } else {
                    Usuario::setAlerta('error', 'La contraseña es incorrecta');
                    $alertas = $usuario->getAlertas();
                }
            }
        }

        $router->render('dashboard/cambiar-password', [
            'title' => 'Cambiar Password',
            'alertas' => $alertas
        ]);
    }
}