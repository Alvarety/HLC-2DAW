<?php
    $servidor = "localhost";
    $nombreBD = "pedidos";
    $usuario = "root";
    $contrasena = "1234";
    
    try {
        $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD;charset=utf8", $usuario, $contrasena);
    
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }