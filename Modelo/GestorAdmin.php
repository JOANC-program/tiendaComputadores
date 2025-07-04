<?php
class GestorAdmin
{
    public function verificarAdmin($correo, $contrasena)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT * FROM usuarios WHERE correo='$correo' LIMIT 1";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $usuario = $result->fetch_assoc();
        $conexion->cerrar();

        // Verifica la contraseña hasheada
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return true;
        }
        return false;
    }

    public function guardarProducto($marca, $modelo, $tipo, $especificaciones, $precio, $id_categoria)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "INSERT INTO productos (marca, modelo, tipo, especificaciones, precio, id_categoria) 
                VALUES ('$marca', '$modelo', '$tipo', '$especificaciones', '$precio', '$id_categoria')";
        $conexion->consulta($sql);
        $id_producto = $conexion->obtenerInsertId();
        $conexion->cerrar();
        return $id_producto;
    }

    public function guardarImagenProducto($id_producto, $ruta)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "INSERT INTO imagenes_producto (id_producto, ruta_imagen) VALUES ('$id_producto', '$ruta')";
        $conexion->consulta($sql);
        $conexion->cerrar();
    }

    public function listarProductos()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT p.*, c.nombre AS categorias FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        $conexion->cerrar();
        return $productos;
    }

    public function listarProductosPaginados($limite, $offset)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT p.*, c.nombre AS categorias,
                   (SELECT ruta_imagen FROM imagenes_producto i WHERE i.id_producto = p.id LIMIT 1) AS imagen
            FROM productos p
            LEFT JOIN categorias c ON p.id_categoria = c.id
            LIMIT $limite OFFSET $offset";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        $conexion->cerrar();
        return $productos;
    }

    public function contarProductos()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT COUNT(*) AS total FROM productos";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $row = $result->fetch_assoc();
        $conexion->cerrar();
        return $row['total'];
    }

    public function listarCategorias()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT * FROM categorias";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        $conexion->cerrar();
        return $categorias;
    }

    public function obtenerProductoPorId($id)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT * FROM productos WHERE id = '$id' LIMIT 1";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $producto = $result->fetch_assoc();
        $conexion->cerrar();
        return $producto;
    }

    public function actualizarProducto($id, $marca, $modelo, $tipo, $especificaciones, $precio, $id_categoria)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "UPDATE productos SET 
                marca='$marca', 
                modelo='$modelo', 
                tipo='$tipo', 
                especificaciones='$especificaciones', 
                precio='$precio', 
                id_categoria='$id_categoria'
            WHERE id='$id'";
        $conexion->consulta($sql);
        $conexion->cerrar();
    }

    public function eliminarProducto($id)
    {
        $conexion = new Conexion();
        $conexion->abrir();

        // 1. Obtener todas las rutas de imágenes asociadas
        $sql = "SELECT ruta_imagen FROM imagenes_producto WHERE id_producto = '$id'";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['ruta_imagen']) && file_exists($row['ruta_imagen'])) {
                unlink($row['ruta_imagen']);
            }
        }

        // 2. Eliminar registros de imágenes asociadas
        $sql = "DELETE FROM imagenes_producto WHERE id_producto = '$id'";
        $conexion->consulta($sql);

        // 3. Eliminar el producto
        $sql = "DELETE FROM productos WHERE id = '$id'";
        $conexion->consulta($sql);

        $conexion->cerrar();
    }

    public function guardarCategoria($nombre)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "INSERT INTO categorias (nombre) VALUES ('$nombre')";
        $conexion->consulta($sql);
        $conexion->cerrar();
    }

    public function eliminarCategoria($id)
    {
        $conexion = new Conexion();
        $conexion->abrir();

        // Eliminar imágenes de productos asociados
        $sql = "SELECT id FROM productos WHERE id_categoria = '$id'";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $productoIds = [];
        while ($row = $result->fetch_assoc()) {
            $productoIds[] = $row['id'];
        }

        // Eliminar imágenes físicas y registros de imágenes asociadas
        if (!empty($productoIds)) {
            foreach ($productoIds as $prodId) {
                // Eliminar imágenes físicas y registros
                $sqlImgs = "SELECT ruta_imagen FROM imagenes_producto WHERE id_producto = '$prodId'";
                $conexion->consulta($sqlImgs);
                $resImgs = $conexion->obtenerResult();
                while ($imgRow = $resImgs->fetch_assoc()) {
                    if (!empty($imgRow['ruta_imagen']) && file_exists($imgRow['ruta_imagen'])) {
                        unlink($imgRow['ruta_imagen']);
                    }
                }
                $sqlDelImgs = "DELETE FROM imagenes_producto WHERE id_producto = '$prodId'";
                $conexion->consulta($sqlDelImgs);
            }
            // Eliminar pedidos asociados a los productos de esta categoría
            $ids = implode(',', $productoIds);
            $sql = "DELETE FROM pedidos WHERE id_producto IN ($ids)";
            $conexion->consulta($sql);
        }

        // Eliminar productos asociados
        $sql = "DELETE FROM productos WHERE id_categoria = '$id'";
        $conexion->consulta($sql);

        // Eliminar la categoría
        $sql = "DELETE FROM categorias WHERE id = '$id'";
        $conexion->consulta($sql);

        $conexion->cerrar();
    }

    public function listarPedidos()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT p.id, u.correo AS usuario, 
                   CONCAT(pr.marca, ' ', pr.modelo) AS producto, 
                   p.cantidad, p.fecha, p.estado
            FROM pedidos p
            LEFT JOIN usuarios u ON p.id_usuario = u.id
            LEFT JOIN productos pr ON p.id_producto = pr.id
            ORDER BY p.fecha DESC";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }
        $conexion->cerrar();
        return $pedidos;
    }

    public function guardarPedido($id_usuario, $id_producto, $cantidad, $fecha, $estado)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "INSERT INTO pedidos (id_usuario, id_producto, cantidad, fecha, estado)
                VALUES ('$id_usuario', '$id_producto', '$cantidad', '$fecha', '$estado')";
        $conexion->consulta($sql);
        $conexion->cerrar();
    }

    public function existeCorreo($correo)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT id FROM usuarios WHERE correo='$correo' LIMIT 1";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $existe = $result->num_rows > 0;
        $conexion->cerrar();
        return $existe;
    }

    public function guardarCliente($nombre, $correo, $contrasena)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES ('$nombre', '$correo', '$hash')";
        $conexion->consulta($sql);
        $conexion->cerrar();
    }

    public function obtenerUsuarioPorCorreo($correo)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT id, contrasena FROM usuarios WHERE correo='$correo' LIMIT 1";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $usuario = $result->fetch_assoc();
        $conexion->cerrar();
        return $usuario;
    }
    public function actualizarEstadoPedido($id_pedido, $estado)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "UPDATE pedidos SET estado='$estado' WHERE id='$id_pedido'";
        $conexion->consulta($sql);
        $conexion->cerrar();
    }
    public function obtenerCategoriaPorId($id)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT * FROM categorias WHERE id = '$id' LIMIT 1";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $categoria = $result->fetch_assoc();
        $conexion->cerrar();
        return $categoria;
    }

    public function actualizarCategoria($id, $nombre)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "UPDATE categorias SET nombre='$nombre' WHERE id='$id'";
        $conexion->consulta($sql);
        $conexion->cerrar();
    }
    public function obtenerImagenesPorProducto($id_producto)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT id, ruta_imagen FROM imagenes_producto WHERE id_producto = '$id_producto'";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $imagenes = [];
        while ($row = $result->fetch_assoc()) {
            $imagenes[] = $row;
        }
        $conexion->cerrar();
        return $imagenes;
    }
    public function eliminarImagenProducto($id_img)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        // Obtener la ruta de la imagen
        $sql = "SELECT ruta_imagen FROM imagenes_producto WHERE id = '$id_img' LIMIT 1";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $row = $result->fetch_assoc();
        if ($row && !empty($row['ruta_imagen']) && file_exists($row['ruta_imagen'])) {
            unlink($row['ruta_imagen']);
        }
        // Eliminar el registro de la base de datos
        $sql = "DELETE FROM imagenes_producto WHERE id = '$id_img'";
        $conexion->consulta($sql);
        $conexion->cerrar();
    }
}
?>