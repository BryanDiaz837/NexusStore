<?php
require_once 'php/funciones.php';
iniciar_sesion_segura();
require_once 'php/conexion.php';

$titulo_pagina = "Inicio";
$ruta_base = '';

$categorias = $conexion->query("SELECT * FROM categorias");
$destacados = $conexion->query("
    SELECT p.*, c.nombre_categoria
    FROM productos p
    LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
    ORDER BY RAND()
    LIMIT 6
");

$iconos_categoria = [
    'Consolas' => 'fa-gamepad',
    'Videojuegos' => 'fa-compact-disc',
    'Accesorios Gaming' => 'fa-headset'
];

include 'php/header.php';
?>

<!-- CARRUSEL DE IMÁGENES HERO -->
<section class="hero-carousel">
    <div id="nexusCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#nexusCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#nexusCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#nexusCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="img/imgslider1.jpg" class="d-block w-100" alt="Nexus Store Hero 1">
                <div class="carousel-overlay"></div>
                <div class="carousel-caption">
                    <h1 class="display-4 fw-bold text-white mb-3">NEXUS STORE</h1>
                    <p class="lead text-light mb-4">
                        Descubre videojuegos, consolas y accesorios de última generación. Todo lo que necesitas para crear el setup perfecto.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <?php if (!esta_logueado()): ?>
                            <a href="registro.php" class="btn btn-primary btn-lg fw-bold px-4">Crear cuenta</a>
                            <a href="login.php" class="btn btn-outline-light btn-lg fw-bold px-4">Iniciar sesión</a>
                        <?php elseif (es_admin()): ?>
                            <a href="admin/panel.php" class="btn btn-primary btn-lg fw-bold px-4">Panel administrador</a>
                        <?php else: ?>
                            <a href="cliente/productos.php" class="btn btn-primary btn-lg fw-bold px-4">Ver catálogo</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <img src="img/imgslider2.jpg" class="d-block w-100" alt="Nexus Store Hero 2">
                <div class="carousel-overlay"></div>
                <div class="carousel-caption">
                    <h1 class="display-4 fw-bold text-white mb-3">VIDEOJUEGOS TOP</h1>
                    <p class="lead text-light mb-4">
                        Descubre los títulos más populares para PlayStation, Xbox y Nintendo Switch.
                    </p>
                    <div>
                        <a href="cliente/productos.php" class="btn btn-primary btn-lg fw-bold px-4">Comprar ahora</a>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <img src="img/imgslider3.jpg" class="d-block w-100" alt="Nexus Store Hero 3">
                <div class="carousel-overlay"></div>
                <div class="carousel-caption">
                    <h1 class="display-4 fw-bold text-white mb-3">ACCESORIOS GAMING</h1>
                    <p class="lead text-light mb-4">
                        Audífonos, controles, teclados y todo para completar tu experiencia de juego.
                    </p>
                    <div>
                        <a href="cliente/productos.php" class="btn btn-primary btn-lg fw-bold px-4">Explorar productos</a>
                    </div>
                </div>
            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#nexusCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#nexusCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>

    </div>
</section>

<!-- CONTENIDO PRINCIPAL -->
<div class="container my-5">

    <h2 class="text-white fw-bold mb-4 border-start border-4 border-primary ps-3">Categorías</h2>

    <div class="row g-4 mb-5">
        <?php while ($cat = $categorias->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card bg-dark text-white border-secondary h-100 p-4 text-center card-hover">
                    <i class="fa-solid <?php echo $iconos_categoria[$cat['nombre_categoria']] ?? 'fa-star'; ?> fa-3x mb-3 text-primary"></i>
                    <h4 class="fw-bold"><?php echo limpiar($cat['nombre_categoria']); ?></h4>
                    <p class="small text-muted mb-0"><?php echo limpiar($cat['descripcion']); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <h2 class="text-white fw-bold mb-4 border-start border-4 border-primary ps-3">Productos destacados</h2>

    <div class="row g-4">
        <?php while ($p = $destacados->fetch_assoc()): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-dark text-white border-secondary h-100 card-hover">
                    <div class="img-container bg-black rounded-top p-2 text-center" style="height: 140px;">
                        <img src="img/<?php echo limpiar($p['imagen'] ?: 'default.svg'); ?>" class="img-fluid h-100" style="object-fit: contain;" alt="<?php echo limpiar($p['nombre_producto']); ?>">
                    </div>
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-secondary mb-2"><?php echo limpiar($p['nombre_categoria']); ?></span>
                            <h6 class="card-title text-truncate" title="<?php echo limpiar($p['nombre_producto']); ?>"><?php echo limpiar($p['nombre_producto']); ?></h6>
                        </div>
                        <p class="card-text fw-bold text-primary fs-5 mb-0 mt-2">$<?php echo number_format($p['precio'], 2); ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</div>

<?php include 'php/footer.php'; ?>