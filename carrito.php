<?php
    include("bdd.php");
    session_start();
    if (!isset($_SESSION['Correo'])) {
        header('Location: login.php');
        exit;
    }

    $productosQuery = $conexion->query("SELECT * FROM productos");
    $productos = $productosQuery->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar el carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Agregar producto al carrito
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
        $productoId = $_POST['producto'];
        $cantidad = $_POST['cantidad'];

        // Verificar stock del producto
        $productoQuery = $conexion->prepare("SELECT * FROM productos WHERE CodProd = ?");
        $productoQuery->execute([$productoId]);
        $producto = $productoQuery->fetch(PDO::FETCH_ASSOC);

        if ($producto && $producto['Stock'] >= $cantidad) {
            // Agregar al carrito
            if (isset($_SESSION['carrito'][$productoId])) {
                $_SESSION['carrito'][$productoId] += $cantidad;
            } else {
                $_SESSION['carrito'][$productoId] = $cantidad;
            }
        } else {
            echo "<p>Error: La cantidad excede el stock disponible.</p>";
        }
    }

    // Confirmar pedido
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
        $fecha = $_POST['fecha'];
        $enviado = isset($_POST['enviado']) ? 1 : 0;

        if (!empty($_SESSION['carrito'])) {
            // Crear el pedido
            $insertPedido = $conexion->prepare("INSERT INTO pedidos (Restaurante, Fecha, Enviado) VALUES (1, ?, ?)");
            $insertPedido->execute([$fecha, $enviado]);
            $pedidoId = $conexion->lastInsertId();

            // Guardar productos del carrito en pedidosproductos
            foreach ($_SESSION['carrito'] as $productoId => $cantidad) {
                $insertProducto = $conexion->prepare("INSERT INTO pedidosproductos (Pedido, Producto, Unidades) VALUES (?, ?, ?)");
                $insertProducto->execute([$pedidoId, $productoId, $cantidad]);

                // Actualizar el stock del producto
                $updateStock = $conexion->prepare("UPDATE productos SET Stock = Stock - ? WHERE CodProd = ?");
                $updateStock->execute([$cantidad, $productoId]);
            }

            // Vaciar el carrito
            $_SESSION['carrito'] = [];
            echo "<p>Pedido creado con éxito. ID del pedido: $pedidoId</p>";
        } else {
            echo "<p>Error: El carrito está vacío.</p>";
        }
    }

    // Eliminar producto del carrito
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
        $productoId = $_POST['producto_id'];
        unset($_SESSION['carrito'][$productoId]);
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carrito de Pedidos</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f9f9f9;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            }

            .container {
                background-color: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                width: 100%;
                margin-bottom: 20px;
            }

            .container h1 {
                margin-bottom: 20px;
                text-align: center;
                font-size: 24px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            table th, table td {
                border: 1px solid #ccc;
                padding: 8px;
                text-align: center;
            }

            .form-container input,
            .form-container select,
            .form-container button {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            .form-container button {
                background-color: purple;
                color: white;
                font-weight: bold;
                border: none;
                cursor: pointer;
            }

            .form-container button:hover {
                background-color: darkpurple;
            }
        </style>
    </head>
    <body>
        <!-- Formulario para agregar productos -->
        <div class="container">
            <h1>Carrito de Pedidos</h1>
            <form method="POST">
                <label for="producto">Producto:</label>
                <select name="producto" id="producto" required>
                    <option value="">Seleccionar Producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['CodProd'] ?>">
                            <?= $producto['Nombre'] ?> (Stock: <?= $producto['Stock'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select><br>

                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" min="1" required><br>

                <button type="submit" name="agregar">Agregar al Carrito</button>
            </form>
        </div>

        <!-- Mostrar productos en el carrito -->
        <div class="container">
            <h1>Productos en el Carrito</h1>
            <?php if (!empty($_SESSION['carrito'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['carrito'] as $productoId => $cantidad): ?>
                            <?php
                            $productoQuery = $pdo->prepare("SELECT * FROM productos WHERE CodProd = ?");
                            $productoQuery->execute([$productoId]);
                            $producto = $productoQuery->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <tr>
                                <td><?= $producto['Nombre'] ?></td>
                                <td><?= $cantidad ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="producto_id" value="<?= $productoId ?>">
                                        <button type="submit" name="eliminar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>El carrito está vacío.</p>
            <?php endif; ?>
        </div>

        <!-- Formulario para confirmar pedido -->
        <div class="container">
            <h1>Confirmar Pedido</h1>
            <form method="POST">
                <label for="fecha">Fecha:</label>
                <input type="datetime-local" name="fecha" id="fecha" required>

                <label for="enviado">¿Enviado?</label>
                <input type="checkbox" name="enviado" id="enviado">

                <button type="submit" name="confirmar">Confirmar Pedido</button>
            </form>
        </div>
    </body>
</html>
