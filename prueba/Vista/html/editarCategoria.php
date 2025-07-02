<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="Vista/css/styles.css">
</head>
<body>
    <h2>Editar Categoría</h2>
    <form action="index.php?accion=actualizarCategoria" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($categoria['id']) ?>">
        <input type="text" name="nombre_categoria" value="<?= htmlspecialchars($categoria['nombre']) ?>" required>
        <button type="submit">Actualizar Categoría</button>
    </form>
    <a href="index.php?accion=categorias">Volver</a>
</body>
</html>