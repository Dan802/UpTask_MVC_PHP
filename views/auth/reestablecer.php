<div class="contenedor reestablecer">
    
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nueva contraseña</p>

        <form action="/reestablecer" class="formulario" method="post">

        <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password"
                id="password" placeholder="Escribe tu Nueva Contraseña">
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crear Una</a>
            <a href="/olvide">Reestablecer contraseña</a>
        </div>
    </div>
</div>