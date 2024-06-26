<aside class="sidebar">
        
    <div class="contenedor-sidebar">
        <h2>UpTask</h2>

        <div class="cerrar-menu">
            <img src="/build/img/cerrar.svg" alt="imagen cerrar menu" id="cerrar-menu">
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="/dashboard"
            class="<?php echo ($title === 'Proyectos') ? 'activo' : ''; ?>">Proyectos</a>
        <a href="/crear-proyecto"
            class="<?php echo ($title === 'Crear Proyecto') ? 'activo' : ''; ?>">Crear Proyecto</a>
        <a href="/perfil"
            class="<?php echo ($title === 'Perfil') ? 'activo' : ''; ?>" >Perfil</a>
    </nav>

    <div class="cerrar-sesion-mobile">
        <a href="/logout" class="cerrar-sesion">Cerrar Sesión</a>
    </div>
</aside>