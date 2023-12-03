<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/cambiar-password" class="enlace">Cambiar La Contraseña</a>

    <form action="/perfil" class="formulario" method="POST">

        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" placeholder="Ingresa tu nuevo nombre" name="nombre" 
            value="<?php echo $usuario->nombre ?>">
        </div>

        <div class="campo">
            <label for="correo">Correo Electrónico</label>
            <input type="email" placeholder="Ingresa tu correo" name="correo" 
            value="">
        </div>
            
        <input type="submit" value="Guardar Cambios">

    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>