<?php
    include("bdd.php");
    session_start();
    if (!isset($_SESSION['Correo'])) {
        header('Location: login.php');
        exit;
    }

    $miConsulta = $conexion->prepare('SELECT CodProd, Nombre, Descripcion, Peso, Stock FROM productos;');
    $miConsulta->execute();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Listado de Productos</title>
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

            table {
                border-collapse: collapse;
                width: 100%;
            }

            table th {
                border: 1px solid black;
                text-align: center;
                padding: 1.3rem;
            }

            table td {
                border: 1px solid black;
                text-align: center;
                padding: 1.3rem;
            }

            .button {
                border-radius: .5rem;
                color: white;
                background-color: purple;
                padding: 1rem;
                text-decoration: none;
            }

            .table-container {
                max-width: 1200px;
                margin: 100px auto;
                padding: 20px;
                background-color: #f9f9f9;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            .table-container h1 {
                font-size: 2rem;
                color: #333;
                margin-bottom: 20px;
            }

            .table-container .button {
                display: inline-block;
                text-decoration: none;
                color: white;
                background-color: purple;
                padding: 10px 20px;
                font-size: 1rem;
                border-radius: 5px;
                transition: background-color 0.3s ease, transform 0.2s ease;
            }

            .table-container .button:hover {
                background-color: #5a2e7a;
                transform: translateY(-2px);
            }

            .table-container table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .table-container table th {
                background-color: #333;
                color: white;
                padding: 10px;
                font-size: 1rem;
                text-transform: uppercase;
            }

            .table-container table td {
                padding: 10px;
                text-align: center; 
                border-bottom: 1px solid #ddd;
                font-size: 0.9rem;
                color: #555; 
            }

            .table-container table tr:nth-child(even) {
                background-color: #f4f4f4;
            }

            .table-container table tr:nth-child(odd) {
                background-color: #fff;
            }

            .table-container table tr:hover {
                background-color: #eaeaea;
            }

            @media (max-width: 768px) {
                .table-container table {
                    font-size: 0.8rem;
                }

                .table-container .button {
                    font-size: 0.9rem;
                }
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
        <div class="table-container">
            <h1>Consulta de Productos</h1>
            <table>
                <tr>
                    <th>Codigo Productos</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Peso</th>
                    <th>Stock</th>
                </tr>
                <?php foreach($miConsulta as $clave => $valor): ?>
                    <tr>
                        <td><?= $valor['CodProd'] ?></td>
                        <td><?= $valor['Nombre'] ?></td>
                        <td><?= $valor['Descripcion'] ?></td>
                        <td><?= $valor['Peso'] ?></td>
                        <td><?= $valor['Stock'] ?></td>
                    </tr>
                <?php endforeach; ?>
                <a class="button" href="opciones.php">Volver</a>
            </table>
        </div>
    </body>
</html>