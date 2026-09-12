# Nexus Store

Proyecto de tienda gaming (consolas, videojuegos y accesorios) para el curso de Bases de Datos y Desarrollo Web.
PHP + MySQL + Bootstrap.

## Como instalarlo

1. Copiar la carpeta nexus_store dentro de htdocs (XAMPP) o www (WAMP).

2. Abrir phpMyAdmin e importar el archivo nexus_store.sql. Esto crea la base de datos, las 5 tablas y carga 3 categorias y 18 productos de ejemplo.

3. Revisar php/conexion.php por si el usuario o la clave de MySQL son distintos a los de por defecto (root, sin clave).

4. Entrar a http://localhost/nexus_store/instalar_admin.php una sola vez. Esto crea el usuario administrador con la clave ya cifrada. Despues de usarlo, borrar ese archivo.
   - correo: admin@nexusstore.com
   - clave: admin123

5. Ya se puede entrar por index.php.

## Estructura

- index.php, login.php, registro.php, logout.php, acerca.php -> paginas generales
- instalar_admin.php -> se usa una sola vez, despues se borra
- nexus_store.sql -> base de datos completa
- css/estilos.css
- img/ -> logo y los iconos que se usan como imagen de cada producto
- php/conexion.php, funciones.php, header.php, footer.php
- admin/ -> panel, productos (agregar/editar/eliminar), ventas, usuarios
- cliente/ -> catalogo, carrito/comprar, mis_compras

## Que hace el sistema

- Registro y login con contraseñas cifradas (password_hash / password_verify)
- Dos tipos de usuario, admin y cliente, cada uno ve cosas distintas
- Admin puede ver, agregar, editar y eliminar productos, ver las ventas de todos y ver la lista de usuarios registrados
- Cliente puede ver el catalogo, filtrar por categoria, agregar productos a un carrito y confirmar la compra
- Al confirmar una compra se guarda en ventas y detalle_venta, y se descuenta el stock (usa una transaccion para que no quede a medias si algo falla)
- Cliente puede ver su propio historial de compras

## Pendiente para la entrega

Falta armar el PDF con capturas de pantalla del sistema funcionando y la presentacion. Eso hay que hacerlo despues de correr el sitio y navegar los distintos flujos (registro, login, agregar producto, comprar, etc).
