<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="Vista/css/styles.css">
    <style>
        /* Puedes mantener o ajustar los estilos si lo necesitas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-eliminar-individual {
            background-color: #dc3545;
            /* Rojo */
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.85em;
        }

        .btn-eliminar-individual:hover {
            background-color: #c82333;
        }

        .finalizar-compra-btn {
            background-color: #28a745;
            /* Verde */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 20px;
        }

        .finalizar-compra-btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <?php
    // Asegúrate de que la sesión esté iniciada y que la variable de sesión esté configurada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    ?>
    <header>
        <h1>Carrito de Compras</h1>
        <nav>
            <?php if (isset($_SESSION['admin'])): ?>
                <a href="index.php?accion=productos">Productos</a>
                <a href="index.php?accion=categorias">Categorías</a>
                <a href="index.php?accion=pedidos">Pedidos</a>
                <a href="index.php?accion=dashboard">Dashboard</a>
                <a href="index.php?accion=catalogo">Catálogo</a>
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

           
            <?php
            // Asegúrate de que este enlace solo se muestre si hay una sesión activa
            if (isset($_SESSION['usuario_rol'])) { // Usando la variable de rol única
                echo '<a href="index.php?accion=cerrarSesion">Cerrar sesión</a>';
            }
            ?>
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
                    <?php foreach ($_SESSION['carrito'] as $key => $item): // Usamos $key para identificar el índice del producto 
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                            <td><?= htmlspecialchars($item['categoria']['nombre'] ?? 'N/A') ?></td>
                            <td>$<?= number_format($item['precio'], 0, ',', '.') ?></td>
                            <td><?= $item['cantidad'] ?></td>
                            <td>$<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?></td>
                            <td>
                                <form action="index.php?accion=eliminarCarrito" method="post" style="display:inline;">
                                    <input type="hidden" name="indice_producto_a_eliminar" value="<?= $key ?>">
                                    <button type="submit" class="btn-eliminar-individual">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <?php $total += $item['precio'] * $item['cantidad']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Total: $<?= number_format($total, 0, ',', '.') ?></h3>

            <form action="index.php?accion=finalizarPedido" method="post">
                <button type="submit" class="finalizar-compra-btn">Finalizar compra</button>
            </form>

        <?php endif; ?>
        <?php if (isset($_GET['mensaje'])): ?>
            <?php if ($_GET['mensaje'] == 'carrito_vacio'): ?>
                <div style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                    Tu carrito está vacío. Agrega productos antes de finalizar la compra.
                </div>
            <?php elseif ($_GET['mensaje'] == 'error_al_crear_pedido'): ?>
                <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                    Hubo un error al procesar tu pedido. Por favor, inténtalo de nuevo.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</body>

</html>