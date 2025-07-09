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
        <h1>Tienda de computadores y repuestos</h1>
        <nav>
            <a href="index.php?accion=productos">Productos</a>
            <a href="index.php?accion=categorias">Categorías</a>
            <a href="index.php?accion=pedidos">Pedidos</a>
            <a href="index.php?accion=dashboard">Dashboard</a>
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
                <input type="text" name="marca" placeholder="Marca" required>
                <input type="text" name="modelo" placeholder="Modelo" required>
                <select name="tipo" required>
                    <option value="">Seleccionar tipo</option>
                    <option value="Computador">Computador</option>
                    <option value="Repuesto">Repuesto</option>
                </select>
                <textarea name="especificaciones" placeholder="Especificaciones técnicas" required></textarea>
                <input type="number" name="precio" placeholder="Precio" required>
                <select name="id_categoria" required>
                    <option value="">Seleccionar categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input  type="file" name="imagenes[]" multiple>
                <button type="submit">Guardar Producto</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Tipo</th>
                        <th>Especificaciones</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto['id']) ?></td>
                            <td><?= htmlspecialchars($producto['marca']) ?></td>
                            <td><?= htmlspecialchars($producto['modelo']) ?></td>
                            <td><?= htmlspecialchars($producto['tipo']) ?></td>
                            <td><?= htmlspecialchars($producto['especificaciones']) ?></td>
                            <td><?= htmlspecialchars($producto['categorias']) ?></td>
                            <td>$<?= number_format($producto['precio'], 0, ',', '.') ?></td>
                            <td>
                                <?php if (!empty($producto['imagenes'])): ?>
                                    <?php foreach ($producto['imagenes'] as $img): ?>
                                        <img src="<?= htmlspecialchars($img) ?>" alt="Imagen" style="width:50px;height:50px;object-fit:cover;margin:2px;">
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span>Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?accion=editarProducto&id=<?= $producto['id'] ?>"><button type="button">Editar</button></a>
                                <a href="index.php?accion=eliminarProducto&id=<?= $producto['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                    <button type="button">Eliminar</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </section>

    <footer>
        <p>&copy; 2025 Tienda de Tenis. Todos los derechos reservados.</p>
    </footer>
</body>

</html>