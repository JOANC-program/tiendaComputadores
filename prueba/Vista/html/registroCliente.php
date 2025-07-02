<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>
    <link rel="stylesheet" href="Vista/css/styles.css">
</head>
<body>
    <h2>Registro de Cliente</h2>
    <form action="index.php?accion=guardarCliente" method="post">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
    </form>
    <a href="index.php?accion=catalogo">Volver al catálogo</a>
</body>
</html>