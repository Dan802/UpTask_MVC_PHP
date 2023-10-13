<div class="contenedor reestablecer">
    
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">

        <?php if(!$resultado){ ?>

            <p class="descripcion-pagina">Coloca tu nueva contraseña</p>

            <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

            <?php if($mostrar): ?>

                <form class="formulario" method="post">

                    <div class="campo">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password"
                        id="password" placeholder="Escribe tu Nueva Contraseña">
                    </div>

                    <div class="campo">
                        <label for="password2">Repite la Contraseña</label>
                        <input type="password" name="password2"
                        id="password2" placeholder="Escribe de Nuevo tu Nueva Contraseña">
                    </div>

                    <input type="submit" class="boton" value="Cambiar Contraseña">
                </form>

            <?php endif; ?>
        
        <?php } else {?>

            <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

            <form class="formulario" action="/">
                    <input type="submit" class="boton" value="Iniciar Sesión">
            </form>

        <?php } ?>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crear Una</a>
            <a href="/olvide">Reestablecer contraseña</a>
        </div>
    </div>
</div>
