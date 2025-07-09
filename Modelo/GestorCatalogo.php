<?php
require_once "Conexion.php";

class GestorCatalogo
{
    public function listarCategorias()
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $categorias = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $categorias[] = $row;
            }
        }
        $conexion->cerrar();
        return $categorias;
    }

    public function listarProductos($limit, $offset, $id_categoria = null)
    {
        $conexion = new Conexion();
        $mysqli = $conexion->getMysqli(); 
        $conexion->abrir();

        $sql = "SELECT
                    p.id,
                    p.marca,         
                    p.modelo,                
                    p.tipo,                    
                    p.precio,
                    p.especificaciones,                   
                    p.id_categoria,
                    c.nombre AS nombre_categoria
                FROM productos p
                LEFT JOIN categorias c ON p.id_categoria = c.id";

        $where_clauses = [];

        if ($id_categoria !== null && is_numeric($id_categoria)) {
            $id_categoria_sanitized = $mysqli->real_escape_string((int)$id_categoria);
            $where_clauses[] = "p.id_categoria = $id_categoria_sanitized";
        }

        if (!empty($where_clauses)) {
            $sql .= " WHERE " . implode(" AND ", $where_clauses);
        }

        $limit = (int)$limit;
        $offset = (int)$offset;
        $sql .= " ORDER BY p.id ASC LIMIT $limit OFFSET $offset";

        error_log("SQL listarProductos (con paginación y filtro): " . $sql); // Para depuración

        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $productos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
           
                $productos[] = $row;
            }
        }
        $conexion->cerrar();
        return $productos;
    }

   
    public function contarTotalProductos($id_categoria = null)
    {
        $conexion = new Conexion();
        $mysqli = $conexion->getMysqli(); // Obtener la instancia de mysqli para sanitización
        $conexion->abrir();

        $sql = "SELECT COUNT(*) AS total FROM productos";

        // Añadir cláusula WHERE si hay un filtro de categoría
        if ($id_categoria !== null && is_numeric($id_categoria)) {
            $id_categoria_sanitized = $mysqli->real_escape_string((int)$id_categoria);
            $sql .= " WHERE id_categoria = $id_categoria_sanitized";
        }

        error_log("SQL contarTotalProductos: " . $sql); // Para depuración

        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $total = 0;
        if ($result && $fila = $result->fetch_assoc()) {
            $total = (int) $fila['total'];
        }
        $conexion->cerrar();
        return $total;
    }

    public function obtenerProductoPorId($id)
    {
        $conexion = new Conexion();
        $mysqli = $conexion->getMysqli();
        $conexion->abrir();
        $id_sanitized = $mysqli->real_escape_string($id);

        $sql = "SELECT p.id,
                       p.marca,
                       p.modelo,
                       p.tipo,
                       p.precio,
                       p.especificaciones,
                       p.id_categoria,
                       c.nombre AS nombre_categoria
                FROM productos p
                LEFT JOIN categorias c ON p.id_categoria = c.id
                WHERE p.id = '$id_sanitized' LIMIT 1";

        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $producto = null;
        if ($result && $row = $result->fetch_assoc()) {
            $producto = $row;
          
        }
        $conexion->cerrar();
        return $producto;
    }
    public function listarImagenesProducto($id_producto)
{
    $conexion = new Conexion();
    $conexion->abrir();
    $sql = "SELECT id, ruta_imagen FROM imagenes_producto WHERE id_producto = $id_producto ORDER BY id ASC";
    $conexion->consulta($sql);
    $result = $conexion->obtenerResult();
    $imagenes = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $imagenes[] = $row;
        }
    }
    $conexion->cerrar();
    return $imagenes;
}

    public function obtenerCategoriaPorId($id) {
        $conexion = new Conexion();
        $mysqli = $conexion->getMysqli();
        $conexion->abrir();
        $id_sanitized = $mysqli->real_escape_string($id);
        $sql = "SELECT * FROM categorias WHERE id = '$id_sanitized' LIMIT 1";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $categoria = null;
        if ($result && $row = $result->fetch_assoc()) {
            $categoria = $row;
        }
        $conexion->cerrar();
        return $categoria;
    }
  
}
?>