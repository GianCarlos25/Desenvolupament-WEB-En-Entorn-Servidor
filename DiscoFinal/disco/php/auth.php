<?php
// auth.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['usuario'])) {
    header('Location: /php/login.php'); // ajusta ruta si hace falta
    exit;
}
