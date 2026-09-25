<?php
require_once '../php/funciones.php';
iniciar_sesion_segura();
requerir_cliente();
require_once '../php/conexion.php';

$titulo_pagina = "Mis compras";
$ruta_base = '../';

$id_usuario = $_SESSION['id_usuario'];

$stmt = $conexion->prepare("SELECT * FROM ventas WHERE id_usuario = ? ORDER BY fecha DESC");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$ventas = $stmt->get_result();

include '../php/header.php';
?>

<div class="container my-5">
  <h2 class="mb-4"><i class="fa-solid fa-receipt text-accent"></i> Mis compras</h2>

  <?php if (isset($_GET['compra_exitosa'])): ?>
    <div class="alert alert-success">¡Compra realizada con éxito! Gracias por tu pedido.</div>
  <?php endif; ?>

  <?php if ($ventas->num_rows === 0): ?>
    <div class="card-nexus p-5 text-center">
      <p class="mb-3">Todavía no has realizado ninguna compra.</p>
      <a href="productos.php" class="btn btn-nexus">Ir al catálogo</a>
    </div>
  <?php else: ?>
    <?php while ($v = $ventas->fetch_assoc()): ?>
      <div class="card-nexus p-4 mb-3">
        <div class="d-flex justify-content-between flex-wrap mb-3">
          <div>
            <strong>Compra #<?php echo $v['id_venta']; ?></strong>
            <div class="small text-muted"><?php echo date('d/m/Y H:i', strtotime($v['fecha'])); ?></div>
          </div>
          <div class="text-accent fw-bold fs-5">$<?php echo number_format($v['total'], 2); ?></div>
        </div>
        <table class="table table-sm mb-0" style="background:transparent;">
          <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio unit.</th><th>Subtotal</th></tr></thead>
          <tbody>
          <?php
            $stmtD = $conexion->prepare("
                SELECT p.nombre_producto, d.cantidad, d.precio
                FROM detalle_venta d
                JOIN productos p ON d.id_producto = p.id_producto
                WHERE d.id_venta = ?
            ");
            $stmtD->bind_param("i", $v['id_venta']);
            $stmtD->execute();
            $detalles = $stmtD->get_result();
            while ($d = $detalles->fetch_assoc()):
          ?>
            <tr>
              <td><?php echo limpiar($d['nombre_producto']); ?></td>
              <td><?php echo $d['cantidad']; ?></td>
              <td>$<?php echo number_format($d['precio'], 2); ?></td>
              <td>$<?php echo number_format($d['precio'] * $d['cantidad'], 2); ?></td>
            </tr>
          <?php endwhile; $stmtD->close(); ?>
          </tbody>
        </table>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>

<?php include '../php/footer.php'; ?>
