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
            
            if((int)$_SESSION['id'] === 1) {
                $dataToReturn = [
                    'tipo' => 'error',
                    'mensaje' => 'Para realizar esta acción debes crear tu propia cuenta'
                ];
                echo json_encode($dataToReturn);
                return;
            }

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
                'proyectoId' => $proyecto->id,
                'nota del autor desde PHP/API' => 'Jodete',
                'datos que recibió la API/PHP/SERVIDOR' => $_POST,
            ];
            
            echo json_encode($dataToReturn);
        }
    }

    public static function actualizar() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            
            if((int)$_SESSION['id'] === 1) {
                $dataToReturn = [
                    'tipo' => 'error',
                    'mensaje' => 'Para realizar esta acción debes crear tu propia cuenta'
                ];
                echo json_encode($dataToReturn);
                return;
            }

            $proyecto = Proyecto::where('url', $_POST['url']);
            
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $dataToReturn = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($dataToReturn);
                return;
            } 
    
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();

            if($resultado) {
                $dataToReturn = [
                    'tipo' => 'exito',
                    'mensaje' => 'Actualizado correctamente',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id
                ];

                echo json_encode($dataToReturn);
            }
        }
    }
   
    public static function eliminar() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

            if((int)$_SESSION['id'] === 1) {
                $dataToReturn = [
                    'tipo' => 'error',
                    'mensaje' => 'Para realizar esta acción debes crear tu propia cuenta'
                ];
                echo json_encode($dataToReturn);
                return;
            }

            $proyecto = Proyecto::where('url', $_POST['url']);
            
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $dataToReturn = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($dataToReturn);
                return;
            } 

            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            if(!$resultado) {
                $dataToReturn = [
                    'resultado' => $resultado,
                    'mensaje' => 'Hubo un error eliminando la tarea',
                    'tipo' => 'error'
                ];
                echo json_encode($dataToReturn);
                return;
            }

            $dataToReturn = [
                'resultado' => $resultado,
                'tipo' => 'exito',
                'mensaje' => 'Tarea eliminada correctamente'
            ];

            echo json_encode($dataToReturn);
        }
    }
}      