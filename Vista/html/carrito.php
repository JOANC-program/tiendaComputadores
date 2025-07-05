<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="Vista/css/styles.css">
</head>

<body>
    <header>
        <h1>Carrito de Compras</h1>
        <nav>
            <a href="index.php?accion=catalogo">Catálogo</a>
            <a href="index.php?accion=carrito">Carrito</a>
            <a href="index.php?accion=pedidoscliente">Pedidos</a>
            <a href="index.php?accion=cerrarSesion">Cerrar sesión</a>

        </nav>
    </header>
    <section>
        <?php if (empty($_SESSION['carrito'])): ?>
            <p>El carrito está vacío.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($_SESSION['carrito'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                            <td><?= htmlspecialchars($item['categoria']) ?></td>
                            <td>$<?= number_format($item['precio'], 0, ',', '.') ?></td>
                            <td><?= $item['cantidad'] ?></td>
                            <td>$<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?></td>
                            <td>
                                <form action="index.php?accion=eliminarCarrito" method="post" style="display:inline;">
                                    <input type="hidden" name="id_producto" value="<?= $item['id'] ?>">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <?php $total += $item['precio'] * $item['cantidad']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total: $<?= number_format($total, 0, ',', '.') ?></h3>
            <form action="index.php?accion=finalizarPedido" method="post">
                <button type="submit">Finalizar compra</button>
            </form>
        <?php endif; ?>
    </section>
</body>
<<<<<<< Updated upstream
<footer>
    <p>&copy; 2025 Tienda de computadores y accesorios. Todos los derechos reservados.</p>
  </footer>
=======

>>>>>>> Stashed changes

</html>