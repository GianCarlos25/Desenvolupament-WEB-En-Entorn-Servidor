<!DOCTYPE html>
<html lang="español">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add</title>
</head>

<?php




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero1 = $_POST['numero1'];
    $numero2 = $_POST['numero2'];

    try {
        $suma = $numero1 + $numero2;
        echo "<p>La suma de los valores es: $suma</p>";
    } catch (Throwable $t) {
        echo 'Un error ocurrio: <br>' . 'Error 1: ' . $t->getMessage() . '<br>Error 2: linea ' . $t->getLine();
    }

}
?>

<body>
    <form action="#" method="POST">
        <label for="numero1">Numero 1:</label>
        <input type="text" name="numero1" id="numero1">
        <br>
        <label for="numero2">Numero 2:</label>
        <input type="text" name="numero2" id="numero2">
        <br>
        <input type="submit" value="Enviar">
</body>

</html>