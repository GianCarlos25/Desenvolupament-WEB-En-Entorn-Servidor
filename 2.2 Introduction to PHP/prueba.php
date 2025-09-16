<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Primera prueba php</title>
</head>

<body>
    Este es un archivo php que se encuentra en el servidor.
</body>

<?php
$valor = 5;
$valorSumar = 3;
$valorTotal= 0;
echo "<br>";
echo "Hello, World!";
echo "<br>";
echo "Esto es una suma";
echo "<br>";    
echo "2 + 2 = " . (2 + 2);
echo "<br>";
echo "5 * 5 = " . (5 * 5);
echo "<br>";
echo "El valor actual del total es : " . $valorTotal;
echo "<br>";
echo "$valor + $valorSumar = " . ($valor + $valorSumar);
$valorTotal = $valor + $valorSumar;
echo "<br>";
echo "El valor total es: " . $valorTotal;
?>

</html>
