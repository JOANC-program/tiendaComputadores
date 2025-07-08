<?php
// Modelo/GestorPedidos.php

require_once 'Conexion.php'; 

class GestorPedidos
{
    private $conexionObj; 

    public function __construct() {
        $this->conexionObj = new Conexion(); 
    }

    /**
     * Inserta una línea de producto en la tabla 'pedidos'.
     * Esta función actúa como el método para añadir CADA producto del carrito
     * a la tabla 'pedidos', que en tu esquema es la tabla de detalles.
     *
     * @param int $id_usuario El ID del usuario.
     * @param int $id_producto El ID del producto.
     * @param int $cantidad La cantidad del producto.
     * @param float $precio_unitario El precio unitario del producto (lo necesitamos para calcular el total del pedido).
     * @return int|bool El ID del registro insertado (la línea de pedido) si es exitoso, o false si hay un error.
     */
    public function crearPedido($id_usuario, $id_producto, $cantidad, $precio_unitario) {
        try {
            $this->conexionObj->abrir(); 
            $mysqli_conn = $this->conexionObj->getMysqli();

            $fecha = date('Y-m-d H:i:s'); // La columna 'fecha' en tu tabla
            $estado = "Pendiente"; // La columna 'estado' en tu tabla

            // Escapar y asegurar los tipos de datos para la consulta directa
            $id_usuario_escaped = (int) $id_usuario; 
            $id_producto_escaped = (int) $id_producto; 
            $cantidad_escaped = (int) $cantidad; 
            $fecha_escaped = $mysqli_conn->real_escape_string($fecha);
            $estado_escaped = $mysqli_conn->real_escape_string($estado);

            // Construir la consulta SQL directamente usando tus columnas:
            // id_usuario, id_producto, cantidad, fecha, estado
            $sql = "INSERT INTO pedidos (id_usuario, id_producto, cantidad, fecha, estado) 
                    VALUES ($id_usuario_escaped, $id_producto_escaped, $cantidad_escaped, '$fecha_escaped', '$estado_escaped')";

            error_log("SQL crearPedido (línea de producto): " . $sql); 

            $resultado_query = $this->conexionObj->consulta($sql);

            if ($resultado_query === false) {
                error_log("Error al ejecutar crearPedido (línea de producto) con consulta directa. MySQL error: " . $mysqli_conn->error);
                return false;
            }

            // Devolvemos el ID del registro de la línea de pedido insertada
            $id_linea_pedido = $this->conexionObj->obtenerInsertId();
            
            if (!$id_linea_pedido) {
                error_log("crearPedido: No se obtuvo un ID de inserción para la línea de pedido.");
                return false;
            }

            return $id_linea_pedido;

        } catch (Exception $e) {
            error_log("Excepción en crearPedido (línea de producto): " . $e->getMessage());
            return false;
        } finally {
            // No cerramos la conexión aquí porque procesarPedidoCompleto la maneja
        }
    }

    // El método 'agregarProductoAPedido' ya no es necesario
    // porque 'crearPedido' ya maneja la inserción de cada línea de producto.
    // Lo comento o podrías eliminarlo.
    /*
    public function agregarProductoAPedido($id_pedido, $id_producto, $cantidad, $precio_unitario) {
        // Esta lógica ya está cubierta por el nuevo comportamiento de crearPedido.
        // Si tuvieras una tabla de 'detalles_pedido' separada, este método sería útil.
        return true; 
    }
    */

    /**
     * Procesa un pedido completo iterando sobre el carrito e insertando cada ítem
     * como una línea de pedido en la tabla 'pedidos'.
     *
     * @param int $id_cliente El ID del cliente (id_usuario).
     * @param array $carrito Un array de productos en el carrito.
     * @return bool True si la operación fue exitosa para todos los ítems, o false si hay un error.
     */
    public function procesarPedidoCompleto($id_cliente, $carrito) {
        try {
            $this->conexionObj->abrir(); 
            $this->conexionObj->beginTransaction(); 
            
            error_log("Iniciando procesarPedidoCompleto para cliente: " . $id_cliente);

            $total_carrito_calculado = 0; // Calculamos el total para mostrarlo o para tu control interno
            if (!is_array($carrito) || empty($carrito)) {
                throw new Exception("El carrito está vacío o no es un array válido.");
            }

            $todos_los_items_insertados = true;

            foreach ($carrito as $item) {
                if (!isset($item['id']) || !isset($item['precio']) || !isset($item['cantidad']) || 
                    !is_numeric($item['id']) || !is_numeric($item['precio']) || !is_numeric($item['cantidad'])) {
                    throw new Exception("Datos de producto inválidos en el carrito.");
                }

                $total_item = (float)$item['precio'] * (int)$item['cantidad'];
                $total_carrito_calculado += $total_item;

                // Llamamos a crearPedido para cada item del carrito
                $id_linea_pedido = $this->crearPedido(
                    $id_cliente,           // id_usuario en tu tabla
                    $item['id'],           // id_producto en tu tabla
                    $item['cantidad'],     // cantidad en tu tabla
                    $item['precio']        // precio_unitario (no va a la DB directamente en esta tabla, pero es crucial para el cálculo)
                );

                if (!$id_linea_pedido) {
                    error_log("Error al insertar la línea de producto ID: {$item['id']} para el pedido del cliente ID: {$id_cliente}");
                    $todos_los_items_insertados = false;
                    break; // Salir del bucle si falla una inserción
                }
                error_log("Línea de pedido para producto ID {$item['id']} creada con ID: " . $id_linea_pedido);
            }

            if ($todos_los_items_insertados) {
                $this->conexionObj->commit(); 
                error_log("Transacción de pedido completada y confirmada. Total calculado: " . $total_carrito_calculado);
                // Retornamos true si todo fue bien, ya que no hay un único 'id_pedido' principal
                return true; 
            } else {
                $this->conexionObj->rollBack();
                error_log("Transacción de pedido revertida debido a un error en la inserción de líneas de producto.");
                return false;
            }
            
        } catch (Exception $e) {
            $this->conexionObj->rollBack(); 
            error_log("Excepción Capturada en procesarPedidoCompleto: " . $e->getMessage());
            return false; 
        } finally {
            $this->conexionObj->cerrar(); 
        }
    }

