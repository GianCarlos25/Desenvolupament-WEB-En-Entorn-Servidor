<?php
// Conexión simple con manejo de errores
$conexion = new mysqli('localhost', 'root', '', 'tienda');

if ($conexion->connect_error) {
    die('❌ Error conectando a la base de datos: ' . $conexion->connect_error);
}

// Consulta
$resultado = $conexion->query('SELECT cod, nombre_corto FROM producto');

// Encabezado
echo '<h1>Listado de productos</h1>';
echo '<ul>';
// Mostrar cada producto como enlace
while ($row = $resultado->fetch_assoc()) {
    $cod = urlencode(trim($row['cod']));
    echo "<li><a href='stock.php?cod=$cod'>{$row['nombre_corto']}</a></li>";
}

echo '</ul>';

// Cerrar conexión
$conexion->close();
?>
