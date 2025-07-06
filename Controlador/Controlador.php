<?php
class Controlador
{
    public function verpagina($ruta)
    {
        require_once $ruta;
    }

    /* public function loginAdmin($correo, $contrasena)
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

    
}*/

    public function ingresar($correo, $contrasena)
    {
         unset($_SESSION['admin']);
        unset($_SESSION['cliente']);
        $gestor = new GestorAdmin();
        $admin = $gestor->verificarAdmin($correo, $contrasena);
        if ($admin) {

            $_SESSION['admin'] = $correo;
            header('Location: index.php?accion=productos'); // Página de administración
            exit;
        }

        $cliente_data = $gestor->ingresar($correo, $contrasena); 

        if ($cliente_data) { // Si $cliente_data no es false (es decir, las credenciales son correctas)
            // Guarda el ID y el correo del cliente como un array en la sesión
            $_SESSION['cliente'] = [
                'id' => $cliente_data['id'], 
                'correo' => $cliente_data['correo']
            ];
            header('Location: index.php?accion=catalogo'); // Página de catálogo para clientes
            exit;
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos');window.location='index.php?accion=login';</script>";
            exit;
        }
    }
    public function mostrarCarrito()
    {
        require_once 'Vista/html/carrito.php';
    }
    
public function agregarCarrito() {
    if (isset($_POST['id_producto'])) {
        $id_producto = $_POST['id_producto'];

        // Estás instanciando GestorAdmin, no GestorCatalogo aquí.
        // Si obtenerProductoPorId y obtenerCategoriaPorId están en GestorAdmin, está bien.
        // Si están en GestorCatalogo, deberías instanciar GestorCatalogo aquí.
        $gestor = new GestorAdmin(); 
        $producto = $gestor->obtenerProductoPorId($id_producto);

        if ($producto) {
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }

            $encontrado = false;
            foreach ($_SESSION['carrito'] as &$item) {
                if ($item['id'] == $id_producto) {
                    $item['cantidad']++;
                    $encontrado = true;
                    break;
                }
            }
            unset($item);

            if (!$encontrado) {
                $categoria_data = $gestor->obtenerCategoriaPorId($producto['id_categoria']);
                
                $_SESSION['carrito'][] = [
                    'id' => $producto['id'],
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'categoria' => $categoria_data['nombre'], 
                    'cantidad' => 1
                ];
            }
        }
    }
    header("Location: index.php?accion=catalogo"); 
    exit;
}


public function finalizarPedido() {
    $conexion = new Conexion();
    if (!isset($_SESSION['cliente'])) {
        header("Location: index.php?accion=login"); 
        exit;
    }
    if (empty($_SESSION['carrito'])) {
        header("Location: index.php?accion=carrito&mensaje=carrito_vacio"); 
        exit;
    }
     $id_cliente = $_SESSION['cliente']['id']; 
        $modeloPedidos= new GestorPedidos();

    try {
        // Inicia una transacción si vas a realizar múltiples inserciones en la base de datos
        $conexion->beginTransaction();

           $id_pedido = $modeloPedidos->crearPedido($id_cliente); // Método para crear el registro principal del pedido

        if (!$id_pedido) {
            throw new Exception("Error al crear el pedido principal.");
        }
       foreach ($_SESSION['carrito'] as $item) {
            $id_producto = $item['id'];
            $cantidad = $item['cantidad'];
            $precio_unitario = $item['precio']; 

            $modeloPedidos->agregarProductoAPedido($id_pedido, $id_producto, $cantidad, $precio_unitario);
        }

        // Confirma la transacción
        $conexion->commit();

        // Limpia el carrito después de una creación de pedido exitosa
        unset($_SESSION['carrito']);

        header("Location: index.php?accion=pedidoscliente&mensaje=pedido_ok"); // Redirecciona a los pedidos del cliente con mensaje de éxito
        exit;

    } catch (Exception $e) {
        $conexion->rollBack(); // Revierte la transacción en caso de error
        // Registra el error y redirecciona con un mensaje de error
        error_log("Error al finalizar el pedido: " . $e->getMessage());
        header("Location: index.php?accion=carrito&mensaje=error_pedido");
        exit;
    }
}

    public function guardarProducto($nombre, $precio, $descripcion, $id_categoria, $imagen)
    {
        $gestor = new GestorAdmin();
        // Guardar imagen si se subió
        $rutaImagen = "";
        if ($imagen["tmp_name"]) {
            $nombreArchivo = uniqid() . "_" . basename($imagen["name"]);
            $rutaDestino = "Vista/img/" . $nombreArchivo;
            move_uploaded_file($imagen["tmp_name"], $rutaDestino);
            $rutaImagen = $rutaDestino;
        }

        $gestor->guardarProducto($nombre, $precio, $descripcion, $id_categoria, $rutaImagen);

        // Redirigir para evitar reenvío del formulario
        header("Location: index.php?accion=productos");
        exit;
    }
    public function editarProducto($id)
    {
        $gestor = new GestorAdmin();
        $producto = $gestor->obtenerProductoPorId($id);
        $categorias = $gestor->listarCategorias();
        $imagenes = $gestor->obtenerImagenesPorProducto($id);
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
    // Dentro de Controlador.php

public function eliminarCarrito() {
    if (isset($_POST['id_producto']) && isset($_SESSION['carrito'])) {
        $id_producto_a_eliminar = $_POST['id_producto'];
        
        foreach ($_SESSION['carrito'] as $key => $item) {
            if ($item['id'] == $id_producto_a_eliminar) {
                unset($_SESSION['carrito'][$key]); // Elimina el artículo
                break;
            }
        }
        // Reindexa el array si es necesario (opcional pero buena práctica después de eliminar)
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); 
    }
    header("Location: index.php?accion=carrito"); // Redirecciona de nuevo al carrito
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
