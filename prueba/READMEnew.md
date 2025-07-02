# Reto Avanzado (opcional, para subir nivel)

A continuación tienen algunos retos extra que puedes implementar en el proyecto para demostrar un nivel avanzado y diferenciar su entrega.

## Extras avanzados sugeridos

# ✏️ Cambios en el modelo de negocio

Este proyecto ahora pasa de ser una tienda de tenis a una **tienda de computadores y repuestos**. A continuación, se resumen los cambios aplicados y cómo deben reflejarse en el backend:

# Adaptar la base de datos:
- La tabla `productos` ahora tiene campos como: `marca`, `modelo`, `tipo` (Computador o Repuesto), `especificaciones`, `precio` y además soporta **múltiples imágenes** relacionadas (por ejemplo, en una tabla `imagenes_producto` con `id_producto` como clave foránea).
- Categorías adaptadas: "Portátiles", "Computadores de escritorio", "Repuestos".

# Cambios en las vistas:
- Catálogo muestra computadores y repuestos, destacando marca, modelo y especificaciones.
- Panel de administración permite registrar productos detallando tipo, marca, especificaciones técnicas **y subir varias fotos** (usando `input type="file" multiple`).

# Retos adicionales propuestos:
- Carrito de compras mixto (computadores + repuestos).
- Estadísticas de productos más vendidos por tipo.
- Filtro avanzado por tipo, marca o características.
- Backend que gestione múltiples imágenes por producto (subida, eliminación y visualización).

# Autenticación completa y segura
- Implementar login y registro para clientes.
- Guardar contraseñas con hash (`password_hash` / `password_verify`).
- Controlar acceso mediante sesiones para la zona admin.

# Paginación y búsqueda
- Mostrar productos en el catálogo en páginas de 6, 9 o 12 productos.
- Añadir buscador por nombre o filtro por categoría.

# Carrito de compras (simulado)
- Permitir que el cliente agregue varios productos antes de confirmar el pedido.
- Guardar el carrito en la sesión y luego crear el pedido al confirmar.

# Estado del pedido
- Permitir que el administrador cambie el estado de un pedido (Pendiente, Enviado, Entregado, Cancelado).
- Mostrar al cliente el estado actual de sus pedidos.

# Dashboard con gráficas
- Mostrar en el panel admin estadísticas básicas usando Chart.js o similar.
- Ejemplo: número de pedidos por mes, productos más vendidos, etc.







