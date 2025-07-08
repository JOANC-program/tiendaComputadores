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
        <br>
        <label>Agregar nuevas imágenes:</label>
        <input type="file" name="imagenes[]" multiple>
        <button type="submit">Actualizar Producto</button>
    </form>

    <p>Imágenes actuales:</p>
    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
    <?php foreach ($imagenes as $id_img => $ruta_img): ?>
        <div style="display: flex; flex-direction: column; align-items: center;">
            <img src="<?= htmlspecialchars($ruta_img) ?>" class="img-miniatura" style="margin-bottom:4px;">
            <form action="index.php?accion=eliminarImagen" method="post" style="margin:0;">
                <input type="hidden" name="id_img" value="<?= $id_img ?>">
                <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                <button type="submit" class="btn-eliminar-img" title="Eliminar imagen" onclick="return confirm('¿Eliminar esta imagen?');"
                    style="background:#dc3545;color:#fff;border:none;border-radius:50%;width:24px;height:24px;font-size:16px;line-height:20px;cursor:pointer;padding:0;">×</button>
            </form>
        </div>
    <?php endforeach; ?>
    </div>

    <a href="index.php?accion=productos">Volver</a>
</body>
</html>