    /**
     * Obtiene los pedidos (líneas de producto) para un cliente desde la tabla 'pedidos'.
     * Reorganiza los resultados para agrupar por "pedido" basándose en la fecha y el usuario,
     * ya que tu tabla 'pedidos' no tiene un ID de pedido principal.
     *
     * @param int $id_cliente El ID del cliente (id_usuario).
     * @return array Un array de pedidos simulados, donde cada "pedido" agrupa líneas por fecha.
     */
    public function obtenerPedidosPorCliente($id_cliente)
    {
        $pedidos_agrupados = []; // Aquí guardaremos los pedidos agrupados por fecha
        $id_cliente_sanitized = (int) $id_cliente; 

        try {
            $this->conexionObj->abrir(); 
            
            // Unimos con 'productos' para obtener el nombre del producto
            // Y con 'imagenes_producto' para obtener la imagen
            $sql = "SELECT
                        p.id AS linea_pedido_id,
                        p.id_usuario,
                        p.id_producto,
                        p.cantidad,
                        p.fecha,
                        p.estado,
                        pr.nombre AS producto_nombre,
                        pr.modelo AS producto_modelo,
                        pr.precio AS producto_precio_unitario, -- Asumimos que el precio actual del producto es el unitario
                        (SELECT i.ruta_imagen FROM imagenes_producto i WHERE i.id_producto = pr.id LIMIT 1) AS producto_imagen_ruta
                    FROM
                        pedidos p
                    LEFT JOIN
                        productos pr ON p.id_producto = pr.id
                    WHERE
                        p.id_usuario = " . $id_cliente_sanitized . "
                    ORDER BY
                        p.fecha DESC, p.id DESC"; // Ordenamos por fecha para intentar agrupar pedidos

            error_log("SQL obtenerPedidosPorCliente: " . $sql); 

            $resultado_mysqli = $this->conexionObj->consulta($sql);

            if ($resultado_mysqli === false) {
                error_log("Fallo en la consulta de pedidos para cliente ID (obtenerPedidosPorCliente): " . $id_cliente . ". MySQL Error: " . $this->conexionObj->getMysqli()->error);
                return [];
            }

            $raw_results = [];
            while ($fila = $resultado_mysqli->fetch_assoc()) { 
                $raw_results[] = $fila;
            }
            
            // Agrupamos las líneas de pedido por fecha para simular pedidos "únicos"
            $current_fecha_pedido = null;
            $current_pedido_id_counter = 0; // Para dar un ID único a cada "pedido" agrupado
            $total_pedido_actual = 0;

            foreach ($raw_results as $row) {
                // Si la fecha cambia, significa que es un nuevo "pedido" (basado en la fecha)
                // O si es el primer registro
                if ($row['fecha'] !== $current_fecha_pedido) {
                    if ($current_fecha_pedido !== null) {
                        // Guardar el pedido anterior antes de empezar uno nuevo
                        $pedidos_agrupados[$current_pedido_id_counter]['total_pedido'] = $total_pedido_actual;
                    }
                    $current_pedido_id_counter++;
                    $current_fecha_pedido = $row['fecha'];
                    $total_pedido_actual = 0; // Resetear el total para el nuevo pedido

                    $pedidos_agrupados[$current_pedido_id_counter] = [
                        'id' => $current_pedido_id_counter, // ID simulado
                        'fecha_pedido' => $row['fecha'],
                        'estado' => $row['estado'], // El estado de la primera línea se usará para el "pedido"
                        'total_pedido' => 0, // Se calculará al final del grupo
                        'productos' => []
                    ];
                }

                // Asegurarse de que el producto existe antes de agregarlo
                if ($row['id_producto']) { 
                    $pedidos_agrupados[$current_pedido_id_counter]['productos'][] = [
                        'id' => $row['id_producto'],
                        'nombre_producto' => $row['producto_nombre'],
                        'modelo_producto' => $row['producto_modelo'],
                        'cantidad' => $row['cantidad'],
                        'precio_unitario' => $row['producto_precio_unitario'],
                        'imagen_ruta' => $row['producto_imagen_ruta']
                    ];
                    // Sumar al total del pedido agrupado (cantidad * precio_unitario del producto)
                    $total_pedido_actual += (float)$row['cantidad'] * (float)$row['producto_precio_unitario'];
                }
            }

            // Guardar el último pedido agrupado
            if ($current_fecha_pedido !== null) {
                $pedidos_agrupados[$current_pedido_id_counter]['total_pedido'] = $total_pedido_actual;
            }

            return array_values($pedidos_agrupados); // Devolver como un array numérico

        } catch (Exception $e) {
            error_log("Excepción inesperada en obtenerPedidosPorCliente: " . $e->getMessage());
            return []; 
        } finally {
            $this->conexionObj->cerrar(); 
        }
    }
}