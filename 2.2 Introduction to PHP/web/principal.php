<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Principal</title>
</head>

<body>
    <!-- Presentacion -->
    <header>
        <h1>Bienvenido a la Página Principal</h1>
    </header>
    <?php include '../nav.inc.php'; ?>
    <section>
        <!-- Sobre mi -->
        <article>
            <div>
                <img src="tumblr_pl6rvzShlq1vu24nz_400.jpg" alt="Mi-foto" class="profile-picture">
                <h2>Un poco sobre mi</h2>
                <p>Hola, Soy Gian Carlos, soy un apasionado de la tecnología y el desarrollo web. Me encanta aprender
                    nuevas habilidades y enfrentar desafíos.</p>
            </div>
        </article>

        <!-- Formulario -->
        <article>
            <div>
                <h2>Contacto</h2>
                <p>Rellena el formulario para ponerte en contacto conmigo o si prefieres escribeme un correo electronico
                    a <a href="mailto:giancarlos.sh25@gmail.com">giancarlos.sh25@gmail.com</a>.</p>
            </div>
            <div class="form-container">
                <form action="#">
                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="message">Mensaje:</label>
                    <textarea id="message" name="message" required></textarea>

                    <button type="submit">Enviar</button>
                </form>
            </div>
        </article>
    </section>
    <?php include '../footer.inc.php'; ?>

</body>

</html>