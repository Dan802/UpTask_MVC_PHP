<div class="contenedor crear">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/crear" class="formulario" method="post">
            
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre"
                id="nombre" placeholder="Escribe tu Nombre"
                value="<?php echo $usuario->nombre; ?>">
            </div>
        
            <div class="campo">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email"
                id="email" placeholder="Escribe tu Correo Electrónico"
                value="<?php echo $usuario->correo; ?>">
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password"
                id="password" placeholder="Escribe tu Contraseña">
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password2"
                id="password2" placeholder="Repite tu Contraseña">
            </div>

            <input type="submit" class="boton" value="Crear Cuenta">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/olvide">Reestablecer contraseña</a>
        </div>
    </div>
</div>