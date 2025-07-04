<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Pedido</title>
    <link rel="stylesheet" href="Vista/css/styles.css">
</head>
<body>
    <h2>Solicitar Pedido</h2>
    <form action="index.php?accion=procesarPedido" method="post">
        <input type="hidden" name="id_producto" value="<?= htmlspecialchars($id_producto) ?>">
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" value="1" min="1" required>
        <label for="correo">Correo:</label>
        <input type="email" name="correo" required>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>
        <button type="submit">Confirmar Pedido</button>
    </form>
    <a href="index.php?accion=catalogo">Volver al catálogo</a>
</body>
<footer>
    <p>&copy; 2025 Tienda de computadores y accesorios. Todos los derechos reservados.</p>
  </footer>
</html>