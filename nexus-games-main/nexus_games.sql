-- NEXUS GAMES
-- Base de datos para proyecto PHP + MySQL
-- Credenciales incluidas:
-- admin@nexusgames.com / admin123
-- cliente@nexusgames.com / demo123

DROP DATABASE IF EXISTS nexus_games;
CREATE DATABASE nexus_games CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nexus_games;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('administrador','cliente') NOT NULL DEFAULT 'cliente',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(80) NOT NULL,
    descripcion VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(120) NOT NULL,
    descripcion VARCHAR(255),
    precio DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL DEFAULT 0,
    id_categoria INT NOT NULL,
    imagen VARCHAR(150) DEFAULT 'game_1.svg',
    CONSTRAINT fk_producto_categoria
        FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_venta_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE detalle_venta (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_detalle_venta
        FOREIGN KEY (id_venta) REFERENCES ventas(id_venta)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detalle_producto
        FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO usuarios (nombre, correo, contrasena, tipo_usuario) VALUES
('Administrador Nexus', 'admin@nexusgames.com', '$2y$10$9k0O8mJCTzX2AxwA2cz.0OeH1qYwN4JKQqOjqz7M8o7mNhx2eT5qO', 'administrador'),
('Cliente Demo', 'cliente@nexusgames.com', '$2y$10$3KJcEdH7BtfkFkAYonl7hO8gTUkMz5uMwY6roB6Ag.S0rW4m6iROa', 'cliente');

INSERT INTO categorias (nombre_categoria, descripcion) VALUES
('PlayStation', 'Videojuegos para consolas PlayStation'),
('Xbox', 'Videojuegos para consolas Xbox'),
('Nintendo', 'Videojuegos para Nintendo Switch'),
('PC', 'Videojuegos para computadora');

INSERT INTO productos (nombre_producto, descripcion, precio, cantidad, id_categoria, imagen) VALUES
('Minecraft', 'Construye, explora y sobrevive en un mundo de bloques.', 29.99, 20, 4, 'game_1.svg'),
('Grand Theft Auto V', 'Acción y mundo abierto en Los Santos.', 24.99, 14, 1, 'game_2.svg'),
('EA Sports FC 26', 'Fútbol con equipos y modos competitivos.', 69.99, 18, 1, 'game_3.svg'),
('Marvel''s Spider-Man 2', 'Aventura de superhéroes en Nueva York.', 69.99, 10, 1, 'game_4.svg'),
('God of War Ragnarök', 'Kratos y Atreus enfrentan el Ragnarök.', 59.99, 9, 1, 'game_5.svg'),
('Call of Duty: Black Ops 6', 'Acción táctica y multijugador.', 69.99, 22, 2, 'game_6.svg'),
('Cyberpunk 2077', 'RPG de mundo abierto en Night City.', 39.99, 12, 4, 'game_7.svg'),
('Resident Evil 4', 'Supervivencia, acción y terror.', 39.99, 11, 1, 'game_8.svg'),
('The Legend of Zelda: Tears of the Kingdom', 'Explora Hyrule y sus islas celestes.', 59.99, 15, 3, 'game_9.svg'),
('Mario Kart 8 Deluxe', 'Carreras rápidas con personajes de Nintendo.', 59.99, 16, 3, 'game_10.svg'),
('Super Mario Bros. Wonder', 'Plataformas coloridas para Nintendo Switch.', 59.99, 13, 3, 'game_11.svg'),
('Halo Infinite', 'Shooter de ciencia ficción para Xbox.', 49.99, 17, 2, 'game_12.svg'),
('Forza Horizon 5', 'Carreras en un enorme mundo abierto.', 49.99, 19, 2, 'game_13.svg'),
('Elden Ring', 'RPG de acción y fantasía oscura.', 59.99, 8, 4, 'game_14.svg'),
('Hogwarts Legacy', 'Aventura mágica en el mundo de Hogwarts.', 49.99, 10, 4, 'game_15.svg');
