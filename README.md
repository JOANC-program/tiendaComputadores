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
# Tienda de Tenis

Este es un sistema web básico para la gestión y venta de tenis, desarrollado en PHP puro y MySQL. Permite a los administradores gestionar productos, categorías y pedidos, y a los clientes ver el catálogo, registrarse y solicitar compras.

este programa se desarrollo partiendo desde el apartado de administrador y luego la vista de  catalogo
, todo se hizo con php , en esta ocacion no se utilizo js

## Características

- **Catálogo de productos** con filtro por categoría.
- **Registro de clientes** para solicitar compras.
- **Zona de administración** para:
  - Agregar, editar y eliminar productos.
  - Agregar y eliminar categorías.
  - Ver listado de pedidos realizados.
- **Pedidos**: Los clientes pueden solicitar la compra de productos, autenticándose con su correo y contraseña.

## Estructura de carpetas

```
prueba/
├── Controlador/
│   └── Controlador.php
├── Modelo/
│   ├── Conexion.php
│   ├── GestorAdmin.php
│   └── GestorCatalogo.php
├── Vista/
│   └── html/
│       ├── admin.php
│       ├── catalogo.php
│       ├── editarProducto.php
│       ├── formularioPedido.php
│       ├── loginAdmin.html
│       ├── registroCliente.php
│       └── error.html
│   └── css/
│       └── styles.css
├── index.php
└── README.md
```

## Uso

- Los **clientes** pueden navegar el catálogo, filtrar por categoría, registrarse y solicitar compras.
- Los **administradores** pueden acceder a la zona admin para gestionar productos, categorías y ver pedidos.
- Al solicitar una compra, el cliente debe autenticarse con su correo y contraseña.

## Notas

- El sistema no implementa roles, por lo que todos los usuarios registrados son clientes.


---

**Desarrollado para fines educativos.**