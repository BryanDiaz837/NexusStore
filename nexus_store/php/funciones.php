<?php
// funciones que se repiten en varias paginas

function iniciar_sesion_segura() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function esta_logueado() {
    return isset($_SESSION['id_usuario']);
}

function es_admin() {
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin';
}

// calcula si estamos dentro de admin/ o cliente/ para saber cuantos
// "../" hay que poner en los header(Location:)
function nivel_carpeta() {
    $script = $_SERVER['PHP_SELF'];
    if (strpos($script, '/admin/') !== false || strpos($script, '/cliente/') !== false) {
        return '../';
    }
    return '';
}

function requerir_login() {
    if (!esta_logueado()) {
        header("Location: " . nivel_carpeta() . "login.php");
        exit;
    }
}

function requerir_admin() {
    requerir_login();
    if (!es_admin()) {
        header("Location: " . nivel_carpeta() . "cliente/productos.php");
        exit;
    }
}

// evita que el admin entre a comprar como si fuera cliente
function requerir_cliente() {
    requerir_login();
    if (es_admin()) {
        header("Location: " . nivel_carpeta() . "admin/panel.php");
        exit;
    }
}

// imagenes disponibles para asignar a un producto (nombre de archivo => etiqueta)
function imagenes_disponibles() {
    return [
        'Tarjeta PSN $50.webp'=> 'Tarjeta PSN $50',
        'Mousepad XXL Gamer.avif'=> 'Mousepad XXL Gamer',
        'Mouse Gamer Logitech G502.jpg'=> 'Mouse Gamer Logitech G502',
        'Teclado Mecánico RGB Razer.jpg'=> 'Teclado Mecánico RGB Razer',
        'Silla Gamer Secretlab.jpg'=>  'Silla Gamer Secretlab',
        'Control Xbox Wireless.avif'=>  'Control Xbox Wireless',
        'Control DualSense PS5.webp'=> 'Control DualSense PS5',
        'Minecraft.jpg'=> 'Minecraft',
        'Call of Duty_Black Ops 6.webp'=> 'Call of Duty: Black Ops 6',
        'EA FC 25.webp'=> 'EA FC 25',
        'The Legend of Zelda_TOTK.avif'=> 'The Legend of Zelda: TOTK',
        'God of War Ragnarök.jpg'=> 'God of War Ragnarök',
        'Steam Deck OLED.webp'=> 'Steam Deck OLED',
        'Nintendo Switch OLED.avif'=> 'Nintendo Switch OLED',
        'Xbox Series X.jpg' => 'Xbox Series X',
        'PlayStation 5 Slim.avif'=> 'PlayStation 5 Slim',
        'Audifonos HyperX Cloud II.jpg' => 'Audífonos HyperX Cloud II',
       

        'consola.svg' => 'Consola',
        'videojuego.svg' => 'Videojuego',
        'control.svg' => 'Control / gamepad',
        'audifonos.svg' => 'Audifonos',
        'silla.svg' => 'Silla gamer',
        'teclado.svg' => 'Teclado',
        'mouse.svg' => 'Mouse',
        'mousepad.svg' => 'Mousepad',
        'tarjeta.svg' => 'Tarjeta de regalo',
        'default.svg' => 'Generico'
    ];
}

function limpiar($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}
