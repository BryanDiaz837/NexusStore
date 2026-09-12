
CREATE DATABASE IF NOT EXISTS nexus_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nexus_store;

-- Tabla usuarios

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla categorias

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255)
) ENGINE=InnoDB;

-- Tabla productos

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(150) NOT NULL,
    descripcion VARCHAR(255),
    precio DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL DEFAULT 0,
    id_categoria INT,
    imagen VARCHAR(255) DEFAULT 'default.svg',
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tabla ventas

CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla detalle_venta

CREATE TABLE detalle_venta (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id_venta) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
) ENGINE=InnoDB;

-- el usuario admin no se inserta aqui porque la contraseña tiene que
-- pasar por password_hash() de PHP. correr instalar_admin.php una vez
-- despues de importar esto (correo admin@nexusstore.com / clave admin123)

-- categorias
INSERT INTO categorias (nombre_categoria, descripcion) VALUES
('Consolas', 'Consolas de videojuegos de última generación'),
('Videojuegos', 'Juegos físicos y digitales para todas las plataformas'),
('Accesorios Gaming', 'Controles, audífonos, sillas y periféricos gamer');

-- productos
INSERT INTO productos (nombre_producto, descripcion, precio, cantidad, id_categoria, imagen) VALUES
('PlayStation 5 Slim', 'Consola de última generación con lector de disco', 499.99, 10, 1, 'consola.svg'),
('Xbox Series X', 'Consola de nueva generación 1TB', 479.99, 8, 1, 'consola.svg'),
('Nintendo Switch OLED', 'Consola híbrida con pantalla OLED de 7"', 349.99, 15, 1, 'consola.svg'),
('Steam Deck OLED', 'Consola portátil para PC gaming', 549.99, 6, 1, 'consola.svg'),
('God of War Ragnarök', 'Juego de acción y aventura para PS5', 59.99, 20, 2, 'videojuego.svg'),
('The Legend of Zelda: TOTK', 'Aventura para Nintendo Switch', 64.99, 18, 2, 'videojuego.svg'),
('EA FC 25', 'Simulador de fútbol multiplataforma', 54.99, 25, 2, 'videojuego.svg'),
('Elden Ring', 'RPG de acción de mundo abierto', 49.99, 22, 2, 'videojuego.svg'),
('Call of Duty: Black Ops 6', 'Shooter en primera persona', 59.99, 30, 2, 'videojuego.svg'),
('Minecraft', 'Juego de construcción y supervivencia', 26.99, 40, 2, 'videojuego.svg'),
('Control DualSense PS5', 'Control inalámbrico para PlayStation 5', 69.99, 35, 3, 'control.svg'),
('Control Xbox Wireless', 'Control inalámbrico para Xbox Series X/S', 59.99, 30, 3, 'control.svg'),
('Audífonos HyperX Cloud II', 'Audífonos gamer con sonido envolvente 7.1', 79.99, 20, 3, 'audifonos.svg'),
('Silla Gamer Secretlab', 'Silla ergonómica para largas sesiones de juego', 349.99, 8, 3, 'silla.svg'),
('Teclado Mecánico RGB Razer', 'Teclado mecánico con retroiluminación RGB', 89.99, 15, 3, 'teclado.svg'),
('Mouse Gamer Logitech G502', 'Mouse óptico de alta precisión', 49.99, 25, 3, 'mouse.svg'),
('Mousepad XXL Gamer', 'Mousepad extendido con base antideslizante', 19.99, 40, 3, 'mousepad.svg'),
('Tarjeta PSN $50', 'Tarjeta de regalo para PlayStation Store', 50.00, 50, 2, 'tarjeta.svg');
