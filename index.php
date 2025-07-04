<?php
session_start();
require_once "Controlador/Controlador.php";
require_once "Modelo/Conexion.php";
require_once "Modelo/GestorAdmin.php";
require_once "Modelo/GestorCatalogo.php";
$Controlador = new Controlador();
if (isset($_GET['accion'])) {
    if ($_GET["accion"] == "vista") {
        $Controlador->verpagina('Vista/html/catalogo.html');

    } elseif ($_GET["accion"] == "catalogo") {
        $Controlador->mostrarCatalogo();

    }elseif ($_GET["accion"] == "admin") {
        $Controlador->verpagina('Vista/html/admin.php');
    }
     elseif ($_GET["accion"] == "loginAdmin") {
        if (isset($_SESSION['admin'])) {
            header("Location: index.php?accion=productos");
            exit;
        } else {
            $Controlador->verpagina('Vista/html/loginAdmin.html');
        }
    } elseif ($_GET["accion"] == "ingresar") {
        $correo = $_POST["correo"];
        $contrasena = $_POST["contrasena"];
        $Controlador->loginAdmin($correo, $contrasena);
    } elseif ($_GET["accion"] == "guardarProducto") {
        $marca = $_POST["marca"];
        $modelo = $_POST["modelo"];
        $tipo = $_POST["tipo"];
        $especificaciones = $_POST["especificaciones"];
        $precio = $_POST["precio"];
        $id_categoria = $_POST["id_categoria"];
        $imagenes = $_FILES["imagenes"];
        $Controlador->guardarProducto($marca, $modelo, $tipo, $especificaciones, $precio, $id_categoria, $imagenes);
    } elseif ($_GET["accion"] == "editarProducto" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $Controlador->editarProducto($id);
    } elseif ($_GET["accion"] == "actualizarProducto") {
        $id = $_POST["id"];
        $marca = $_POST["marca"];
        $modelo = $_POST["modelo"];
        $tipo = $_POST["tipo"];
        $especificaciones = $_POST["especificaciones"];
        $precio = $_POST["precio"];
        $id_categoria = $_POST["id_categoria"];
        $imagenes = $_FILES["imagenes"];
        $Controlador->actualizarProducto($id, $marca, $modelo, $tipo, $especificaciones, $precio, $id_categoria, $imagenes);
    } elseif ($_GET["accion"] == "eliminarProducto" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $Controlador->eliminarProducto($id);
    } elseif ($_GET["accion"] == "guardarCategoria") {
        $nombre = $_POST["nombre_categoria"];
        $Controlador->guardarCategoria($nombre);
    } elseif ($_GET["accion"] == "eliminarCategoria" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $Controlador->eliminarCategoria($id);
    } elseif ($_GET["accion"] == "formularioPedido") {
        $id_producto = $_POST["id_producto"];
        require "Vista/html/formularioPedido.php";
    } elseif ($_GET["accion"] == "procesarPedido") {
        $correo = $_POST["correo"];
        $contrasena = $_POST["contrasena"];
        $id_producto = $_POST["id_producto"];
        $cantidad = $_POST["cantidad"];
        $Controlador->procesarPedido($correo, $contrasena, $id_producto, $cantidad);
    } elseif ($_GET["accion"] == "registroCliente") {
        require "Vista/html/registroCliente.php";
    } elseif ($_GET["accion"] == "guardarCliente") {
        $nombre = $_POST["nombre"];
        $correo = $_POST["correo"];
        $contrasena = $_POST["contrasena"];
        $Controlador->guardarCliente($nombre, $correo, $contrasena);
    } elseif ($_GET["accion"] == "categorias") {
        $Controlador->mostrarCategorias();
    } elseif ($_GET["accion"] == "pedidos") {
        $Controlador->mostrarPedidos();
    } elseif ($_GET["accion"] == "productos") {
        $Controlador->mostrarProductos();
    } elseif ($_GET["accion"] == "cerrarSesion") {
        require "Vista/html/logout.php";
    } elseif ($_GET["accion"] == "cambiarEstadoPedido") {
        $id_pedido = $_POST["id_pedido"];
        $estado = $_POST["estado"];
        $Controlador->cambiarEstadoPedido($id_pedido, $estado);
    } elseif ($_GET["accion"] == "editarCategoria" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $Controlador->editarCategoria($id);
    } elseif ($_GET["accion"] == "actualizarCategoria") {
        $id = $_POST["id"];
        $nombre = $_POST["nombre_categoria"];
        $Controlador->actualizarCategoria($id, $nombre);
    } elseif ($_GET["accion"] == "eliminarImagen" && isset($_GET["id_img"]) && isset($_GET["id_producto"])) {
        $id_img = $_GET["id_img"];
        $id_producto = $_GET["id_producto"];
        $Controlador->eliminarImagenProducto($id_img, $id_producto);
    } else {
        $Controlador->verpagina('Vista/html/error.html');

    }
} else {
    header('Location: index.php?accion=catalogo');
    exit;
}
?>