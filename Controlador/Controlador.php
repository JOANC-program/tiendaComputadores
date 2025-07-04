<?php
class Controlador
{
    public function verpagina($ruta)
    {
        require_once $ruta;
    }
    public function loginAdmin($correo, $contrasena)
{
    $gestor = new GestorAdmin();
    $existe = $gestor->verificarAdmin($correo, $contrasena);
    if ($existe) {
        $_SESSION['admin'] = $correo; 
        header('Location: index.php?accion=productos');
        exit;
    } else {
        $this->verpagina('Vista/html/loginAdmin.html');
        echo "<script>alert('Usuario o contraseña incorrectos');</script>";
    }
}
public function guardarProducto($marca, $modelo, $tipo, $especificaciones, $precio, $id_categoria, $imagenes)
{
    $gestor = new GestorAdmin();
    $id_producto = $gestor->guardarProducto($marca, $modelo, $tipo, $especificaciones, $precio, $id_categoria);

    // Guardar imágenes
    foreach ($imagenes['tmp_name'] as $key => $tmp_name) {
        if ($tmp_name) {
            $nombreArchivo = uniqid() . "_" . basename($imagenes["name"][$key]);
            $rutaDestino = "Vista/img/" . $nombreArchivo;
            move_uploaded_file($tmp_name, $rutaDestino);
            $gestor->guardarImagenProducto($id_producto, $rutaDestino);
        }
    }

    header("Location: index.php?accion=productos");
    exit;
}
public function editarProducto($id)
{
    $gestor = new GestorAdmin();
    $producto = $gestor->obtenerProductoPorId($id);
    $categorias = $gestor->listarCategorias();
    require "Vista/html/editarProducto.php";
}
public function actualizarProducto($id, $nombre, $precio, $descripcion, $id_categoria, $imagen)
{
    $gestor = new GestorAdmin();
    // Procesar imagen solo si se subió una nueva
    $rutaImagen = null;
    if ($imagen["tmp_name"]) {
        $nombreArchivo = uniqid() . "_" . basename($imagen["name"]);
        $rutaDestino = "Vista/img/" . $nombreArchivo;
        move_uploaded_file($imagen["tmp_name"], $rutaDestino);
        $rutaImagen = $rutaDestino;
    }
    $gestor->actualizarProducto($id, $nombre, $precio, $descripcion, $id_categoria, $rutaImagen);
    // Redirigir para evitar reenvío del formulario
    header("Location: index.php?accion=productos");
    exit;
}
public function eliminarProducto($id)
{
    $gestor = new GestorAdmin();
    $gestor->eliminarProducto($id);
    // Redirigir para evitar reenvío del formulario
    header("Location: index.php?accion=productos");
    exit;
}
public function guardarCategoria($nombre)
{
    $gestor = new GestorAdmin();
    $gestor->guardarCategoria($nombre);
    // Redirigir para evitar reenvío del formulario
    header("Location: index.php?accion=categorias");
    exit;
}
public function eliminarCategoria($id)
{
    $gestor = new GestorAdmin();
    $gestor->eliminarCategoria($id);
    // Redirigir para evitar reenvío del formulario
    
    header("Location: index.php?accion=categorias");
    
    exit;
}
public function procesarPedido($correo, $contrasena, $id_producto, $cantidad)
{
    $gestor = new GestorAdmin();
    $usuario = $gestor->obtenerUsuarioPorCorreo($correo);

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        $id_usuario = $usuario['id'];
        $fecha = date('Y-m-d');
        $estado = "Pendiente";
        $gestor->guardarPedido($id_usuario, $id_producto, $cantidad, $fecha, $estado);
        header("Location: index.php?accion=catalogo&mensaje=pedido_ok");
        exit;
    } else {
        echo "<script>alert('Usuario no registrado');window.location='index.php?accion=catalogo';</script>";
        exit;
    }
}
public function guardarCliente($nombre, $correo, $contrasena)
{
    $gestor = new GestorAdmin();
    if ($gestor->existeCorreo($correo)) {
        echo "<script>alert('El correo ya está registrado');window.location='index.php?accion=registroCliente';</script>";
        exit;
    }
    $gestor->guardarCliente($nombre, $correo, $contrasena);
    echo "<script>alert('Registro exitoso, ahora puede solicitar pedidos');window.location='index.php?accion=catalogo';</script>";
    exit;
}
public function mostrarCatalogo()
{
    $gestor = new GestorCatalogo();
    $categorias = $gestor->listarCategorias();
    $productos = isset($_GET['filtro_categoria']) && $_GET['filtro_categoria'] != ''
        ? $gestor->listarProductosPorCategoria($_GET['filtro_categoria'])
        : $gestor->listarProductos();
    require "Vista/html/catalogo.php";
}
public function cambiarEstadoPedido($id_pedido, $estado)
{
    $gestor = new GestorAdmin();
    $gestor->actualizarEstadoPedido($id_pedido, $estado);
    header("Location: index.php?accion=pedidos");
    exit;
}
public function mostrarCategorias()
{
    $gestor = new GestorAdmin();
    $categorias = $gestor->listarCategorias();
    require "Vista/html/categorias.php";
}
public function mostrarPedidos()
{
    $gestor = new GestorAdmin();
    $pedidos = $gestor->listarPedidos();
    require "Vista/html/pedidos.php";
}
public function mostrarProductos()
{
    $gestor = new GestorAdmin();
    $limite = 10;
    $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
    $offset = ($pagina - 1) * $limite;

    $productos = $gestor->listarProductosPaginados($limite, $offset);
    // Agregar todas las imágenes a cada producto
    foreach ($productos as &$producto) {
        $producto['imagenes'] = $gestor->obtenerImagenesPorProducto($producto['id']);
    }
    unset($producto);

    $totalProductos = $gestor->contarProductos();
    $totalPaginas = ceil($totalProductos / $limite);

    $categorias = $gestor->listarCategorias();
    require "Vista/html/productos.php";
}
public function editarCategoria($id)
{
    $gestor = new GestorAdmin();
    $categoria = $gestor->obtenerCategoriaPorId($id);
    require "Vista/html/editarCategoria.php";
}

public function actualizarCategoria($id, $nombre)
{
    $gestor = new GestorAdmin();
    $gestor->actualizarCategoria($id, $nombre);
    header("Location: index.php?accion=categorias");
    exit;
}
public function eliminarImagenProducto($id_img, $id_producto)
{
    $gestor = new GestorAdmin();
    $gestor->eliminarImagenProducto($id_img);
    header("Location: index.php?accion=editarProducto&id=$id_producto");
    exit;
}
public function mostrarDashboard()
{
    $gestor = new GestorAdmin();
    $pedidosPorMes = $gestor->pedidosPorMes();
    $masVendidos = $gestor->productosMasVendidos();
    require "Vista/html/dashboard.php";
}
}