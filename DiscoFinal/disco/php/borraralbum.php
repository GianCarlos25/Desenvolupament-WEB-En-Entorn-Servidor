<?php
require_once __DIR__ . '/auth.php';      // solo session + redirect
require_once __DIR__ . '/helpers.php';   // h()

$usuario = (string)($_SESSION['usuario'] ?? '');

// elegir header según sesión (ya garantizada por auth.php)
if (!empty($_SESSION['usuario'])) {
    $headerFile = __DIR__ . '/header-sesionActive.inc.php';
} else {
    $headerFile = __DIR__ . '/header.php';
}function h($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}
if (!isset($_GET['album'])) {
    die('Falta ?album');
}
$albumId = (int) $_GET['album'];

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

try {
    $dwes->beginTransaction();
    $delC = $dwes->prepare("DELETE FROM cancion WHERE album=?");
    $delC->execute([$albumId]);

    $delA = $dwes->prepare("DELETE FROM album WHERE codigo=?");
    $delA->execute([$albumId]);

    if ($delA->rowCount() === 0) {
        $dwes->rollBack();
        header('Location: album.php?codigo=' . $albumId . '&msg=' . urlencode('Álbum no encontrado'));
        exit;
    }
    $dwes->commit();
    header('Location: index.php?msg=' . urlencode('Álbum y canciones eliminados.'));
} catch (Throwable $e) {
    if ($dwes->inTransaction())
        $dwes->rollBack();
    header('Location: album.php?codigo=' . $albumId . '&msg=' . urlencode('Error: ' . $e->getMessage()));
}
