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
        <input type="text" name="marca" value="<?= htmlspecialchars($producto['marca']) ?>" required>
        <input type="text" name="modelo" value="<?= htmlspecialchars($producto['modelo']) ?>" required>
        <select name="tipo" required>
            <option value="Computador" <?= $producto['tipo'] == 'Computador' ? 'selected' : '' ?>>Computador</option>
            <option value="Repuesto" <?= $producto['tipo'] == 'Repuesto' ? 'selected' : '' ?>>Repuesto</option>
        </select>
        <textarea name="especificaciones" required><?= htmlspecialchars($producto['especificaciones']) ?></textarea>
        <input type="number" name="precio" value="<?= htmlspecialchars($producto['precio']) ?>" required>
        <select name="id_categoria" required>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $producto['id_categoria'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p>Imágenes actuales:</p>
        <?php
        foreach ($imagenes as $img): ?>
            <div style="display:inline-block;text-align:center;">
                <img src="<?= htmlspecialchars($img['ruta_imagen']) ?>" style="width:50px;height:50px;object-fit:cover;margin:2px;">
                <br>
                <a href="index.php?accion=eliminarImagen&id_img=<?= $img['id'] ?>&id_producto=<?= $producto['id'] ?>" onclick="return confirm('¿Eliminar esta imagen?');">Eliminar</a>
            </div>
        <?php endforeach; ?>
        <br>
        <label>Agregar nuevas imágenes:</label>
        <input type="file" name="imagenes[]" multiple>
        <button type="submit">Actualizar Producto</button>
    </form>
    <a href="index.php?accion=productos">Volver</a>
</body>
</html>