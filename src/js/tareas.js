// IIFE: Immediately Invoked Function Expression 
// allow you to define variables and function inside a function which cannot be access outside of that function.
(function() {

    obtenerTareas();

    let tareas = [];
    let filtradas = [];

    // Boton para mostrar la ventan emergente de Agregar Tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function() {
        mostrarFormulario();
    });

    const filtros = document.querySelectorAll('#filtros input[type="radio"');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    } )

    // <<===== Funciones =====>>

    function filtrarTareas(e, vble = true) {
        
        let filtro = 'e.target.value';

        if(vble) {
            console.log(e);
            filtro = e.target.value;
        } else {
            filtro = e.value;
        }
        
        console.log(filtro);

        if(filtro !== '') {
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {
            filtradas = [];
        }

        mostrarTareas();
    }

    async function obtenerTareas() {
        try {
            const proyectoId = obtenerProyectoId();
            const url = `/api/tareas?id=${proyectoId}`; //El backend y el js deben quedar en el mismo dominio
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            // const {tareas} = resultado;
            tareas = resultado.tareas;

            mostrarTareas();

        } catch (error) {
            console.log(error);
        }
    }

    function mostrarTareas() {

        limpiarTareas();

        totalPendientes();
        totalCompletas();

        // si hay tareas filtradas entonces filtradas o si no el arreglo original de tareas
        const arrayTareas = filtradas.length ? filtradas : tareas;

        const listadoTareas = document.querySelector('#listado-tareas');
        const estadosObject = {0 : 'Pendiente', 1 : 'Completa'};

        if(arrayTareas.length === 0) {
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No Hay Tareas';
            textoNoTareas.classList.add('no-tareas');

            listadoTareas.appendChild(textoNoTareas);
            console.log('No hay tareas');

            return;
        }

        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');
            
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function() {
                mostrarFormulario(editar = true, {...tarea});
            }
            
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            // Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-Tarea');
            btnEstadoTarea.classList.add(`${estadosObject[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estadosObject[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;

            btnEstadoTarea.ondblclick = function() { 
                // Hacemos una copia de tarea porque no es buena practica trabajar en el objeto original
                // Y JS es tan kk que se actualiza SOLO todo el objeto de tareas
                cambiarEstadoTarea({...tarea});
            };

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';

            btnEliminarTarea.ondblclick = function() {
                confirmarEliminarTarea({...tarea});
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            listadoTareas.appendChild(contenedorTarea);
        });
    }

    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === '0');
        const pendientesRadio = document.querySelector('#pendientes');

        if(totalPendientes.length === 0) {
            pendientesRadio.disabled = true;
        } else {
            pendientesRadio.disabled = false;
        }
    }

    function totalCompletas() {
        const totalCompletas = tareas.filter(tarea => tarea.estado === '1');
        const completadasRadio = document.querySelector('#completadas');

        if(totalCompletas.length === 0) {
            completadasRadio.disabled = true;
        } else {
            completadasRadio.disabled = false;
        }
    }

    function mostrarFormulario(editar = false, tarea = {}) {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
        <form action="" class="formulario nueva-tarea">
            <legend>${editar ? 'Editar Tarea': 'Añade una nueva tarea'}</legend>
            <div class="campo">
                <label for="">Tarea</label>
                <input type="text" name="tarea" id="tarea"
                placeholder="${tarea.nombre ? 'Edita la Tarea' : 'Añadir Tarea al Proyecto Actual'}" 
                value = "${tarea.nombre ? tarea.nombre : ''}">
            </div>
            <div class="opciones">
                <button type="button" class="cerrar-modal">Cancelar</button>
                
                <input type="submit" class="submit-nueva-tarea" 
                value="${tarea.nombre ? 'Guardar Cambios' : 'Añadir Tarea'}">
            </div>
        </form>
        `;

        // Por medio de delegation (Prioridades de JS al debuguear el codigo)
        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e) {
            // console.log(e.target);
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal') ||
            e.target.classList.contains('modal')) {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');

                setTimeout(() => {
                    modal.remove(); //Elimina la ventana modal 
                }, 500);
            }

            if(e.target.classList.contains('submit-nueva-tarea')) {
                
                // .trim() Elimina los spacios al principio y final del input del usuario
                const nombreTareaInput = document.querySelector('#tarea').value.trim();

                if(nombreTareaInput === '') {
                    mostrarAlertaUI('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
                    return;
                } 

                if(editar) {
                    tarea.nombre = nombreTareaInput;
                    actualizarTarea(tarea);
                } else {
                    agregarTarea(nombreTareaInput);
                }
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
    }

    /**
     * @param {string} mensaje 
     * @param {string} tipo Error o Exito 
     * @param {string} referencia La alerta se insertará ANTES del elemento referenciado
     */
    function mostrarAlertaUI(mensaje, tipo, referencia){

        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;

        // console.log("referencia ", referencia);
        // console.log("parentElement", referencia.parentElement);
        // console.log('sibiling', referencia.nextElementSibling);
        
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    /**
     * Consulta el servidor/API para añadir una nueva tarea
     */
    async function agregarTarea(tarea) {
        const datosToSend = new FormData();
        datosToSend.append('nombre', tarea);
        datosToSend.append('proyectoId', obtenerProyectoId());
        let resultApiDataReturn = '';

        console.log(tarea)

        try {
            
            // 1. Nos conectamos a la api
            // 1. Request: A la vez que nos conectamos estamos enviando datos por medio de $_POST
            const urlAgregarTarea = '/api/tarea';
            const respuestaDeLaConexion = await fetch(urlAgregarTarea, {
                method: 'POST',
                body: datosToSend
            });

            // 2. Response: Datos retornados de la api (La api siempre debe retornar JSON)
            resultApiDataReturn = await respuestaDeLaConexion.json();

        } catch (error) {
            console.log(error);
        }

        if(resultApiDataReturn.tipo === 'exito') {
                
            const modal = document.querySelector('.modal');

            mostrarAlertaUI(resultApiDataReturn.mensaje,
                resultApiDataReturn.tipo,
                document.querySelector('.formulario legend'));

            setTimeout(() => {
                modal.remove();
                // window.location.reload();
            }, 3000);

            const tareaNuevaObj = {
                estado: "0",
                id: resultApiDataReturn.id,
                nombre: tarea, 
                proyectoId: resultApiDataReturn.proyectoId 
            }

            tareas = [tareaNuevaObj, ...tareas];
            agregarTareaDesdeFiltradas();
        } else {
            const modal = document.querySelector('.modal');

            mostrarAlertaUI(resultApiDataReturn.mensaje,
                resultApiDataReturn.tipo,
                document.querySelector('.formulario legend'));

            setTimeout(() => {
                modal.remove();
                // window.location.reload();
            }, 3000);
        }
    }

    function agregarTareaDesdeFiltradas() {
        const filtros = document.querySelectorAll('#filtros input[type="radio"');

        if(filtros[2].checked) {
            filtrarTareas(filtros[2], false);
        } else {
            mostrarTareas();
        }
    }

    function cambiarEstadoTarea(tareaToModify) {
        
        const nuevoEstado = tareaToModify.estado === "1" ? "0" : "1"; // if 1 => 0 else 1
        tareaToModify.estado = nuevoEstado;
        actualizarTarea(tareaToModify);

    }

    async function actualizarTarea(tareaModified) {
        const {estado, id, nombre, proyectoId} = tareaModified;

        const datos = new FormData();
        datos.append('estado', estado);
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('proyectoId', proyectoId);
        datos.append('url', obtenerProyectoId());

        // debugueando datos
        /*for( let valor of datos.values()) {
            console.log(valor);
        }*/

        let resultado = {tipo : '', mensaje : ''};

        try {
            const url = '/api/tarea/actualizar';
            const respuesta = await fetch(url, { method: 'POST', body: datos });
            resultado = await respuesta.json();

        } catch (error) {
            console.log(error);
        }

        if(resultado.tipo === 'exito') {
            // mostrarAlertaUI(
            //     resultado.mensaje, 
            //     resultado.tipo, 
            //     document.querySelector('.contenedor-nueva-tarea'));

            Swal.fire(
                '', 
                resultado.mensaje,
                'success'
            );

            const modal = document.querySelector('.modal');
            if(modal) {
                modal.remove();
            }

            // Modificamos el estado de la tarea mediante un nuevo arreglo en memoria
            tareas = tareas.map(tareaMemoria => {
                if(tareaMemoria.id === id) {
                    tareaMemoria.estado = estado;
                    tareaMemoria.nombre = nombre;
                }

                return tareaMemoria;
            });

            mostrarTareas();
        } else {
            Swal.fire(
                '', 
                resultado.mensaje,
                'error'
            );

            const modal = document.querySelector('.modal');
            if(modal) {
                modal.remove();
            }
        }
    }

    function confirmarEliminarTarea(tareaToDelete) {
        // const respuesta = confirm('Eliminar Tarea?');

        // https://sweetalert2.github.io/v10.html
        // A dialog with three buttons
        Swal.fire({
            title: '¿Eliminar Tarea?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Si',
            denyButtonText: `No`,
          }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tareaToDelete);
                // Swal.fire('Tarea Eliminada!', '', 'success');
            } 
          })
    }

    async function eliminarTarea(tareaToDelete) {

        const {estado, id, nombre} = tareaToDelete;

        const datos = new FormData();
        datos.append('estado', estado);
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('url', obtenerProyectoId());

        try {
            const url = '/api/tarea/eliminar';
            const respuesta = await fetch(url, {method: 'POST', body: datos});
            const resultado = await respuesta.json();

            // mostrarAlertaUI(
            //     resultado.mensaje,
            //     resultado.tipo,
            //     document.querySelector('.contenedor-nueva-tarea')
            // );

            if(resultado.resultado) {
                Swal.fire('Eliminado!', resultado.mensaje, 'success');

                //crear un nuevo arreglo con uno excepto todos o todos excepto uno
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tareaToDelete.id);

                mostrarTareas();
            } else {
                Swal.fire('Error :(', resultado.mensaje, 'error');
            }
            
        } catch (error) {
            console.log(error);
        }
    }

    function obtenerProyectoId() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }

    function limpiarTareas() {
        
        if(tareas.length > 0){
            const listadoTareas = document.querySelector('#listado-tareas');
            // listadoTareas.innerHTML = ''; No lo usamos porque es muy lento/mas procesamiento
    
            while(listadoTareas.firstChild) {
                listadoTareas.removeChild(listadoTareas.firstChild);
            }
        }
        
    }
})();
