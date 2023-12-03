<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Cambiar Perfil</a>

    <form action="/cambiar-password" class="formulario" method="POST">

        <div class="campo">
            <label for="password_actual">Contraseña Actual</label>
            <input type="password" placeholder="Ingresa tu contraseña actual" name="password_actual" 
            value="">
        </div>

        <div class="campo">
            <label for="password_nuevo">Nueva Contraseña</label>
            <input type="password" placeholder="Ingresa tu correo" name="password_nuevo" 
            value="">
        </div>
            
        <input type="submit" value="Guardar Cambios">

    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>