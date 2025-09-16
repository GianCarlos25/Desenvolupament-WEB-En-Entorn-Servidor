<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="web/index.css">
    <title>Server</title>
</head>

<body>
    <?php include 'nav.inc.php'; ?>

    <table style="width:100%; border: 1px solid black; text-align: center;">
        <?php
        foreach ($_SERVER as $clave => $valor) {
            echo "<tr style='border: 1px solid black; text-align: center;'><td style='border: 1px solid black; text-align: center;'><strong>$clave</strong></td>";
            echo "<td style='border: 1px solid black; text-align: center;'>$valor</td></tr>";
        }
        ?>
    </table>
    <?php include 'footer.inc.php'; ?>

</body>

</html>