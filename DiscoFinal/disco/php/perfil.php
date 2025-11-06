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

// --- Imagen pequeña por convención de nombres ---
$userFolder = preg_replace('/[^A-Za-z0-9_\-]/', '_', str_replace(' ', '_', $usuario));
$baseDir   = __DIR__ . "/../img/users/$userFolder";
$baseUrl   = "/img/users/$userFolder";
$imgUrl    = "/img/defaults/avatar-small.png";

if (is_dir($baseDir)) {
    foreach (['png','jpg','jpeg','webp'] as $ext) {
        $matches = glob("$baseDir/*Small.$ext");
        if ($matches && is_file($matches[0])) {
            $imgUrl = $baseUrl . '/' . basename($matches[0]);
            break;
        }
    }
}

$pageTitle = 'Perfil de ' . h($usuario);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $pageTitle ?></title>
  <style>
    body { font-family: sans-serif; text-align: center; padding: 2rem; background: #f4f4f4; }
    .perfil { display: inline-block; background: #fff; border-radius: 1rem; padding: 2rem; box-shadow: 0 2px 6px rgba(0,0,0,.1); }
    .perfil img { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; margin-bottom: 1rem; }
    .logout { margin-top: 1rem; }
  </style>
</head>
<body>

<?php require $headerFile; ?>

<div class="perfil">
  <img src="<?= h($imgUrl) ?>" alt="Avatar de <?= h($usuario) ?>">
  <h2>Hola, <?= h($usuario) ?></h2>
  <p>Bienvenido a tu perfil personal.</p>

  <div class="logout">
    <form action="index.php" method="post">
      <button type="submit">Inicio</button>
    </form>
  </div>
</div>

</body>
</html>
