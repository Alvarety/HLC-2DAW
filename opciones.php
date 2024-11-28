<?php
    include("bdd.php");
    session_start();
    if (!isset($_SESSION['Correo'])) {
        header('Location: login.php');
        exit;
    }

?>
<html>
    <head>
        <title>Menu de Opciones</title>
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

            .options-menu {
                max-width: 400px; 
                margin: 100px auto;
                padding: 20px;
                background-color: #f9f9f9;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            .options-menu h2 {
                color: #333;
                margin-bottom: 20px;
                font-size: 1.8rem;
            }

            .options-menu ol {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .options-menu li {
                margin-bottom: 15px;
            }

            .options-menu a {
                display: block;
                text-decoration: none;
                color: black;
                background-color: grey; 
                padding: 10px 20px;
                border-radius: 25px; 
                font-size: 1.2rem;
                transition: background-color 0.3s ease, transform 0.2s ease;
            }

            .options-menu a:hover {
                background-color: #333;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        <div class="navbar">
            <h1>¡Bienvenido!</h1>
            <div class="button-container">
                <a class="button" href="">Options</a>
                <a class="button" href="">Username</a>
                <a class="button" href="logout.php">Cerrar Sesion</a>
            </div>
        </div>
        <div class="options-menu">
            <h2>Menú de Opciones</h2>
            <ol>
                <li><a href="ver_pedidos2.php">Modificar Pedidos</a></li>
                <li><a href="hacer_pedidos.php">Hacer Pedidos</a></li>
                <li><a href="ver_pedidos.php">Consultar Pedidos</a></li>
                <li><a href="ver_categorias.php">Consultar Categorias</a></li>
                <li><a href="ver_productos.php">Consultar Productos</a></li>
            </ol>
        </div>
    </body>
</html>