<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="Vista/css/styles.css">
</head>

<body>
    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'pedido_ok'): ?>
        <script>alert('¡Pedido realizado con éxito!');</script>
    <?php endif; ?>
    <header>
        <h1>Tienda de Tenis</h1>
        <nav>
            <a href="index.php?accion=catalogo">Catálogo</a>
            <a href="index.php?accion=loginAdmin">Zona Admin</a>
            <a href="index.php?accion=registroCliente">Registrarse</a>
        </nav>
    </header>
    <section id="catalogo">
        <h2>Catálogo de Productos</h2>
        <form method="get" action="index.php">
            <input type="hidden" name="accion" value="catalogo">
            <select name="filtro_categoria" onchange="this.form.submit()">
                <option value="">Todas las categorías</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_GET['filtro_categoria']) && $_GET['filtro_categoria'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <div class="productos">
            <?php foreach ($productos as $producto): ?>
                <div class="producto">
                    <?php if (!empty($producto['imagen'])): ?>
                        <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                    <p>Categoría: <?= htmlspecialchars($producto['categorias']) ?></p>
                    <p>Talla: <?= htmlspecialchars($producto['descripcion']) ?></p>
                    <p>$<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                    <form action="index.php?accion=formularioPedido" method="post">
                        <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                        <button type="submit">Solicitar compra</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>

</html>