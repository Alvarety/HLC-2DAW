<?php
    include("bdd.php");
    session_start();

    if (!isset($_SESSION['Correo'])) {
        header('Location: login.php');
        exit;
    }

    if (isset($_GET['CodPed'])) {
        $pedidoId = $_GET['CodPed'];

        $deleteProductos = $conexion->prepare("DELETE FROM pedidosproductos WHERE Pedido = ?");
        $deleteProductos->execute([$pedidoId]);
        
        $deletePedido = $conexion->prepare("DELETE FROM pedidos WHERE CodPed = ?");
        $deletePedido->execute([$pedidoId]);

        echo "<p>Pedido eliminado con éxito.</p>";
        header("Location: ver_pedidos2.php");
        exit;
    } else {
        echo "<p>El ID del pedido no es válido.</p>";
        exit;
    }
