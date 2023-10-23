<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {

    public static function index() {

        $proyectoId = $_GET['id']; //Desde la URL

        if(!$proyectoId) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $proyectoId);

        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /404');
        }

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        // echo json_encode($tareas);
        echo json_encode(['tareas' => $tareas]);
    }

    public static function crear() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            
            // Verificamos que exista el proyecto al cual queremos añadir la tarea
            $proyectoId = $_POST['proyectoId'];
            $proyecto = Proyecto::where('url', $proyectoId); 

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $dataToReturn = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea'
                ];
    
                echo json_encode($dataToReturn);
            } 

            // Automaticamente se asignan los datos de post a tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();

            $dataToReturn = [
                'tipo' => 'exito',
                'mensaje' => 'Tarea agregada correctamente',
                'id' => $resultado['id'],
                'nota del autor desde PHP/API' => 'Jodete',
                'datos que recibió la API/PHP/SERVIDOR' => $_POST,
            ];
            
            echo json_encode($dataToReturn);
        }
    }
    

    public static function actualizar() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            
        }
    }
   
    public static function eliminar() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            
        }
    }
}      