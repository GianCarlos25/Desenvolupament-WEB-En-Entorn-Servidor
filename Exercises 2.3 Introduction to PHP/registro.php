<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="web/index.css">
    <title>Document</title>
</head>
<?php

include 'nav.inc.php';

?>

<body>
    <!--

Comentarios de explicacion:

-Mediantes el metodo echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
se puede hacer que los datos introducidos en el formulario permanezcan en los campos del formulario tras enviarlo
si no se ha enviado nada, el campo permanece vacio

-Mediante este mismo metodo como echo (isset($_POST['terms']) && $_POST['terms'] === 'on') ? 'checked' : ''; podemos revisar si el checkbox estaba marcado o no
y con echo (isset($_POST['gender']) && $_POST['gender'] === 'male') ? 'selected' : ''; sabemos cual a selecionado

-->
    
    <h1>Formulario de Registro</h1>
    <form action="#" method="POST" id="registrationForm">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name"
            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
        <br>
        <label for="surname">Apellido:</label>
        <input type="text" id="surname" name="surname"
            value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>" required>
        <br>
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username"
            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirm_password">Confirmar Contraseña:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
        <br>
        <label for="birthdate">Fecha de Nacimiento:</label>
        <input type="date" id="birthdate" name="birthdate"
            value="<?php echo isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : ''; ?>" required>
        <br>
        <label for="gender">Género:</label>
        <select id="gender" name="gender" required>
            <option value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'male') ? 'selected' : ''; ?>>Masculino</option>
            <option value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'female') ? 'selected' : ''; ?>>Femenino</option>
            <option value="other" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'other') ? 'selected' : ''; ?>>Otro</option>
        </select>
        <br>
        <label for="terms">Acepto los términos y condiciones</label>
        <input type="checkbox" id="terms" name="terms" <?php echo (isset($_POST['terms']) && $_POST['terms'] === 'on') ? 'checked' : ''; ?> required>
        <br>
        <label for="newsletter">Acepto los envíos de publicidad</label>
        <input type="checkbox" id="newsletter" name="newsletter" <?php echo (isset($_POST['newsletter']) && $_POST['newsletter'] === 'on') ? 'checked' : ''; ?>>
        <br>
        <button type="submit">Registrar</button>
    </form>

    <?php

    /* 

    -Mediantes $_SERVER podemos comprobar si el formulario se ha enviado
    y asi validar los datos introducidos 

    -Con !filter_var($email, FILTER_VALIDATE_EMAIL) podemos validar el formato del email

    */


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $contraseña = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($contraseña !== $confirm_password) {
            echo '<p class="error_passw">Las contraseñas no coinciden</p>';
        }

        $email = $_POST['email'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<p class="error_email">El formato del correo no es válido</p>';
        }
    }

    include 'footer.inc.php';
    ?>

</body>

</html>