<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Tienda Online</title>
    <link rel="stylesheet" href="Vista/css/styles.css">
    <style>
        /* Estilos básicos para la tabla de pedidos */
        .container {
            width: 80%;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .pedido-detalle {
            margin-top: 5px;
            font-size: 0.9em;
            color: #555;
        }
        .pedido-detalle strong {
            color: #333;
        }
        .no-pedidos {
            padding: 20px;
            text-align: center;
            color: #666;
            border: 1px solid #eee;
            background-color: #f9f9f9;
        }
        /* Mensajes de feedback */
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
 
    <header>
        <h1>Tienda computadores y repuestos</h1>
        <nav>

       

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

<div class="container">
        <h1>Mis Pedidos</h1>

        <?php 
        // Mostrar mensajes de éxito o error si vienen de la URL
        if (isset($_GET['mensaje'])) {
            if ($_GET['mensaje'] == 'pedido_creado') {
                echo '<div class="message success">¡Tu pedido ha sido creado exitosamente!</div>';
            } elseif ($_GET['mensaje'] == 'requiere_login_cliente') {
                echo '<div class="message error">Debes iniciar sesión como cliente para ver tus pedidos.</div>';
            }
            // Puedes agregar más mensajes si los defines en otras redirecciones
        }
        ?>

        <?php if (!empty($pedidos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Productos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                            <td>$<?php echo number_format(htmlspecialchars($pedido['total_pedido']), 2, ',', '.'); ?></td>
                            <td>
                                <?php if (!empty($pedido['productos'])): ?>
                                    <ul style="list-style: none; padding: 0; margin: 0;">
                                        <?php foreach ($pedido['productos'] as $producto): ?>
                                            <li class="pedido-detalle">
                                                <?php echo htmlspecialchars($producto['cantidad']); ?> x 
                                                <strong><?php echo htmlspecialchars($producto['nombre_producto'] . ' ' . $producto['modelo_producto']); ?></strong> 
                                                ($<?php echo number_format(htmlspecialchars($producto['precio_unitario']), 2, ',', '.'); ?> c/u)
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    Sin detalles de productos.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-pedidos">
                <p>No tienes pedidos registrados todavía.</p>
                <p><a href="index.php?accion=catalogo">Explora nuestro catálogo</a> para empezar a comprar.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>