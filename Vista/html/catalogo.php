<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="Vista/css/styles.css">
    <link rel="stylesheet" href="Vista/css/styles.css">
</head>

<body>
    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'pedido_ok'): ?>
        <script>alert('¡Pedido realizado con éxito!');</script>
    <?php endif; ?>
    <header>
        <h1>Tienda de Tenis</h1>
        <nav>

            <?php if (isset($_SESSION['admin'])): ?>
                <a href="index.php?accion=productos">Productos</a>
                <a href="index.php?accion=categorias">Categorías</a>
                <a href="index.php?accion=pedidos">Pedidos</a>
                <a href="index.php?accion=dashboard">Dashboard</a>
                
               <?php /*<a href="index.php?accion=catalogo">Catálogo</a>
                 <a href="index.php?accion=carrito">Ver carrito*/ ?>

                    <?php if (!empty($_SESSION['carrito'])): ?>
                        (<?= array_sum(array_column($_SESSION['carrito'], 'cantidad')) ?>)
                    <?php endif; ?>
                 </a>

            <?php endif; ?>

            <?php if (isset($_SESSION['cliente'])): ?>
                <a href="index.php?accion=carrito">Ver carrito

                    <?php if (!empty($_SESSION['carrito'])): ?>
                        (<?= array_sum(array_column($_SESSION['carrito'], 'cantidad')) ?>)
                    <?php endif; ?>
                 </a>
                <a href="index.php?accion=pedidoscliente">Pedidos</a>

                <a href="index.php?accion=catalogo">Catálogo</a>
            <?php endif; ?>


            <?php if (isset($_SESSION['cliente']) || isset($_SESSION['admin'])): ?>
                <a href="index.php?accion=cerrarSesion">Cerrar sesión</a>

            <?php endif; ?>
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
                    <p>Modelo: <?= htmlspecialchars($producto['id_categoria']) ?></p>


                    <form action="index.php?accion=agregarCarrito" method="post" style="margin-bottom: 8px;">
                        <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                        <button type="submit">Agregar al carrito</button>
                    </form>
                   
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>


</html>