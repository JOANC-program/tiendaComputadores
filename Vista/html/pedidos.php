<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Pedidos</title>
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
    <section>
        <h2>Pedidos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= htmlspecialchars($pedido['id']) ?></td>
                        <td><?= htmlspecialchars($pedido['usuario']) ?></td>
                        <td><?= htmlspecialchars($pedido['producto']) ?></td>
                        <td><?= htmlspecialchars($pedido['cantidad']) ?></td>
                        <td><?= htmlspecialchars($pedido['fecha']) ?></td>
                        <td>
                            <form action="index.php?accion=cambiarEstadoPedido" method="post" style="display:inline;">
                                <input type="hidden" name="id_pedido" value="<?= $pedido['id'] ?>">
                                <select name="estado" onchange="this.form.submit()">
                                    <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente
                                    </option>
                                    <option value="procesado" <?= $pedido['estado'] == 'procesado' ? 'selected' : '' ?>>En
                                        proceso</option>
                                    <option value="enviado" <?= $pedido['estado'] == 'enviado' ? 'selected' : '' ?>>Enviado
                                    </option>
                                    <option value="entregado" <?= $pedido['estado'] == 'entregado' ? 'selected' : '' ?>>Entregado
                                    </option>
                                    <option value="cancelado" <?= $pedido['estado'] == 'cancelado' ? 'selected' : '' ?>>Cancelado
                                    </option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
<footer>
    <p>&copy; 2025 Tienda de computadores y accesorios. Todos los derechos reservados.</p>
  </footer>

</html>