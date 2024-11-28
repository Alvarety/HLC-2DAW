<?php
    include("bdd.php");
    session_start();

    if (!isset($_SESSION['Correo'])) {
        header('Location: login.php');
        exit;
    }

    $restauranteQuery = $conexion->prepare("SELECT CodRes FROM restaurantes WHERE Correo = ?");
    $restauranteQuery->execute([$_SESSION['Correo']]);
    $restaurante = $restauranteQuery->fetch(PDO::FETCH_ASSOC);

    if (!$restaurante) {
        echo "Error: Restaurante no encontrado.";
        exit;
    }

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (isset($_POST['agregar_producto'])) {
        $productoId = $_POST['producto'];
        $cantidad = $_POST['cantidad'];

        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] == $productoId) {
                $item['cantidad'] += $cantidad;
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            $_SESSION['carrito'][] = [
                'id' => $productoId,
                'cantidad' => $cantidad
            ];
        }
    }


    // Eliminar un producto del carrito
    if (isset($_POST['eliminar_producto'])) {
        $productoId = $_POST['producto_id'];
        foreach ($_SESSION['carrito'] as $indice => $item) {
            if ($item['id'] == $productoId) {
                unset($_SESSION['carrito'][$indice]); // Eliminar el producto
                $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar el array
                break;
            }
        }
    }

    if (isset($_POST['guardar_pedido'])) {
        if (count($_SESSION['carrito']) > 0) {
            $fecha = date('Y-m-d H:i:s');
            $enviado = 0;

            $insertPedido = $conexion->prepare("INSERT INTO pedidos (Fecha, Enviado, Restaurante) VALUES (?, ?, ?)");
            $insertPedido->execute([$fecha, $enviado, $restaurante['CodRes']]);

            $pedidoId = $conexion->lastInsertId();

            foreach ($_SESSION['carrito'] as $producto) {
                $insertProducto = $conexion->prepare("INSERT INTO pedidosproductos (Pedido, Producto, Unidades) VALUES (?, ?, ?)");
                $insertProducto->execute([$pedidoId, $producto['id'], $producto['cantidad']]);
            }

            $_SESSION['carrito'] = [];

            header("Location: opciones.php");
            exit;
        } else {
            echo "<p>No hay productos en el carrito.</p>";
        }
    }

    $productosQuery = $conexion->query("SELECT * FROM productos");
    $productos = $productosQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hacer Pedido</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Nunito', Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f5f5f5;
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

            .container {
                max-width: 800px;
                margin: 20px auto;
                padding: 20px;
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .container h2 {
                text-align: center;
            }

            form {
                margin-bottom: 20px;
            }

            label, select {
                display: block;
                margin: 10px 0;
                width: 100%;
                padding: 8px;
                font-size: 16px;
            }

            input {
                display: block;
                margin: 10px 0;
                width: 97%;
                padding: 8px;
                font-size: 16px;
            }

            button {
                padding: 10px 15px;
                background-color: purple;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 100%;
                margin-top: 10px;
                font-family: 'Nunito', Arial, sans-serif;
                font-weight: bold;
            }

            button:hover {
                background-color: #5a2e7a;
            }

            .carrito h3 {
                text-align: center;
            }

            .carrito table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .carrito table th, .carrito table td {
                padding: 10px;
                border: 1px solid #ddd;
                text-align: left;
            }

            .carrito table th {
                background-color: #333;
                color: white;
            }

            .carrito .button {
                border-radius: .5rem;
                color: white;
                background-color: purple;
                padding: 1rem;
                text-decoration: none;
                margin-left: 10px; 
                transition: background-color 0.3s ease, transform 0.2s ease;
                display: block;
                margin: auto 360px;
            }

            .carrito .button:hover {
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

        <div class="container">
            <h2>Agregar Producto</h2>
            <form method="POST">
                <label for="producto">Producto:</label>
                <select name="producto" id="producto" required>
                    <option value="">Seleccionar Producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['CodProd'] ?>">
                            <?= $producto['Nombre'] ?> - Stock: <?= $producto['Stock'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" min="1" required>

                <button type="submit" name="agregar_producto">Agregar al Carrito</button>
            </form>

            <div class="carrito">
                <h3>Carrito de Compras</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['carrito'] as $item): ?>
                            <?php 
                            $productoInfo = array_filter($productos, fn($prod) => $prod['CodProd'] == $item['id']);
                            $productoInfo = reset($productoInfo);
                            ?>
                            <tr>
                                <td><?= $productoInfo['Nombre'] ?></td>
                                <td><?= $item['cantidad'] ?></td>
                                <td>
                                <!-- Botón para eliminar producto -->
                                <form method="POST">
                                    <input type="hidden" name="producto_id" value="<?= $item['id'] ?>">
                                    <button type="submit" name="eliminar_producto">Eliminar</button>
                                </form>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <form method="POST">
                    <button type="submit" name="guardar_pedido">Guardar Pedido</button>
                </form>
                <a class="button" href="opciones.php">Volver</a>
            </div>
        </div>
    </body>
</html>
