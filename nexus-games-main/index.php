<?php
$titulo = "Inicio";
$nivel = "";
require_once "php/conexion.php";

$sql = "SELECT p.*, c.nombre_categoria
        FROM productos p
        INNER JOIN categorias c ON p.id_categoria = c.id_categoria
        ORDER BY p.id_producto ASC
        LIMIT 8";
$productos = $conexion->query($sql);

include "php/header.php";
?>
<section class="hero">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7">
                <span class="badge-category mb-3">TIENDA DE VIDEOJUEGOS</span>
                <h1 class="hero-title">Juega más.<br><span class="text-purple">Elige tu próxima aventura.</span></h1>
                <p class="lead text-muted-custom mt-4">
                    Encuentra títulos para PlayStation, Xbox, Nintendo y PC en un solo lugar.
                </p>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <?php if (isset($_SESSION['usuario_id']) && $_SESSION['tipo_usuario'] === 'cliente'): ?>
                        <a href="cliente/productos.php" class="btn btn-primary btn-lg">Ver catálogo</a>
                    <?php elseif (isset($_SESSION['usuario_id']) && $_SESSION['tipo_usuario'] === 'administrador'): ?>
                        <a href="admin/panel.php" class="btn btn-primary btn-lg">Ir al panel</a>
                    <?php else: ?>
                        <a href="registro.php" class="btn btn-primary btn-lg">Crear cuenta</a>
                        <a href="login.php" class="btn btn-dark-soft btn-lg">Iniciar sesión</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-card">
                    <div class="d-flex justify-content-between border-bottom border-secondary-subtle pb-3 mb-3">
                        <span>Catálogo</span><strong>15 juegos</strong>
                    </div>
                    <div class="d-flex justify-content-between border-bottom border-secondary-subtle pb-3 mb-3">
                        <span>Categorías</span><strong>4 plataformas</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Experiencia</span><strong class="text-purple">100% gaming</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="container py-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <p class="text-purple fw-bold mb-1">DESTACADOS</p>
            <h2 class="section-title mb-0">Videojuegos populares</h2>
        </div>
    </div>

    <div class="row g-4">
        <?php if ($productos): while ($p = $productos->fetch_assoc()): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card-nexus">
                  <?php
$imagen = $p['imagen'];
$srcImagen = preg_match('/^https?:\/\//i', $imagen)
    ? $imagen
    : 'img/games/' . $imagen;
?>

<img src="<?= htmlspecialchars($srcImagen) ?>"
     class="game-cover"
     alt="<?= htmlspecialchars($p['nombre_producto']) ?>">
                        <span class="badge-category"><?= htmlspecialchars($p['nombre_categoria']) ?></span>
                        <h5 class="mt-3 mb-2"><?= htmlspecialchars($p['nombre_producto']) ?></h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price">$<?= number_format($p['precio'], 2) ?></span>
                            <span class="small-note">Stock: <?= (int)$p['cantidad'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; endif; ?>
    </div>
</main>
<?php include "php/footer.php"; ?>
