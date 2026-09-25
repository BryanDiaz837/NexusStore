<?php
require_once '../php/funciones.php';
iniciar_sesion_segura();
requerir_admin();
require_once '../php/conexion.php';

$titulo_pagina = "Ventas realizadas";
$ruta_base = '../';

$ventas = $conexion->query("
    SELECT v.id_venta, v.fecha, v.total, u.nombre AS cliente, u.correo
    FROM ventas v
    JOIN usuarios u ON v.id_usuario = u.id_usuario
    ORDER BY v.fecha DESC
");

include '../php/header.php';
?>

<div class="container my-5">
  <h2 class="mb-4"><i class="fa-solid fa-chart-line text-accent"></i> Ventas realizadas</h2>

  <div class="table-responsive">
    <table class="table table-nexus align-middle">
      <thead>
        <tr>
          <th>ID Venta</th>
          <th>Cliente</th>
          <th>Correo</th>
          <th>Fecha</th>
          <th>Total</th>
          <th>Detalle</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($v = $ventas->fetch_assoc()): ?>
          <tr>
            <td>#<?php echo $v['id_venta']; ?></td>
            <td><?php echo limpiar($v['cliente']); ?></td>
            <td><?php echo limpiar($v['correo']); ?></td>
            <td><?php echo date('d/m/Y H:i', strtotime($v['fecha'])); ?></td>
            <td class="text-accent fw-bold">$<?php echo number_format($v['total'], 2); ?></td>
            <td>
              <button class="btn btn-sm btn-outline-nexus" type="button" data-bs-toggle="collapse" data-bs-target="#detalle<?php echo $v['id_venta']; ?>">
                Ver productos
              </button>
            </td>
          </tr>
          <tr class="collapse" id="detalle<?php echo $v['id_venta']; ?>">
            <td colspan="6">
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
              ?>
              <table class="table table-sm mb-0" style="background:transparent;">
                <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio unit.</th><th>Subtotal</th></tr></thead>
                <tbody>
                <?php while ($d = $detalles->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo limpiar($d['nombre_producto']); ?></td>
                    <td><?php echo $d['cantidad']; ?></td>
                    <td>$<?php echo number_format($d['precio'], 2); ?></td>
                    <td>$<?php echo number_format($d['precio'] * $d['cantidad'], 2); ?></td>
                  </tr>
                <?php endwhile; ?>
                </tbody>
              </table>
              <?php $stmtD->close(); ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../php/footer.php'; ?>
