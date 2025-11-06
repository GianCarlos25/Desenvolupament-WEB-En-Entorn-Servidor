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

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "INSERT INTO album (codigo,titulo,discografica,formato,fechaLanzamiento,fechaCompra,precio)
                VALUES (?,?,?,?,?,?,?)";
        $dwes->prepare($sql)->execute([
            (int) $_POST['codigo'],
            $_POST['titulo'] ?? '',
            $_POST['discografica'] ?? '',
            $_POST['formato'] ?? '',
            $_POST['fechaLanzamiento'] ?? null,
            $_POST['fechaCompra'] ?? null,
            (float) ($_POST['precio'] ?? 0),
        ]);
        header('Location: index.php?msg=' . urlencode('Álbum creado.'));
        exit;
    } catch (Throwable $e) {
        $msg = 'Error: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<meta charset="utf-8">
<title>Nuevo álbum</title>
<?php require $headerFile; ?>  

<h1>Nuevo álbum</h1>
<?php if ($msg)
    echo "<p>" . h($msg) . "</p>"; ?>
<form method="post">
    <p>Código: <input name="codigo" type="number" required value="<?= h($_POST['codigo'] ?? '') ?>"></p>
    <p>Título: <input name="titulo" required value="<?= h($_POST['titulo'] ?? '') ?>"></p>
    <p>Discográfica: <input name="discografica" value="<?= h($_POST['discografica'] ?? '') ?>"></p>
    <p>Formato: <input name="formato" value="<?= h($_POST['formato'] ?? '') ?>"></p>
    <p>Fecha lanzamiento: <input name="fechaLanzamiento" type="date" value="<?= h($_POST['fechaLanzamiento'] ?? '') ?>">
    </p>
    <p>Fecha compra: <input name="fechaCompra" type="date" value="<?= h($_POST['fechaCompra'] ?? '') ?>"></p>