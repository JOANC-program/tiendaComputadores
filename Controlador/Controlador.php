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

    public function agregarCarrito()
    {
        if (isset($_POST['id_producto'])) {
            $id_producto = $_POST['id_producto'];

            $gestor = new GestorAdmin(); // O GestorCatalogo si obtenerProductoPorId está ahí
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
                    // Obtén la categoría completa como un array
                    $categoria_data = $gestor->obtenerCategoriaPorId($producto['id_categoria']);

                    $_SESSION['carrito'][] = [
                        'id' => $producto['id'],
                        'nombre' => $producto['nombre'],
                        'precio' => $producto['precio'],
                        // ¡EL CAMBIO CLAVE ESTÁ AQUÍ!
                        'categoria' => $categoria_data, // Almacena el array completo de la categoría
                        // Ahora $item['categoria'] en el carrito será como ['id' => 1, 'nombre' => 'Laptops']
                        'cantidad' => 1
                    ];
                }
            }
        }
        header("Location: index.php?accion=catalogo");
        exit;
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
        $imagenes = $gestor->obtenerImagenesPorProducto($id);
        require "Vista/html/editarProducto.php";
    }
    public function actualizarProducto($id, $marca, $modelo, $tipo, $especificaciones, $precio, $id_categoria, $imagenes)
    {
        $gestor = new GestorAdmin();
        // Actualiza los datos principales del producto
        $gestor->actualizarProducto($id, $marca, $modelo, $tipo, $especificaciones, $precio, $id_categoria);

        // Procesa nuevas imágenes si se subieron
        foreach ($imagenes['tmp_name'] as $key => $tmp_name) {
            if ($tmp_name) {
                $nombreArchivo = uniqid() . "_" . basename($imagenes["name"][$key]);
                $rutaDestino = "Vista/img/" . $nombreArchivo;
                move_uploaded_file($tmp_name, $rutaDestino);
                $gestor->guardarImagenProducto($id, $rutaDestino);
            }
        }

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
  public function finalizarPedido()
    {

        $id_cliente = $_SESSION['cliente']['id']; // Asume que el ID del usuario está en $_SESSION['cliente']['id']

        // 2. Obtener el carrito actual de la sesión
        $carrito_actual = $_SESSION['carrito'] ?? [];

        // 3. Validar el carrito antes de procesar el pedido
        if (!is_array($carrito_actual) || empty($carrito_actual)) {
            header("Location: index.php?accion=carrito&mensaje=carrito_vacio");
            exit;
        }

        // 4. Instanciar el GestorPedidos
        $modeloPedidos = new GestorPedidos(); 
        
        // 5. Llamar a procesarPedidoCompleto
        // Este método se encarga de insertar cada línea de producto en tu tabla 'pedidos'
        $pedido_exitoso = $modeloPedidos->procesarPedidoCompleto($id_cliente, $carrito_actual); 

        // 6. Manejar el resultado
        if ($pedido_exitoso) {
            // Pedido(s) creado(s) exitosamente, vaciar el carrito
            unset($_SESSION['carrito']);
            header("Location: index.php?accion=pedidoscliente&mensaje=pedido_ok");
            exit;
        } else {
            // Hubo un error al procesar el pedido
            error_log("Finalizar Compra: procesarPedidoCompleto devolvió false para cliente ID: " . $id_cliente);
            header("Location: index.php?accion=carrito&mensaje=error_pedido");
            exit;
        }
    }

    public function mostrarPedidosCliente()
    {
        $id_cliente = $_SESSION['cliente']['id'];


        $gestorPedidos = new GestorPedidos();

        $pedidos = $gestorPedidos->obtenerPedidosPorCliente($id_cliente);
        require "Vista/html/clientespedidos.php";
 
    }

    public function eliminarCarrito()
    {
        // Verifica si se ha enviado el índice del producto a eliminar
        if (isset($_POST['indice_producto_a_eliminar']) && isset($_SESSION['carrito'])) {
            $indice_a_eliminar = $_POST['indice_producto_a_eliminar'];
            if (isset($_SESSION['carrito'][$indice_a_eliminar])) {
                unset($_SESSION['carrito'][$indice_a_eliminar]); // Elimina el elemento por su índice

                // Opcional pero recomendado: Reindexa el array después de eliminar
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
            }
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
        echo "<script>alert('El correo ya está registrado');window.location='index.php?accion=login';</script>";
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
    public function cerrarSesion()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['carrito']);
        session_unset();
        session_destroy();

        header("Location: index.php?accion=login");
        exit;
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