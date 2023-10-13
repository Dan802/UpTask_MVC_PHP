<div class="contenedor olvide">
    
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera el acceso a tu cuenta</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/olvide" class="formulario" method="post">
            <div class="campo">
                <label for="correo">Correo Electrónico</label>
                <input type="correo" name="correo"
                id="correo" placeholder="Escribe tu Correo Electrónico">
            </div>

            <input type="submit" class="boton" value="Enviar Instrucciones">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
            <a href="/crear">Crear Una Cuenta</a>
        </div>
    </div>
</div>