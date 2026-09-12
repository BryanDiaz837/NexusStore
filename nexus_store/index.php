<?php
require_once 'php/funciones.php';
iniciar_sesion_segura();
require_once 'php/conexion.php';

$titulo_pagina = "Inicio";
$ruta_base = '';

// Traer algunas categorías y productos destacados
$categorias = $conexion->query("SELECT * FROM categorias");
$destacados = $conexion->query("SELECT p.*, c.nombre_categoria FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id_categoria ORDER BY RAND() LIMIT 6");

$iconos_categoria = [
    'Consolas' => 'fa-gamepad',
    'Videojuegos' => 'fa-compact-disc',
    'Accesorios Gaming' => 'fa-headset'
];

include 'php/header.php';
?>

<section class="nexus-hero text-center">
  <div class="container">
    <h1>Bienvenido a <span class="text-accent">NEXUS STORE</span></h1>
    <p class="lead mx-auto" style="max-width:600px;">Consolas, videojuegos y accesorios gamer al mejor precio. Sube de nivel tu setup.</p>
    <?php if (!esta_logueado()): ?>
        <a href="registro.php" class="btn btn-nexus btn-lg me-2">Crear cuenta</a>
        <a href="login.php" class="btn btn-outline-nexus btn-lg">Iniciar sesión</a>
    <?php elseif (es_admin()): ?>
        <a href="admin/panel.php" class="btn btn-nexus btn-lg">Ir al panel de administrador</a>
    <?php else: ?>
        <a href="cliente/productos.php" class="btn btn-nexus btn-lg">Ver catálogo</a>
    <?php endif; ?>
  </div>
</section>

<div class="container my-5">
  <h3 class="mb-4">Categorías</h3>
  <div class="row g-4 mb-5">
    <?php while ($cat = $categorias->fetch_assoc()): ?>
        <div class="col-md-4">
          <div class="card-nexus p-4 text-center">
            <i class="fa-solid <?php echo $iconos_categoria[$cat['nombre_categoria']] ?? 'fa-star'; ?> fa-2x mb-3 text-accent"></i>
            <h5 class="card-title"><?php echo limpiar($cat['nombre_categoria']); ?></h5>
            <p class="small"><?php echo limpiar($cat['descripcion']); ?></p>
          </div>
        </div>
    <?php endwhile; ?>
  </div>

  <h3 class="mb-4">Productos destacados</h3>
  <div class="row g-4">
    <?php while ($p = $destacados->fetch_assoc()): ?>
        <div class="col-md-4 col-lg-2">
          <div class="card-nexus">
            <div class="icono-producto"><img src="img/<?php echo limpiar($p['imagen'] ?: 'default.svg'); ?>" alt="<?php echo limpiar($p['nombre_producto']); ?>"></div>
            <div class="card-body">
              <span class="badge categoria-badge mb-2"><?php echo limpiar($p['nombre_categoria']); ?></span>
              <h6 class="card-title"><?php echo limpiar($p['nombre_producto']); ?></h6>
              <p class="precio mb-0">$<?php echo number_format($p['precio'], 2); ?></p>
            </div>
          </div>
        </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include 'php/footer.php'; ?>
