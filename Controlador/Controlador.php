<?php
class Controlador
{
    public function verpagina($ruta)
    {
        require_once $ruta;
    }
    
   
    
    public function ingresar($correo, $contrasena)
    {
        $gestor = new GestorAdmin();
        $admin = $gestor->verificarAdmin($correo, $contrasena);
        if ($admin) {
            $_SESSION['admin'] = $correo;
            header('Location: index.php?accion=productos'); // Página de administración
            exit;
        }

        $existe = $gestor->ingresar($correo, $contrasena);
        if ($existe) {
            $_SESSION['usuario'] = $correo;
            header('Location: index.php?accion=catalogo'); // Página de catálogo para clientes
            exit;
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos');window.location='index.php?accion=login';</script>";
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
    public function agregarCarrito()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }
        $id_producto = $_POST['id_producto'];
        $gestor = new GestorAdmin();
        $producto = $gestor->obtenerProductoPorId($id_producto);
        if (!$producto) {
            header('Location: index.php?accion=catalogo');
            exit;
        }
        // Obtener nombre de la categoría
        $categoria = '';
        if (!empty($producto['id_categoria'])) {
            $cat = $gestor->obtenerCategoriaPorId($producto['id_categoria']);
            $categoria = $cat ? $cat['nombre'] : '';
        }
        // Inicializar carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        // Si ya está en el carrito, sumar cantidad
        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto]['cantidad']++;
        } else {
            $_SESSION['carrito'][$id_producto] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'categoria' => $categoria,
                'precio' => $producto['precio'],
                'cantidad' => 1
            ];
        }
        header('Location: index.php?accion=carrito');
        exit;
    }
    public function mostrarCarrito()
    {
        require_once 'Vista/html/carrito.php';
    }

    public function eliminarCarrito()
    {
        if (isset($_POST['id_producto']) && isset($_SESSION['carrito'][$_POST['id_producto']])) {
            unset($_SESSION['carrito'][$_POST['id_producto']]);
        }
        header('Location: index.php?accion=carrito');
        exit;
    }

    public function finalizarPedido()
    {
        if (!isset($_SESSION['usuario']) || empty($_SESSION['carrito'])) {
            header('Location: index.php?accion=carrito');
            exit;
        }
        $gestor = new GestorAdmin();
        $usuario = $gestor->obtenerUsuarioPorCorreo($_SESSION['usuario']);
        $id_usuario = $usuario ? $usuario['id'] : null;
        $fecha = date('Y-m-d');
        $estado = 'Pendiente';
        foreach ($_SESSION['carrito'] as $item) {
            $gestor->guardarPedido($id_usuario, $item['id'], $item['cantidad'], $fecha, $estado);
        }
        unset($_SESSION['carrito']);
        header('Location: index.php?accion=catalogo&mensaje=pedido_ok');
        exit;
    }
}
