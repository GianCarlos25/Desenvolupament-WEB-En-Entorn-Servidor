<?php
    include '..2/nav.inc.php';

    echo 'El alumno se llama --> ';
    echo $_POST['name'] . '<br>';
    echo ' Su email es --> ';
    echo $_POST['email'] . '<br>';
    echo ' Y su mensaje es --> ';
    echo $_POST['message'] . '<br>';
    echo ' ¿Desea recibir una copia en su email? --> ';
    echo $_POST['copy'] ? 'Sí' : 'No' . '<br>';
    echo ' Y la fecha es --> ';
    echo $_POST['date'] . '<br>';

    include '../footer.inc.php';
?>