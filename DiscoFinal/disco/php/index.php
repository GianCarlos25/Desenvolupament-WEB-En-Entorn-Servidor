<?php


require_once __DIR__ . '/auth.php';      // solo session + redirect
require_once __DIR__ . '/helpers.php';   // h()

if (!empty($_SESSION['usuario'])) {
    $headerFile = __DIR__ . '/header-sesionActive.inc.php';
} else {
    $headerFile = __DIR__ . '/header.php';
}

$usuario = (string)($_SESSION['usuario'] ?? '');

// elegir header según sesión (ya garantizada por auth.php)


// ---- Conexión PDO (tu patrón) ----
$opc = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false
];
try {
  $dwes = new PDO('mysql:host=localhost;dbname=discografia;charset=utf8mb4', 'root', '', $opc);
} catch (PDOException $e) {
  echo 'Falló la conexión: ' . $e->getMessage();
  exit;
}
// -----------------------------------

$msg = $_GET['msg'] ?? null;

try {
  $albums = $dwes->query("SELECT codigo,titulo,discografica,formato,fechaLanzamiento,fechaCompra,precio
                             FROM album ORDER BY codigo")->fetchAll();
} catch (Throwable $e) {
  die('Error: ' . h($e->getMessage()));
}
?>
<!doctype html>
<meta charset="utf-8">
<title>Álbumes</title>
<?php require $headerFile; ?>  
<h1>Álbumes</h1>
<p><a href="albumnuevo.php">Nuevo álbum</a> | <a href="canciones.php">Buscar canciones</a></p>
<?php if ($msg)
  echo '<p>' . h($msg) . '</p>'; ?>
<table border="1" cellpadding="6" cellspacing="0">
  <tr>
    <th>Código</th>
    <th>Título</th>
    <th>Discográfica</th>
    <th>Formato</th>
    <th>F. lanz.</th>
    <th>F. compra</th>
    <th>Precio</th>
    <th>Borrar</th>
  </tr>
  <?php foreach ($albums as $a): ?>
    <tr>
      <td><?= h($a['codigo']) ?></td>
      <td><a href="album.php?codigo=<?= urlencode($a['codigo']) ?>"><?= h($a['titulo']) ?></a></td>
      <td><?= h($a['discografica']) ?></td>
      <td><?= h($a['formato']) ?></td>
      <td><?= h($a['fechaLanzamiento']) ?></td>
      <td><?= h($a['fechaCompra']) ?></td>
      <td><?= h($a['precio']) ?></td>
      <td><a href="borraralbum.php?album=<?= urlencode($a['codigo']) ?>"
          onclick="return confirm('¿Seguro que quieres borrar este álbum y sus canciones?');">
          Borrar
        </a></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$albums): ?>
    <tr>
      <td colspan="7">Sin datos.</td>
    </tr><?php endif; ?>
</table>