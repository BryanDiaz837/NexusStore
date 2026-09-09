# NEXUS GAMES — Proyecto PHP + MySQL

Tienda de videojuegos desarrollada para el proyecto de Bases de Datos.

## Tecnologías
- PHP
- MySQL
- HTML
- CSS
- Bootstrap 5

## Requisitos cumplidos
- Registro de usuarios
- Inicio de sesión
- Cierre de sesión
- 2 roles: administrador y cliente
- 5 tablas relacionadas
- 4 categorías
- 15 productos
- CRUD de productos para administrador
- Visualización de usuarios registrados
- Simulación y registro de ventas
- Historial de compras del cliente
- Diseño responsive
- Sin degradados

## Cómo instalar en XAMPP

1. Copia la carpeta `nexus_games` dentro de:
   `C:\xampp\htdocs\`

2. Abre XAMPP e inicia:
   - Apache
   - MySQL

3. Entra a phpMyAdmin:
   `http://localhost/phpmyadmin`

4. Ve a **Importar** y selecciona:
   `nexus_games.sql`

5. Abre:
   `http://localhost/nexus_games/`

## Credenciales de prueba

### Administrador
- Correo: `admin@nexusgames.com`
- Contraseña: `admin123`

### Cliente
- Correo: `cliente@nexusgames.com`
- Contraseña: `demo123`

## Nota
La conexión está configurada para la instalación típica de XAMPP:
- Host: localhost
- Usuario: root
- Contraseña: vacía
- Base de datos: nexus_games

Si tu MySQL usa otra contraseña, modifica `php/conexion.php`.
