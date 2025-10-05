<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error 404 - Página no encontrada</title>
    <style>
        body {
            background: #f8f8f8;
            color: #333;
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 10%;
        }
        h1 {
            font-size: 5em;
            margin-bottom: 0.2em;
        }
        p {
            font-size: 1.5em;
            margin-bottom: 2em;
        }
        a {
            color: #007acc;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Lo sentimos, la página que buscas no existe. <?php e($msg); ?></p>
    <a href="/">Volver al inicio</a>
</body>
</html>