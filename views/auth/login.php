<div class="contenedor login">
    
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>
        
        <p class="descripcion-pagina">Hola</p>


        <form action="/" class="formulario" method="post">
            <div class="campo">
                <label for="correo">Correo Electrónico</label>
                <input type="correo" name="correo"
                id="correo" placeholder="Escribe tu Correo Electrónico">
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password"
                id="password" placeholder="Escribe tu Contraseña">
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crear Una</a>
            <a href="/olvide">Reestablecer contraseña</a>
        </div>
    </div>
</div>