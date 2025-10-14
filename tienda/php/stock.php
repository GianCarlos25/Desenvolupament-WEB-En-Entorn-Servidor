<?php
// 1) Validación del parámetro ?cod
$cod = isset($_GET['cod']) ? trim($_GET['cod']) : '';
if ($cod === '') {
    http_response_code(400);
    die('Falta el parámetro "cod" en la URL. Ej: stock.php?cod=3F24');
}

// 2) Conexión con la base de datos (ajusta credenciales si hace falta)
$conexion = new mysqli('localhost', 'root', '', 'tienda');
if ($conexion->connect_errno) {
    die('Error conectando a la base de datos: ' . $conexion->connect_error);
}
$conexion->set_charset('utf8mb4');

/* ------------------------------------------------------------
   3) Si llega POST -> Actualizar unidades (transacción)
   ------------------------------------------------------------ */
$mensaje = '';
$hayPost = ($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['actualizar']);

if ($hayPost) {
    // Esperamos un array: unidades[COD_TIENDA] = valor
    $unidadesPost = isset($_POST['unidades']) && is_array($_POST['unidades'])
        ? $_POST['unidades'] : [];

    $conexion->begin_transaction();
    try {
        $stmtUpd = $conexion->prepare(
            "UPDATE stock SET unidades = ? WHERE producto = ? AND tienda = ?"
        );
        if (!$stmtUpd) {
            throw new Exception('No se pudo preparar UPDATE: ' . $conexion->error);
        }

        foreach ($unidadesPost as $tiendaCod => $valor) {
            // Normaliza a entero >= 0
            $u = is_numeric($valor) ? (int) $valor : 0;
            if ($u < 0)
                $u = 0;

            $stmtUpd->bind_param('isi', $u, $cod, $tiendaCod);
            if (!$stmtUpd->execute()) {
                throw new Exception('Error al actualizar tienda ' . $tiendaCod . ': ' . $stmtUpd->error);
            }
        }

        $conexion->commit();
        $mensaje = 'Stock actualizado correctamente.';
        $stmtUpd->close();

    } catch (Throwable $e) {
        $conexion->rollback();
        $mensaje = 'Error actualizando: ' . $e->getMessage();
    }
}

/* ------------------------------------------------------------
   4) SELECT para listar tiendas y unidades del producto
   ------------------------------------------------------------ */
$sql = "
SELECT s.producto, s.tienda, s.unidades, t.nombre AS tienda_nombre
FROM stock s
JOIN tienda t ON s.tienda = t.cod
WHERE s.producto = ?
ORDER BY t.nombre
";
$stmtSel = $conexion->prepare($sql);
if (!$stmtSel) {
    die('No se pudo preparar SELECT: ' . $conexion->error);
}
$stmtSel->bind_param('s', $cod);
$stmtSel->execute();
$res = $stmtSel->get_result();

$filas = [];
while ($row = $res->fetch_assoc()) {
    $filas[] = $row;
}
$stmtSel->close();
$conexion->close();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Stock del producto <?= htmlspecialchars($cod) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

    <h1>Stock del producto (<?= htmlspecialchars($cod) ?>)</h1>

    <?php if ($mensaje): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <?php if (count($filas) === 0): ?>
        <p>No hay stock disponible para este producto.</p>
    <?php else: ?>
        <h2>Modificar unidades por tienda</h2>
        <form method="POST" action="stock.php?cod=<?= urlencode($cod) ?>">
            <table border="1" cellpadding="6" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tienda</th>
                        <th>Unidades</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filas as $f): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($f['tienda_nombre']) ?></strong></td>
                            <td>
                                <input type="number" name="unidades[<?= htmlspecialchars($f['tienda']) ?>]"
                                    value="<?= (int) $f['unidades'] ?>" min="0">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p>
                <button type="submit" name="actualizar" value="1">Actualizar</button>
            </p>
        </form>
    <?php endif; ?>

</body>

</html>