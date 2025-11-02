<?php
session_start();

include 'header.php';
function h($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

$opc = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $dwes = new PDO('mysql:host=localhost;dbname=discografia;charset=utf8mb4', 'root', '', $opc);
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . h($e->getMessage());
    exit;
}

$msg = '';

$cookie_name = 'user';
$cookie_opts = [
    'expires' => time() + 86400 * 30, // 30 días (ajusta si quieres)
    'path' => '/',
    'secure' => false,               // en localhost sin HTTPS -> false. En prod con HTTPS -> true
    'httponly' => true,
    'samesite' => 'Lax',
];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['opciones'])) {
    if ($_POST['opciones'] === 'yes') {
        echo 'Acceso correcto';
        exit;
    } else { // 'no' -> borrar cookie y mostrar formulario
        setcookie($cookie_name, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => false, // en prod con https -> true
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        unset($_COOKIE[$cookie_name]); // limpiar en esta petición
        $msg = 'Cookie eliminada. Inicia sesión de nuevo.';
        session_destroy();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario']) && isset($_POST['password']) && !isset($_POST['opciones'])) {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $msg = 'Usuario y contraseña son obligatorios.';
    } else {
        try {
            $select = $dwes->prepare("SELECT password FROM tabla_usuarios WHERE usuario = ?");
            $select->execute([$usuario]);
            $row = $select->fetch();

            if ($row && password_verify($password, $row['password'])) {
                // Guardar cookie
                setcookie($cookie_name, $usuario, $cookie_opts);
                // Hacer disponible en esta misma carga (sin esperar al próximo request)
                $_COOKIE[$cookie_name] = $usuario;
                $msg = 'Login correcto.';
                $_SESSION['usuario'] = $usuario;
            } else {
                $msg = 'Usuario o contraseña incorrectos.';
            }
        } catch (Throwable $e) {
            $msg = 'Error: ' . h($e->getMessage());
        }
    }
}


if (!empty($msg)) {
    echo '<p>' . h($msg) . '</p>';
}

if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] !== '') {
    // Mostrar prompt "¿Quieres iniciar sesión como...?"
    echo "Do you want to log in as " . h($_COOKIE[$cookie_name]) . "?";
    echo "<form method='post' action='#'>";
    echo "  <select name='opciones'>";
    echo "    <option value='yes'>Yes</option>";
    echo "    <option value='no'>No</option>";
    echo "  </select>";
    echo "  <input type='submit' value='Submit'>";
    echo "</form>";
} else {
    // Mostrar formulario de login
    echo "<h2>Iniciar sesión</h2>";
    echo "<form method='post' action='#'>";
    echo "  Usuario: <input type='text' name='usuario'><br>";
    echo "  Contraseña: <input type='password' name='password'><br>";
    echo "  <input type='submit' value='Iniciar sesión'>";
    echo "</form>";
}
