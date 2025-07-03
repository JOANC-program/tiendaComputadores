<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="Vista\css\styles.css">
</head>

<body>
    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'pedido_ok'): ?>
        <script>
            alert('¡Pedido realizado con éxito!');
        </script>
    <?php endif; ?>
    <header>
        <h1>Tienda de Tenis</h1>
        <nav>
            <a href="index.php?accion=catalogo">Catálogo</a>
            <!-- <a href="index.php?accion=loginAdmin">Zona Admin</a> -->
            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="index.php?accion=carrito">Ver carrito
            
                    <?php if (!empty($_SESSION['carrito'])): ?>
                        (<?= array_sum(array_column($_SESSION['carrito'], 'cantidad')) ?>)
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            <?php if (isset($_SESSION['usuario']) || isset($_SESSION['admin'])): ?>
                <a href="index.php?accion=cerrarSesion">Cerrar sesión</a>
                   <a href="index.php?accion=pedidoscliente">Pedidos</a>
            <?php endif; ?>
            <!--<a href="index.php?accion=registroCliente">Registrarse</a>-->
        </nav>
    </header>
    <section id="catalogo">
        <h2>Catálogo de Productos</h2>
        <form method="get" action="index.php" style="display: flex; gap: 1em; align-items: center;">
            <input type="hidden" name="accion" value="catalogo">
             <!--<label>Mostrar
                <select name="limite" onchange="this.form.submit()">
               <option value="6" <?= (isset($_GET['limite']) && $_GET['limite'] == 6) ? 'selected' : '' ?>>6</option>
                    <option value="9" <?= (isset($_GET['limite']) && $_GET['limite'] == 9) ? 'selected' : '' ?>>9</option>
                    <option value="12" <?= (isset($_GET['limite']) && $_GET['limite'] == 12) ? 'selected' : '' ?>>12</option>
                </select>
                productos por página
            </label> -->


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
                    <p>Marca: <?= htmlspecialchars($producto['marca']) ?></p>
                    <p>Modelo: <?= htmlspecialchars($producto['modelo']) ?></p>
                   
                    <p>especificaciones: <?= htmlspecialchars($producto['especificaciones']) ?></p>
                    <p>$<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                    <p>Modelo: <?= htmlspecialchars($producto['id_categoria']) ?></p>


                    <form action="index.php?accion=agregarCarrito" method="post" style="margin-bottom: 8px;">
                        <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                        <button type="submit">Agregar al carrito</button>
                    </form>
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