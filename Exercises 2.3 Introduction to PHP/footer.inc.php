<?php


$dia = date("d");
$mes = date("M");

$arrayFechas = array(
    "Dias" => array( 1 => "Lunes", 2 => "Martes", 3 => "Miércoles", 4 => "Jueves", 5 => "Viernes", 6 => "Sábado", 7 => "Domingo"),
    "Meses" => array("Jan" => "Enero", "Feb" => "Febrero", "Mar" => "Marzo", "Apr" => "Abril", "May" => "Mayo", "Jun" => "Junio", "Jul" => "Julio", "Aug" => "Agosto", "Sep" => "Septiembre", "Oct" => "Octubre", "Nov" => "Noviembre", "Dec" => "Diciembre"),
);

echo "<footer>
        <p>Hoy es " . $arrayFechas["Dias"][date("N")] . ", " . $dia . " de " . $arrayFechas["Meses"][date("M")] . " de " . date("Y") . ".</p>
    </footer>";
?></footer>