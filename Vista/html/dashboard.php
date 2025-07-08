<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="Vista/css/styles.css">
</head>

<body>
    <header>
        <h1>Tienda de Tenis</h1>
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
        <h2>Dashboard de Estadísticas</h2>
        <div style="width: 600px;">
            <canvas id="pedidosMes"></canvas>
        </div>
        <div style="width: 600px;">
            <canvas id="masVendidos"></canvas>
        </div>
        <script>
            // Variables globales para JS
            const pedidosMes = <?= json_encode($pedidosPorMes['data']) ?>;
            const meses = <?= json_encode($pedidosPorMes['labels']) ?>;
            const productos = <?= json_encode($masVendidos['labels']) ?>;
            const cantidades = <?= json_encode($masVendidos['data']) ?>;
        </script>
        <script src="Vista/script/script.js"></script>
</body>

</html>