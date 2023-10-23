<?php 

namespace Controllers;

use MVC\Router;
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

        $router->render('dashboard/perfil', [
            'title' => 'Perfil'
        ]);
    }
}

