<h1 class="nombre-pagina">Nuevo Servicios</h1>
<p class="descripcion-pagina">Llena todos los campos para crear un nuevo servicios</p>

<?php
include_once __DIR__ . '/../templates/barra.php';
include_once __DIR__ . '/../templates/alerta.php';
?>


<form action="/servicios/crear" method="POST" class="formulario">

    <?php
    include_once __DIR__ . '/formulario.php';
    ?>

    <input type="submit" class="boton" value="Guardar Servicio">
</form>