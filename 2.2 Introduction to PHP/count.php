<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="web/index.css">
    <title>count</title>
</head>

<body>
    <?php include 'nav.inc.php'; ?>

    <h1>Números del 0 al 30</h1>

    <?php
    $cont = 0;
    while ($cont <= 30) {
        echo "$cont\n";
        $cont++;
    }
    echo"<br><br>"
    ?>

    <?php
    $factOriginal = 5;
    $factNuevo = 1;

    for ($i = 1; $i <= $factOriginal; $i++) {
        $factNuevo *= $i;
    }

    echo "<h1>El factorial de $factOriginal</h1><br><br>";
    echo "$factOriginal! = 5 x 4 x 3 x 2 x 1 = $factNuevo.<br><br>"
    ?>
    
    <?php include 'footer.inc.php'; ?>

</body>

</html>