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

$q = $_GET['q'] ?? '';
$campo = $_GET['campo'] ?? 'ambos'; // cancion | album | ambos
$genero = $_GET['genero'] ?? '';     // vacío = todos
$res = [];

if (isset($_GET['buscar'])) {
    $where = [];
    $params = [];

    if ($q !== '') {
        if ($campo === 'cancion') {
            $where[] = "c.titulo LIKE ?";
            $params[] = "%$q%";
        } elseif ($campo === 'album') {
            $where[] = "a.titulo LIKE ?";
            $params[] = "%$q%";
        } else { // ambos
            $where[] = "(c.titulo LIKE ? OR a.titulo LIKE ?)";
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
    }
    if ($genero !== '') {
        $where[] = "c.genero = ?";
        $params[] = $genero;
    }

    $sql = "SELECT c.titulo AS cancion, a.titulo AS album, c.posicion, c.duracion, c.genero
            FROM cancion c JOIN album a ON a.codigo = c.album";
    if ($where)
        $sql .= " WHERE " . implode(" AND ", $where);
    $sql .= " ORDER BY a.titulo, c.posicion";

    try {
        $st = $dwes->prepare($sql);
        $st->execute($params);
        $res = $st->fetchAll();
    } catch (Throwable $e) {
        die('Error: ' . h($e->getMessage()));
    }
}
?>
<!doctype html>
<meta charset="utf-8">
<title>Buscar canciones</title>
<h1>Búsqueda de canciones</h1>
<form method="get">
    <p>Texto: <input name="q" value="<?= h($q) ?>"></p>
    <p>Buscar en:
        <label><input type="radio" name="campo" value="cancion" <?= ($campo === 'cancion' ? 'checked' : '') ?>> Títulos de
            canción</label>
        <label><input type="radio" name="campo" value="album" <?= ($campo === 'album' ? 'checked' : '') ?>> Nombres de
            álbum</label>
        <label><input type="radio" name="campo" value="ambos" <?= ($campo === 'ambos' ? 'checked' : '') ?>> Ambos</label>
    </p>
    <p>Género:
        <select name="genero">
            <option value="">(Todos)</option>
            <option <?= ($genero === 'Pop' ? 'selected' : '') ?>>Pop</option>
            <option <?= ($genero === 'Jazz' ? 'selected' : '') ?>>Jazz</option>
            <option <?= ($genero === 'Blues' ? 'selected' : '') ?>>Blues</option>
            <option <?= ($genero === 'Rock' ? 'selected' : '') ?>>Rock</option>
        </select>
    </p>
    <p><button type="submit" name="buscar" value="1">Buscar</button> <a href="index.php">Volver</a></p>
</form>

<?php if ($res): ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Álbum</th>
            <th>#</th>
            <th>Canción</th>
            <th>Duración</th>
            <th>Género</th>
        </tr>
        <?php foreach ($res as $r): ?>
            <tr>
                <td><?= h($r['album']) ?></td>
                <td><?= h($r['posicion']) ?></td>
                <td><?= h($r['cancion']) ?></td>
                <td><?= h($r['duracion']) ?></td>
                <td><?= h($r['genero']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif (isset($_GET['buscar'])): ?>
    <p>No se encontraron resultados.</p>
<?php endif; ?>