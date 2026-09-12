<?php
require_once '../php/funciones.php';
iniciar_sesion_segura();
requerir_admin();
require_once '../php/conexion.php';

$titulo_pagina = "Administrar productos";
$ruta_base = '../';

$mensaje = '';
if (isset($_GET['eliminado'])) {
    $mensaje = "Producto eliminado correctamente.";
} elseif (isset($_GET['agregado'])) {
    $mensaje = "Producto agregado correctamente.";
} elseif (isset($_GET['editado'])) {
    $mensaje = "Producto actualizado correctamente.";
}

$productos = $conexion->query("
    SELECT p.*, c.nombre_categoria
    FROM productos p
    LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
    ORDER BY p.id_producto DESC
");

include '../php/header.php';
?>

<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2><i class="fa-solid fa-box-open text-accent"></i> Administrar productos</h2>
    <a href="agregar_producto.php" class="btn btn-nexus"><i class="fa-solid fa-plus"></i> Agregar producto</a>
  </div>

  <?php if ($mensaje): ?><div class="alert alert-success"><?php echo $mensaje; ?></div><?php endif; ?>

  <div class="table-responsive">
    <table class="table table-nexus align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Imagen</th>
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($p = $productos->fetch_assoc()): ?>
          <tr>
            <td>#<?php echo $p['id_producto']; ?></td>
            <td><img src="../img/<?php echo limpiar($p['imagen'] ?: 'default.svg'); ?>" alt="" style="width:45px;height:45px;border-radius:8px;object-fit:cover;"></td>
            <td><?php echo limpiar($p['nombre_producto']); ?></td>
            <td><span class="badge categoria-badge"><?php echo limpiar($p['nombre_categoria'] ?? 'Sin categoría'); ?></span></td>
            <td>$<?php echo number_format($p['precio'], 2); ?></td>
            <td>
              <?php if ($p['cantidad'] <= 5): ?>
                <span class="text-danger fw-bold"><?php echo $p['cantidad']; ?></span>
              <?php else: ?>
                <?php echo $p['cantidad']; ?>
              <?php endif; ?>
            </td>
            <td>
              <a href="editar_producto.php?id=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-outline-nexus"><i class="fa-solid fa-pen"></i></a>
              <a href="eliminar_producto.php?id=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                 <i class="fa-solid fa-trash"></i>
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../php/footer.php'; ?>
