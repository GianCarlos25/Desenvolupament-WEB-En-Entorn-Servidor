<?php

require_once __DIR__ . '/auth.php';      // solo session + redirect
require_once __DIR__ . '/helpers.php';   // h()

$usuario = (string)($_SESSION['usuario'] ?? '');

// elegir header según sesión (ya garantizada por auth.php)
if (!empty($_SESSION['usuario'])) {
    $headerFile = __DIR__ . '/header-sesionActive.inc.php';
} else {
    $headerFile = __DIR__ . '/header.php';
}

function h($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
if(!isset($_GET['codigo'])){ die('Falta ?codigo'); }

$opc = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];
try {
    $dwes = new PDO('mysql:host=localhost;dbname=discografia;charset=utf8mb4', 'root', '', $opc);
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage(); exit;
}

$codigo = (int)$_GET['codigo'];

try {
    $st = $dwes->prepare("SELECT * FROM album WHERE codigo=?");
    $st->execute([$codigo]);
    $album = $st->fetch();
    if(!$album) die('Álbum no encontrado');

    $sc = $dwes->prepare("SELECT titulo,posicion,duracion,genero FROM cancion WHERE album=? ORDER BY posicion");
    $sc->execute([$codigo]);
    $canciones = $sc->fetchAll();
} catch (Throwable $e) { die('Error: '.h($e->getMessage())); }
?>
<!doctype html><meta charset="utf-8"><title>Álbum</title>
<?php require $headerFile; ?>  

<h1><?=h($album['titulo'])?></h1>
<p>
  Discográfica: <?=h($album['discografica'])?> |
  Formato: <?=h($album['formato'])?> |
  Lanzamiento: <?=h($album['fechaLanzamiento'])?> |
  Compra: <?=h($album['fechaCompra'])?> |
  Precio: €<?=h($album['precio'])?>
</p>
<p>
  <a href="cancionnueva.php?album=<?=$codigo?>">Añadir canción</a> |
  <a href="borraralbum.php?album=<?=$codigo?>" onclick="return confirm('Borrar álbum y todas sus canciones?')">Borrar álbum</a> |
  <a href="index.php">Volver</a>
</p>
<h2>Canciones</h2>
<table border="1" cellpadding="6" cellspacing="0">
  <tr><th>#</th><th>Título</th><th>Duración</th><th>Género</th></tr>
  <?php foreach($canciones as $c): ?>
  <tr>
    <td><?=h($c['posicion'])?></td>
    <td><?=h($c['titulo'])?></td>
    <td><?=h($c['duracion'])?></td>
    <td><?=h($c['genero'])?></td>
  </tr>
  <?php endforeach; ?>
  <?php if(!$canciones): ?><tr><td colspan="4">Sin canciones.</td></tr><?php endif; ?>
</table>
