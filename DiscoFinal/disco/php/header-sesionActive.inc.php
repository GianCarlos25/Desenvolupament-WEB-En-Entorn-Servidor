<!-- header.php -->
<?php

// si la sesión no está activa, intenta recuperarla desde cookie
if (!isset($_SESSION['usuario']) && isset($_COOKIE['user'])) {
    $_SESSION['usuario'] = $_COOKIE['user'];
}

if (!isset($_SESSION['usuario'])) {
    // si no hay sesión ni cookie, redirige al login
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil de <?= h($usuario) ?></title>
</head>

<nav>
  <strong>Hola, <?= h($_SESSION['usuario'] ?? '') ?></strong> |
  <a href="/php/index.php">Inicio</a>
</nav>
<hr>


</html>