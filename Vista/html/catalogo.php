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
        <h1>Tienda de computadores y repuestos</h1>
        <nav>
            <?php if (isset($_SESSION['admin'])): ?>
                <a href="index.php?accion=productos">Productos</a>
                <a href="index.php?accion=categorias">Categorías</a>
                <a href="index.php?accion=pedidos">Pedidos</a>
                <a href="index.php?accion=dashboard">Dashboard</a>
                <a href="index.php?accion=carrito">Ver carrito
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
    <h2>Catálogo de Productos</h2>
    <form method="get" action="index.php">
        <input type="hidden" name="accion" value="catalogo">
        <select name="filtro_categoria" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= htmlspecialchars($cat['id']) ?>" <?= (isset($_GET['filtro_categoria']) && $_GET['filtro_categoria'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <div class="productos">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                <div class="producto">
                    <?php
                    // La modificación se realiza aquí:
                    if (!empty($producto['imagen'])):
                    ?>
                        <img src="Vista/img/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['marca'] . ' ' . $producto['modelo']) ?>">
                    <?php else: ?>
                        <img src="Vista/img/placeholder.png" alt="Imagen no disponible">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($producto['marca'] . ' ' . $producto['modelo']) ?></h3>
                    <p>Tipo: <?= htmlspecialchars($producto['tipo']) ?></p>
                    <p>$<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                    <p>Especificaciones Técnicas: <?= htmlspecialchars($producto['especificaciones']) ?></p>

                    <form action="index.php?accion=agregarCarrito" method="post" style="margin-bottom: 8px;">
                        <input type="hidden" name="id_producto" value="<?= htmlspecialchars($producto['id']) ?>">
                        <button type="submit">Agregar al carrito</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1 / -1;">No se encontraron productos en esta categoría o para esta búsqueda.</p>
        <?php endif; ?>
    </div>

    <div class="pagination">
        <?php
        // Construye los parámetros de la URL para mantener el filtro de categoría
        $base_url_params = "accion=catalogo";
        if (isset($_GET['filtro_categoria']) && $_GET['filtro_categoria'] !== '') {
            $base_url_params .= "&filtro_categoria=" . htmlspecialchars($_GET['filtro_categoria']);
        }

        // Enlace "Anterior"
        if ($pagina_actual > 1) {
            echo '<a href="index.php?' . $base_url_params . '&pagina=' . ($pagina_actual - 1) . '">Anterior</a>';
        } else {
            echo '<span class="disabled">Anterior</span>';
        }

        $rango_paginas = 2; // Mostrar 2 páginas antes y después de la actual
        $inicio_rango = max(1, $pagina_actual - $rango_paginas);
        $fin_rango = min($total_paginas, $pagina_actual + $rango_paginas);

        // Si hay muchas páginas al principio, mostrar el "1" y puntos suspensivos
        if ($inicio_rango > 1) {
            echo '<a href="index.php?' . $base_url_params . '&pagina=1">1</a>';
            if ($inicio_rango > 2) { // Mostrar puntos suspensivos si no es la página 2
                echo '<span class="disabled">...</span>';
            }
        }

        for ($i = $inicio_rango; $i <= $fin_rango; $i++) {
            if ($i == $pagina_actual) {
                echo '<span class="current-page">' . $i . '</span>';
            } else {
                echo '<a href="index.php?' . $base_url_params . '&pagina=' . $i . '">' . $i . '</a>';
            }
        }

        // Si hay muchas páginas al final, mostrar puntos suspensivos y la última página
        if ($fin_rango < $total_paginas) {
            if ($fin_rango < $total_paginas - 1) { // Mostrar puntos suspensivos si no es la penúltima página
                echo '<span class="disabled">...</span>';
            }
            echo '<a href="index.php?' . $base_url_params . '&pagina=' . $total_paginas . '">' . $total_paginas . '</a>';
        }

        // Enlace "Siguiente"
        if ($pagina_actual < $total_paginas) {
            echo '<a href="index.php?' . $base_url_params . '&pagina=' . ($pagina_actual + 1) . '">Siguiente</a>';
        } else {
            echo '<span class="disabled">Siguiente</span>';
        }
        ?>
    </div>
</body>
</html>