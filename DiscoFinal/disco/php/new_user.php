<?php
include 'header.php';

// Mostrar errores en pantalla (solo para depurar)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Función para escapar texto en HTML
function h($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

// Conexión PDO
$opc = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];
try {
    $dwes = new PDO('mysql:host=localhost;dbname=discografia;charset=utf8mb4', 'root', '', $opc);
} catch (PDOException $e) {
    exit('Falló la conexión: ' . h($e->getMessage()));
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // Encriptar contraseña
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // --- Insertar usuario ---
    try {
        $ins = $dwes->prepare("INSERT INTO tabla_usuarios (usuario, password) VALUES (?,?)");
        $ins->execute([$usuario, $hash]);
        $userId = $dwes->lastInsertId(); // lo usamos para nombrar las imágenes
        $msg = 'Usuario guardado.';
    } catch (Throwable $e) {
        exit('Error al guardar usuario: ' . $e->getMessage());
    }

    // --- Comprobar archivo subido ---
    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
        exit('Error al subir la imagen.');
    }

    // --- Validar tipo de imagen ---
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES['archivo']['tmp_name']);
    $allowed = ['jpg' => 'image/jpeg', 'png' => 'image/png'];
    $ext = array_search($mime, $allowed, true);
    if ($ext === false) {
        exit('Tipo de archivo no permitido (solo JPG o PNG).');
    }

    // --- Validar dimensiones ---
    $info = getimagesize($_FILES['archivo']['tmp_name']);
    if ($info === false) exit('No es una imagen válida.');
    list($w, $h) = $info;
    if ($w > 360 || $h > 480) {
        $msg .= ' (La imagen fue redimensionada automáticamente)';
    }

    // --- Crear carpeta del usuario ---
    $safeUser = preg_replace('/[^a-z0-9_\-]/i', '_', $usuario);
    // 👇 IMPORTANTE: ../ para subir un nivel y crear en disco/img/users/
    $baseDir = __DIR__ . "/../img/users/$safeUser";
    if (!is_dir($baseDir)) {
        mkdir($baseDir, 0775, true);
    }

    // --- Crear imagen desde el archivo ---
    $src = ($ext === 'jpg')
        ? imagecreatefromjpeg($_FILES['archivo']['tmp_name'])
        : imagecreatefrompng($_FILES['archivo']['tmp_name']);

    // --- Función para redimensionar ---
    function resize($src, $w, $h) {
        $dst = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, imagesx($src), imagesy($src));
        return $dst;
    }

    // --- Crear las dos versiones ---
    $imgBig = resize($src, 360, 480);
    $imgSmall = resize($src, 72, 96);

    // --- Rutas de destino ---
    $pathBig = "$baseDir/{$userId}Big.png";
    $pathSmall = "$baseDir/{$userId}Small.png";

    // --- Guardar imágenes ---
    imagepng($imgBig, $pathBig);
    imagepng($imgSmall, $pathSmall);

    // --- Guardar rutas relativas (para mostrar luego) ---
    $pathBigDB = "img/users/$safeUser/{$userId}Big.png";
    $pathSmallDB = "img/users/$safeUser/{$userId}Small.png";

    $msg .= ' Imagen guardada correctamente.';
    sleep(3);
    header('Location: http://disco.local/php/inicio_sesion.php');

}
?>
<!doctype html>
<meta charset="utf-8">
<title>Registro</title>
<h1>Registro de nuevo usuario</h1>

<?php if ($msg) echo "<p>" . h($msg) . "</p>"; ?>

<form method="post" enctype="multipart/form-data" autocomplete="off">
    <p>Usuario: <input name="usuario" required value="<?= h($_POST['usuario'] ?? '') ?>"></p>
    <p>Contraseña: <input type="password" name="password" required></p>
    <p>Imagen de perfil: <input type="file" name="archivo" accept=".jpg,.jpeg,.png" required></p>
    <p><button type="submit">Guardar</button></p>
</form>
