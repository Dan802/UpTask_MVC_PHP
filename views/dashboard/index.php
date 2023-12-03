<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<?php if (empty($proyectos)): ?>
    <?php displayNoProjectsMessage(); ?>
<?php else: ?>
    <?php displayProjectList($proyectos); ?>
<?php endif; ?>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>

<?php
function displayNoProjectsMessage()
{
    echo '<p class="no-proyectos">No Hay Proyectos Aún</p>';
    echo '<a href="/crear-proyecto">Comienza creando uno</a>';
}

function displayProjectList($proyectos)
{
    echo '<ul class="listado-proyectos">';
    iterarProyectos($proyectos);
    echo '</ul>';
}

function iterarProyectos($proyectos)
{
    foreach ($proyectos as $proyecto) {
        echo '<li class="proyecto">';
        echo '<span>' . $proyecto->id . '.&nbsp;</span>';
        echo '<a href="/proyecto?id=' . $proyecto->url . '">';
        echo $proyecto->proyecto;
        echo '</a>';
        echo '</li>';
    }
}
?>

