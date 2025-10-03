<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    include "JuegoClass.php";

    $miJuego = new Juego("The Last of Us Part II", 26, 49.99, "PS4", 1);
    echo "<strong>" . $miJuego->titulo . "</strong>";
    echo "<br>Precio: " . $miJuego->getPrecio() . " euros";
    echo "<br>Precio IVA incluido: " . $miJuego->getPrecioConIva() . " euros";
    echo "<br>" . $miJuego->muestraResumen();


    ?>
</body>

</html>