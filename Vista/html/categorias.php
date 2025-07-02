<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Categorías</title>
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
        <h2>Categorías</h2>
        <form class="form-admin" action="index.php?accion=guardarCategoria" method="post">
            <input type="text" name="nombre_categoria" placeholder="Nombre de la categoría" required>
            <button type="submit">Guardar Categoría</button>
        </form>
        <ul>
            <?php foreach ($categorias as $cat): ?>
                <li>
                    <?= htmlspecialchars($cat['nombre']) ?>
                    <a href="index.php?accion=editarCategoria&id=<?= $cat['id'] ?>">
                        <button type="button">Editar</button>
                    </a>
                    <a href="index.php?accion=eliminarCategoria&id=<?= $cat['id'] ?>"
                        onclick="return confirm('¿Seguro que deseas eliminar esta categoría?, se eliminaran los productos asociados.')(<?= $cat['id'] ?>);">
                        <button type="button">Eliminar</button>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</body>

</html>