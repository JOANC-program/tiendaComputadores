<?php


class GestorPedidos {
    private $conexion_obj; 

    public function __construct() { 
        $this->conexion_obj = new Conexion(); 
        $this->conexion_obj->abrir(); 
    }

    public function __destruct() {
        $this->conexion_obj->cerrar();
    }

    public function crearPedido($id_cliente) {
        $mysqli = $this->conexion_obj->getMysqli(); 
        
        $stmt = $mysqli->prepare("INSERT INTO pedidos (id_cliente, fecha, estado) VALUES (?, NOW(), 'pendiente')");
        $stmt->bind_param("i", $id_cliente); 
        $stmt->execute();
        
        if ($stmt->error) {
            throw new Exception("Error en la consulta (crearPedido): " . $stmt->error);
        }
        
        $last_id = $stmt->insert_id; 
        $stmt->close();
        return $last_id; 
    }

    public function agregarProductoAPedido($id_pedido, $id_producto, $cantidad, $precio_unitario) {
        $mysqli = $this->conexion_obj->getMysqli();
        
        $stmt = $mysqli->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $precio_unitario);
        $stmt->execute();
        
        if ($stmt->error) {
            throw new Exception("Error en la consulta (agregarProductoAPedido): " . $stmt->error);
        }
        $stmt->close();
    }
}
?>