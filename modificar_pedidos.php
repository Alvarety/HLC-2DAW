<?php
    include("bdd.php");
    session_start();

    if (!isset($_SESSION['Correo'])) {
        header('Location: login.php');
        exit;
    }

    $error = '';

    if (isset($_GET['CodPed'])) {
        $pedidoId = $_GET['CodPed'];

        $pedidoQuery = $conexion->prepare("SELECT * FROM pedidos WHERE CodPed = ?");
        $pedidoQuery->execute([$pedidoId]);
        $pedido = $pedidoQuery->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            $error = "Pedido no encontrado.";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fecha = $_POST['fecha'];
            $enviado = isset($_POST['enviado']) ? 1 : 0;

            $updatePedido = $conexion->prepare("UPDATE pedidos SET Fecha = ?, Enviado = ? WHERE CodPed = ?");
            $updatePedido->execute([$fecha, $enviado, $pedidoId]);

            echo "<p>Pedido actualizado con éxito.</p>";
            header("Location: ver_pedidos2.php");
            exit;
        }
    } else {
        $error = "No se ha seleccionado un pedido válido.";
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar Pedido</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Nunito', Arial, sans-serif;
                font-size: 20px;
                margin: 0;
            }

            .navbar {
                background-color: grey;
                color: black;
                padding: 10px 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .navbar h1 {
                padding-left: 20px;
                margin: 0;
            }

            .navbar .button-container {
                padding-right: 20px; 
            }

            .navbar .button {
                border-radius: .5rem;
                color: white;
                background-color: purple;
                padding: 1rem;
                text-decoration: none;
                margin-left: 10px; 
                transition: background-color 0.3s ease, transform 0.2s ease;
            }

            .navbar .button:hover {
                background-color: #5a2e7a;
                transform: translateY(-2px);
            }

            form {
                width: 500px;
                margin: 40px auto;
                background-color: #f9f9f9;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            label {
                display: block;
                margin-bottom: 8px;
            }

            input, select {
                width: 90%;
                padding: 20px;
                margin-bottom: 15px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            button {
                padding: 10px 20px;
                background-color: purple;
                font-family: 'Nunito', Arial, sans-serif;
                border-radius: 8px;
                color: white;
                font-weight: bold;
                border: none;
                cursor: pointer;
                width: 50%;
                margin: 0 auto;
                display: block;
            }

            button:hover {
                background-color: #5a2e7a;
            }

            .error {
                color: red;
                font-size: 14px;
            }

            .form-container .button2 {
                border-radius: .5rem;
                color: white;
                background-color: purple;
                padding: 1rem;
                text-decoration: none;
                margin-left: 10px;
                transition: background-color 0.3s ease, transform 0.2s ease;
                display: block;
                margin: auto 725px;
            }

            .form-container .button2:hover {
                background-color: #5a2e7a;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <h1>¡Bienvenido!</h1>
            <div class="button-container">
                <a class="button" href="">Opciones</a>
                <a class="button" href="">Usuario</a>
                <a class="button" href="logout.php">Cerrar Sesion</a>
            </div>
        </div>

        <div class="form-container">
            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php else: ?>
                <form method="POST">
                    <label for="fecha">Fecha del Pedido:</label>
                    <input type="datetime-local" name="fecha" id="fecha" value="<?= $pedido['Fecha'] ?>" required>

                    <label for="enviado">¿Enviado?</label>
                    <input type="checkbox" name="enviado" id="enviado" <?= $pedido['Enviado'] == 1 ? 'checked' : '' ?>>

                    <button type="submit">Actualizar Pedido</button>
                </form>
            <?php endif; ?>
            <a class="button2" href="ver_pedidos2.php">Volver</a>
        </div>
    </body>
</html>
