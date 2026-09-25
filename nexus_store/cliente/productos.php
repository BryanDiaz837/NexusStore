<?php
require_once '../php/funciones.php';
iniciar_sesion_segura();
requerir_cliente();
require_once '../php/conexion.php';

$titulo_pagina = "Catálogo";
$ruta_base = '../';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$mensaje = '';

// Agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_carrito'])) {
    $id_producto = (int) $_POST['id_producto'];
    $cantidad = max(1, (int) $_POST['cantidad']);

    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $producto = $stmt->get_result()->fetch_assoc();

    if ($producto && $producto['cantidad'] >= $cantidad) {
        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$id_producto] = [
                'nombre' => $producto['nombre_producto'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad
            ];
        }
        $mensaje = "\"" . $producto['nombre_producto'] . "\" agregado al carrito.";
    } else {
        $mensaje = "No hay suficiente stock disponible.";
    }
}

// Filtro por categoría
$id_categoria_filtro = isset($_GET['categoria']) ? (int) $_GET['categoria'] : 0;
$categorias = $conexion->query("SELECT * FROM categorias ORDER BY nombre_categoria");

if ($id_categoria_filtro > 0) {
    $stmt = $conexion->prepare("SELECT p.*, c.nombre_categoria FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id_categoria WHERE p.id_categoria = ? ORDER BY p.nombre_producto");
    $stmt->bind_param("i", $id_categoria_filtro);
    $stmt->execute();
    $productos = $stmt->get_result();
} else {
    $productos = $conexion->query("SELECT p.*, c.nombre_categoria FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id_categoria ORDER BY p.nombre_producto");
}

include '../php/header.php';
?>

<div class="container my-5">
  <h2 class="mb-4"><i class="fa-solid fa-store text-accent"></i> Catálogo Nexus Store</h2>

  <?php if ($mensaje): ?><div class="alert alert-info"><?php echo limpiar($mensaje); ?></div><?php endif; ?>

  <div class="mb-4">
    <a href="productos.php" class="btn btn-sm <?php echo $id_categoria_filtro == 0 ? 'btn-nexus' : 'btn-outline-nexus'; ?> me-2">Todas</a>
    <?php
      $categorias->data_seek(0);
      while ($c = $categorias->fetch_assoc()):
    ?>
      <a href="productos.php?categoria=<?php echo $c['id_categoria']; ?>"
         class="btn btn-sm <?php echo $id_categoria_filtro == $c['id_categoria'] ? 'btn-nexus' : 'btn-outline-nexus'; ?> me-2 mb-2">
         <?php echo limpiar($c['nombre_categoria']); ?>
      </a>
    <?php endwhile; ?>
  </div>

  <div class="row g-4">
    <?php while ($p = $productos->fetch_assoc()): ?>
      <div class="col-md-4 col-lg-3">
        <div class="card-nexus">
          <div class="icono-producto"><img src="../img/<?php echo limpiar($p['imagen'] ?: 'default.svg'); ?>" alt="<?php echo limpiar($p['nombre_producto']); ?>"></div>
          <div class="card-body">
            <span class="badge categoria-badge mb-2"><?php echo limpiar($p['nombre_categoria'] ?? 'General'); ?></span>
            <h6 class="card-title"><?php echo limpiar($p['nombre_producto']); ?></h6>
            <p class="small text-muted" style="min-height:40px;"><?php echo limpiar($p['descripcion']); ?></p>
            <p class="precio">$<?php echo number_format($p['precio'], 2); ?></p>
            <p class="small text-muted mb-2">
              Stock: <?php echo $p['cantidad']; ?>
              <?php if ($p['cantidad'] == 0): ?><span class="badge bg-danger">Agotado</span><?php endif; ?>
            </p>

            <?php if ($p['cantidad'] > 0): ?>
              <form method="POST" class="d-flex gap-2">
                <input type="hidden" name="id_producto" value="<?php echo $p['id_producto']; ?>">
                <input type="number" name="cantidad" value="1" min="1" max="<?php echo $p['cantidad']; ?>" class="form-control form-control-sm" style="width:70px;">
                <button type="submit" name="agregar_carrito" class="btn btn-nexus btn-sm w-100">
                  <i class="fa-solid fa-cart-plus"></i> Agregar
                </button>
              </form>
            <?php else: ?>
              <button class="btn btn-secondary btn-sm w-100" disabled>Sin stock</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include '../php/footer.php'; ?>
