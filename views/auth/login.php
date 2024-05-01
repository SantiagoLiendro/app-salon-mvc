<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php
include_once __DIR__ . '/../templates/alerta.php';
?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="email">
            Email
        </label>
        <input type="email" id="email" name="email" placeholder="Tu Email" />
    </div>

    <div class="campo">
        <label for="password">
            Password
        </label>
        <input type="password" id="password" name="password" placeholder="Tu Password" />
    </div>

    <input type="submit" class="boton" value="Iniciar Sesiòn">

</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>