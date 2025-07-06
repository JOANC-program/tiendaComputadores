<?php
require_once "Conexion.php";

class GestorCatalogo
{
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
    
    public function listarProductosPorCategoria($id_categoria)
    {
        $conexion = new Conexion();
        $conexion->abrir();
        $sql = "SELECT p.*, c.nombre AS categorias FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id WHERE p.id_categoria = '$id_categoria'";
        $conexion->consulta($sql);
        $result = $conexion->obtenerResult();
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        $conexion->cerrar();
        return $productos;
    }
}
?>