<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="Vista/css/styles.css">
</head>
<body>
    <h2>Editar Producto</h2>
    <form action="index.php?accion=actualizarProducto" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        <input type="number" name="precio" value="<?= htmlspecialchars($producto['precio']) ?>" required>
        <input type="text" name="descripcion" value="<?= htmlspecialchars($producto['descripcion']) ?>" required>
        <select name="id_categoria" required>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $producto['id_categoria'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="file" name="imagen">
        <button type="submit">Actualizar Producto</button>
    </form>
    <a href="index.php?accion=productos">Volver</a>
</body>
<footer>
    <p>&copy; 2025 Tienda de computadores y accesorios. Todos los derechos reservados.</p>
  </footer>
</html>