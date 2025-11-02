<?php
// auth.php
session_start(); // antes de imprimir nada

if (empty($_SESSION['usuario'])) {
    // no hay sesión -> a login
    header('Location: inicio_sesion.php');
    exit;
}
// hay sesión -> continuar
?>