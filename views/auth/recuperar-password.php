<h1 class="nombre-pagina">Reestablecer Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<?php
include_once __DIR__ . '/../templates/alerta.php';
?>
<?php
if (!$error) : ?>
    <form class="formulario" method="POST">
        <div class="campo">
            <label for="password" class="text-center">
                Nuevo Password
            </label>
            <input type="password" id="password" name="password" placeholder="Tu Nuevo Password" />
        </div>

        <input type="submit" value="Enviar Intrucciones" class="boton">

    </form>
<?php
endif;
?>
<div class="acciones">
    <a href="/">Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
</div>