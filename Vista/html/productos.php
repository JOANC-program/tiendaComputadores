<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Vista/css/styles.css">

</head>

<body>
    <header>
        <h1>Tienda de Tenis</h1>
        <nav>
            <a href="index.php?accion=catalogo">Catálogo</a>
            <a href="index.php?accion=productos">Productos</a>
            <a href="index.php?accion=categorias">Categorías</a>
            <a href="index.php?accion=pedidos">Pedidos</a>
            <?php if (isset($_SESSION['admin'])): ?>
                <a href="index.php?accion=cerrarSesion">Cerrar sesión</a>
            <?php endif; ?>
        </nav>
    </header>
    <section id="panel-admin">
        <h2>Panel de Administración</h2>

        <div class="admin-section">
            <h3>Productos</h3>
            <form class="form-admin" action="index.php?accion=guardarProducto" method="post"
                enctype="multipart/form-data">
                <input type="text" name="nombre" placeholder="Nombre del producto" required>
                <input type="number" name="precio" placeholder="Precio" required>
                <input type="text" name="descripcion" placeholder="Talla" required>
                <select name="id_categoria" required>
                    <option value="">Seleccionar categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="file" name="imagen">
                <button type="submit">Guardar Producto</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Talla</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto['id']) ?></td>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td><?= htmlspecialchars($producto['categorias']) ?></td>
                            <td>$<?= number_format($producto['precio'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                            <td>
                                <a href="index.php?accion=editarProducto&id=<?= $producto['id'] ?>"><button
                                        type="button">Editar</button></a>
                                <a href="index.php?accion=eliminarProducto&id=<?= $producto['id'] ?>"
                                    onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                    <button type="button">Eliminar</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <div class="paginacion" style="text-align:center; margin:2rem 0;">
                        <?php if ($pagina > 1): ?>
                            <a href="index.php?accion=productos&pagina=<?= $pagina - 1 ?>">&laquo; Anterior</a>
                        <?php endif; ?>
                        <span>Página <?= $pagina ?> de <?= $totalPaginas ?></span>
                        <?php if ($pagina < $totalPaginas): ?>
                            <a href="index.php?accion=productos&pagina=<?= $pagina + 1 ?>">Siguiente &raquo;</a>
                        <?php endif; ?>
                    </div>
                </tbody>
            </table>
        </div>

    </section>

   <footer>
    <p>&copy; 2025 Tienda de computadores y accesorios. Todos los derechos reservados.</p>
  </footer>
</body>

</html>