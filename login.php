<?php
    include("bdd.php");
    session_start();
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $correo = $_POST['Correo'];
        $clave = $_POST['Clave'];

        $stmt = $conexion->prepare('SELECT CodRes, Correo, Clave, Pais, CP, Ciudad, Direccion, Rol FROM restaurantes WHERE Correo = :Correo');
        $stmt->execute(['Correo' => $correo]);
        $user = $stmt->fetch();

        if ($user && $clave == $user['Clave']) {
            $_SESSION['Correo'] = $user['Correo'];
            header("Location: opciones.php");
            exit();

        } else {
            echo "Correo o clave incorrecta.";
        }
    }
?>
<html>
    <head>
        <title>Login Form</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Nunito', Arial, sans-serif;
                font-size: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }

            .contact-form {
                max-width: 500px;
                min-width: 300px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f0f0f0;
                border: 1px solid #ccc;
                border-radius: 10px;
            }

            .contact-form h2 {
                color: black;
                font-weight: initial;
                text-align: center;
                margin-bottom: 20px;
            }

            form {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                border-radius: 8px;
            }

            form label {
                display: block;
                margin-bottom: 8px;
            }

            form input, form select, form text {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }

            form input[type="submit"]:hover{
                background-color: black;
                color: white;
            }

            form p {
                text-align: center; /* Centra el contenido dentro del párrafo */
            }

            form input[type="submit"] {
                cursor: pointer;
                width: 50%;
                padding: 10px;
                margin: 0 auto; /* Centra el botón horizontalmente */
                display: block; /* Asegura que el botón tome en cuenta el margen automático */
            }
        </style>
    </head>
    <body>
        <div class="contact-form">
            <h2>LOGIN</h2>
            <form method="POST" action="login.php">
                <label for="Correo">Correo:</label>
                <p>
                    <input type="text" id="Correo" name="Correo" required>
                </p>
                <label for="Clave">Clave:</label>
                <p>
                    <input type="password" id="Clave" name="Clave" required>
                </p>
                <p>
                    <input type="submit" value="Iniciar Sesion">
                </p>
            </form>
        </div>
    </body>
</html>