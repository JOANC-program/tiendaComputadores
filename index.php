<?php
session_start();
require_once "Controlador/Controlador.php";
require_once "Modelo/Conexion.php";
require_once "Modelo/GestorAdmin.php";
require_once "Modelo/GestorCatalogo.php";
require_once "Modelo/GestorPedidos.php";

$Controlador = new Controlador();
if (isset($_GET['accion'])) {
    if ($_GET["accion"] == "vista") {
        $Controlador->verpagina('Vista/html/catalogo.html');
    }
    if ($_GET["accion"] == "vista") {
        $Controlador->verpagina('Vista/html/catalogo.html');
    }
    if ($_GET["accion"] == "catalogo") {
        $Controlador->mostrarCatalogo();
    }
    if ($_GET["accion"] == "admin") {
        $Controlador->verpagina('Vista/html/admin.php');
    }
    if ($_GET["accion"] == "login") {
        $Controlador->verpagina('Vista/html/login.php');
    }
    if ($_GET["accion"] == "loginAdmin") {
        if (isset($_SESSION['admin'])) {
            header("Location: index.php?accion=productos");
            exit;
        } else {
            $Controlador->verpagina('Vista/html/loginAdmin.html');
        }
    }
    if ($_GET["accion"] == "ingresar") {
        $correo = $_POST["correo"];
        $contrasena = $_POST["contrasena"];
        $Controlador->ingresar($correo, $contrasena);
    }
    if ($_GET["accion"] == "guardarProducto") {
        $marca = $_POST["marca"];
        $modelo = $_POST["modelo"];
        $tipo = $_POST["tipo"];
        $especificaciones = $_POST["especificaciones"];
        $precio = $_POST["precio"];
        $id_categoria = $_POST["id_categoria"];
        $imagenes = $_FILES["imagenes"];
        $Controlador->guardarProducto($marca, $modelo, $tipo, $especificaciones, $precio, $id_categoria, $imagenes);
    }
    if ($_GET["accion"] == "editarProducto" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $Controlador->editarProducto($id);
    }
    if ($_GET["accion"] == "actualizarProducto") {
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $precio = $_POST["precio"];
        $descripcion = $_POST["descripcion"];
        $id_categoria = $_POST["id_categoria"];
        $imagen = $_FILES["imagen"];
        $Controlador->actualizarProducto($id, $nombre, $precio, $descripcion, $id_categoria, $imagen);
    }
    if ($_GET["accion"] == "eliminarProducto" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $Controlador->eliminarProducto($id);
    }

    if ($_GET["accion"] == "guardarCategoria") {
        $nombre = $_POST["nombre_categoria"];
        $Controlador->guardarCategoria($nombre);
    }

    if ($_GET["accion"] == "eliminarCategoria" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $Controlador->eliminarCategoria($id);
    }
    if ($_GET["accion"] == "formularioPedido") {
        $id_producto = $_POST["id_producto"];
        require "Vista/html/formularioPedido.php";
    }
    if ($_GET["accion"] == "procesarPedido") {
        $correo = $_POST["correo"];
        $contrasena = $_POST["contrasena"];
        $id_producto = $_POST["id_producto"];
        $cantidad = $_POST["cantidad"];
        $Controlador->procesarPedido($correo, $contrasena, $id_producto, $cantidad);
    }
    if ($_GET["accion"] == "registroCliente") {
        require "Vista/html/registroCliente.php";
    }
    if ($_GET["accion"] == "guardarCliente") {
        $nombre = $_POST["nombre"];
        $correo = $_POST["correo"];
        $contrasena = $_POST["contrasena"];
        $Controlador->guardarCliente($nombre, $correo, $contrasena);
    }
    if( $_GET["accion"] == "eliminarCarrito") {
        $Controlador->eliminarCarrito();
    }

    if ($_GET["accion"] == "categorias") {
        $Controlador->mostrarCategorias();
    }
    if ($_GET["accion"] == "pedidos") {
        $Controlador->mostrarPedidos();
    }
    if ($_GET["accion"] == "productos") {
        $Controlador->mostrarProductos();
    }
    if ($_GET["accion"] == "cerrarSesion") {
     $Controlador->cerrarSesion();
    }
    if ($_GET["accion"] == "cambiarEstadoPedido") {
        $id_pedido = $_POST["id_pedido"];
        $estado = $_POST["estado"];
        $Controlador->cambiarEstadoPedido($id_pedido, $estado);
    }
    if ($_GET["accion"] == "editarCategoria" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $Controlador->editarCategoria($id);
    }
    if ($_GET["accion"] == "actualizarCategoria") {
        $id = $_POST["id"];
        $nombre = $_POST["nombre_categoria"];
        $Controlador->actualizarCategoria($id, $nombre);
    }
     if ($_GET["accion"] == "carrito") {
            $Controlador->mostrarCarrito();
    }
    if ($_GET["accion"] == "agregarCarrito") {
            $Controlador->agregarCarrito();
    }
    if ($_GET["accion"] == "finalizarPedido") {
            $Controlador->finalizarPedido();
    }

    if ($_GET["accion"] == "pedidoscliente") {
             $Controlador->mostrarPedidosCliente();
    }
     if ($_GET["accion"] == "dashboard") {
             $Controlador->mostrarDashboard();
         }
        /* else {
        $Controlador->verpagina('Vista/html/error.html');
    }*/
    
} else {
    $Controlador->verpagina('Vista/html/login.php');
}
