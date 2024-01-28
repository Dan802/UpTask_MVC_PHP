<?php if(empty($alertas)): ?>
    <div class="welcome visible" id="welcome">
        <h1>¡Hola! ¿Primera vez por aquí?</h1>

        <p>Puedes crear una cuenta totalmente gratis y con todas las funcionalidades disponibles. Anímate.</p>

        <p><br>Si en cambio, solo pasabas a mirar el sitio, puedes ingresar con el siguiete usuario:</p>
        <p><span>Correo:</span> juanfgonzalez@correo.com</p>
        <p><span>Contraseña:</span> 123456</p>

        <button class="welcome__btn">
            <p>Entendido</p>
        </button>

        <p id="welcome__info">Para ver mas proyectos presiona el <a href="https://juanfgonzalez.netlify.app">Enlace</a></p>
    </div>
<?php endif; ?>

<div class="contenedor login">
    
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

<div class="contenedor-sm">
    
    <p class="descripcion-pagina">Iniciar Sesión</p>
    
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

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

<?php 
    $script = '<script src="build/js/welcome.js"></script>';
?>