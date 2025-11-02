<?php

include 'header.php';
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
    echo 'Falló la conexión: ' . h($e->getMessage());
    exit;
}


$pass = $_POST['password'];

// use of crypt function
$hash = password_hash($pass, PASSWORD_DEFAULT);

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ins = $dwes->prepare("INSERT INTO tabla_usuarios (usuario, password) VALUES (?,?)");
        $ins->execute([
            $_POST['usuario'] ?? '',
            $hash
        ]);
        $msg = 'Usuario guardado.';
    } catch (Throwable $e) {
        $msg = 'Error: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<meta charset="utf-8">
<title>Registro</title>
<h1>Registro de nuevo usuario</h1>
<?php if ($msg)
    echo "<p>" . h($msg) . "</p>"; ?>
<form method="post" autocomplete="off">
    <p>Usuario: <input name="usuario" required value="<?= h($_POST['usuario'] ?? '') ?>"></p>
    <p>Contraseña: <input type="password" name="password" required></p>
    <p><button type="submit">Guardar</button></p>
</form>