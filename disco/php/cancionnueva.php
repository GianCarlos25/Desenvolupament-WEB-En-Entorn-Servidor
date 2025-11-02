<?php
require __DIR__.'/auth.php'; // bloquea si no hay sesión
function h($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

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

$albumId = isset($_POST['album']) ? (int) $_POST['album'] : (int) ($_GET['album'] ?? 0);
if (!$albumId)
    die('Falta ?album');

$alb = $dwes->prepare("SELECT codigo,titulo FROM album WHERE codigo=?");
$alb->execute([$albumId]);
$album = $alb->fetch();
if (!$album)
    die('Álbum inexistente');

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ins = $dwes->prepare("INSERT INTO cancion (titulo,album,posicion,duracion,genero) VALUES (?,?,?,?,?)");
        $ins->execute([
            $_POST['titulo'] ?? '',
            $albumId,
            (int) ($_POST['posicion'] ?? 0),
            $_POST['duracion'] ?? '00:00:00',
            $_POST['genero'] ?? ''
        ]);
        $msg = 'Canción guardada.';
        $_POST = []; // limpiar
    } catch (Throwable $e) {
        $msg = 'Error: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<meta charset="utf-8">
<title>Nueva canción</title>
<h1>Nueva canción para “<?= h($album['titulo']) ?>”</h1>
<?php if ($msg)
    echo "<p>" . h($msg) . "</p>"; ?>
<form method="post">
    <input type="hidden" name="album" value="<?= $albumId ?>">
    <p>Título: <input name="titulo" required value="<?= h($_POST['titulo'] ?? '') ?>"></p>
    <p>Posición: <input type="number" name="posicion" min="1" value="<?= h($_POST['posicion'] ?? 1) ?>"></p>
    <p>Duración (HH:MM:SS): <input name="duracion" placeholder="00:03:30" value="<?= h($_POST['duracion'] ?? '') ?>">
    </p>
    <p>Género: <input name="genero" value="<?= h($_POST['genero'] ?? '') ?>"></p>
    <p><button type="submit">Guardar</button> <a href="album.php?codigo=<?= $albumId ?>">Volver</a></p>
</form>




