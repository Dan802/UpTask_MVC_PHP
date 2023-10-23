// IIFE: Immediately Invoked Function Expression 
// allow you to define variables and function inside a function which cannot be access outside of that function.
(function() {

    // Boton para mostrar la ventan emergente de Agregar Tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    function mostrarFormulario() {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
        <form action="" class="formulario nueva-tarea">
            <legend>Añade una nueva tarea</legend>
            <div class="campo">
                <label for="">Tarea</label>
                <input type="text" name="tarea" placeholder="Añadir Tarea al Proyecto Actual" id="tarea">
            </div>
            <div class="opciones">
                <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea">
                <button type="button" class="cerrar-modal">Cancelar</button>
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
                submitFormularioNuevaTarea();
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioNuevaTarea() {
        // .trim() Elimina los spacios al principio y final del input del usuario
        const tarea = document.querySelector('#tarea').value.trim();

        if(tarea === '') {
            mostrarAlertaUI('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
            return;
        } 

        agregarTarea(tarea);
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

        console.log("referencia ", referencia);
        console.log("parentElement", referencia.parentElement);
        console.log('sibiling', referencia.nextElementSibling);
        
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

        try {
            
            // 1. Nos conectamos a la api
            // 1. Request: A la vez que nos conectamos estamos enviando datos por medio de $_POST
            const urlAgregarTarea = 'http://localhost:3000/api/tarea';
            const respuestaDeLaConexion = await fetch(urlAgregarTarea, {
                method: 'POST',
                body: datosToSend
            });

            // 2. Response: Datos retornados de la api (La api siempre debe retornar JSON)
            const resultApiDataReturn = await respuestaDeLaConexion.json();
            
            if(resultApiDataReturn.tipo === 'exito') {
                
                const modal = document.querySelector('.modal');

                mostrarAlertaUI(resultApiDataReturn.mensaje,
                    resultApiDataReturn.tipo,
                    document.querySelector('.formulario legend'));

                setTimeout(() => {
                    modal.remove();
                }, 3000);
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
})();
