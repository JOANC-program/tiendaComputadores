**IMPORTANTE**
-USUARIO ADMIN: 
correo:admin@ejemplo.com
contraseña: admin123
 -------
 al eliminar la  un producto se va a eliminar la imagen tambien de la carpeta img
 ---------
 contraseña hasheada
 -------
 solo falto el tamaño de las imagenes
 -----
 base de datos exportada con create database
# Tienda de Computadores

Este es un sistema web básico para la gestión y venta de comptadores y repuestos, desarrollado en PHP puro y MySQL( con algunas funcionalidades en js).
 Permite a los administradores loguearse, gestionar productos(el apartado de productos tiene un CRUD, añadiendo que se pueden agregar y visualizar multiples imagenes y si requiere puede eliminar imagenes independientemente del producto), categorías (tambien exite un CRUD de categorias, estas categorias se visualizaran donde se requieran y seran funcionales, si se elimina una categoria, se eliminaran todos los productos asociados a esta categoria) y pedidos(que se pueden visualizar todos los pedidos registrados y con la posibilidad de cambiar el estado del pedido, esta informacion puede visualizarla el usuario), tambien les permite ver las ventas del mes y los productos mas vendidos en el dashboard.
Tambien se le permite a los  usuarios registrarse , loguearse, acceder al catalogo  ver los diferentes productos agregados, si los productos tienen mas de una imagen se van a mostrar como un carrucel de imagenes, tambien  si puede filtrar los productos por categorias, agregando un carrito de compra de que puede ir agregando los productos que quiere y la cantidad que quiera, ya cuando quiera  hacer la compra se va al carrito  y hace el pedido(si quiere eliminar un producto lop puede hacer), al hacerlo se va a mostrar los pedidos que se han hecho en el apartado de mis pedidos, con su respectivo valor, se puede visualizar el estado del pedido. 
se puede cerrar sesion en ambos apartados. 


## Características

- **Catálogo de productos** con filtro por categoría.
- **Registro  y login de clientes** para solicitar compras y queden registradas con el usuario.
- **Zona de administración** 
  - Agregar, editar y eliminar productos.
  - Agregar, editar y eliminar categorías.
  - Ver listado de pedidos realizados y poder cambiar estado.
  - Dasboard de pedidos del mes y productos mas comprados.
  **Zona de Cliente** 
  - En el catalogo se visualizan los productos disponibles con sus respectivas imagenes
  - Carrito de compra, se pueden agregar  cualquier producto y cantidad del producto
  - Mis pedidos, se pueden visualizar los pedidos  y el estado de los pedidos que se hicieron
## Estructura de carpetas

```
prueba/
├── Controlador/
│   └── Controlador.php
├── Modelo/
│   ├── Conexion.php
│   ├── GestorAdmin.php
│   |── GestorCatalogo.php
|   ├── GestorPedidos.php
├── Vista/
│   └── html/
│       ├── carrito.php
│       ├── catalogo.php
│       ├── categorias.php
│       ├── clientespedidos.php
│       ├── dashboard.html
│       ├── editarCategoria.php
|       ├── editarProductos.php
│       ├── error.html
│       ├── formularioPedido.php
|       ├── login.php
│       ├── pedidos.html
│       ├── productos.php
│   └── css/
│       └── styles.css
|   └── img/
│       └── 
|   └── script/
│       └── script.js
├── index.php
└── README.md
```

## Uso

- Los **clientes** pueden navegar el catálogo, filtrar por categoría, agregar productos al carrito,solicitar compras y visualizar los pedidos y el estado de los pedidos.
- Los **administradores** pueden acceder a la zona admin para gestionar productos, categorías, ver pedidos y ver el dashboard.
## Notas

- El sistema implementa 2 roles, admin y cliente.

**Desarrollado para fines educativos.**