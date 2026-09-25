<?php
require_once '../php/funciones.php';
iniciar_sesion_segura();
requerir_cliente();
require_once '../php/conexion.php';

$titulo_pagina = "Mi carrito";
$ruta_base = '../';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$error = '';

// Eliminar un producto del carrito
if (isset($_GET['quitar'])) {
    $id_quitar = (int) $_GET['quitar'];
    unset($_SESSION['carrito'][$id_quitar]);
    header("Location: comprar.php");
    exit;
}

// Confirmar compra
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_compra'])) {
    if (empty($_SESSION['carrito'])) {
        $error = "Tu carrito está vacío.";
    } else {
        $conexion->begin_transaction();
        try {
            $total = 0;
            foreach ($_SESSION['carrito'] as $item) {
                $total += $item['precio'] * $item['cantidad'];
            }

            $id_usuario = $_SESSION['id_usuario'];
            $stmtVenta = $conexion->prepare("INSERT INTO ventas (total, id_usuario) VALUES (?, ?)");
            $stmtVenta->bind_param("di", $total, $id_usuario);
            $stmtVenta->execute();
            $id_venta = $conexion->insert_id;

            foreach ($_SESSION['carrito'] as $id_producto => $item) {
                // Verificar stock disponible
                $stmtStock = $conexion->prepare("SELECT cantidad FROM productos WHERE id_producto = ? FOR UPDATE");
                $stmtStock->bind_param("i", $id_producto);
                $stmtStock->execute();
                $stock = $stmtStock->get_result()->fetch_assoc();

                if (!$stock || $stock['cantidad'] < $item['cantidad']) {
                    throw new Exception("Stock insuficiente para " . $item['nombre']);
                }

                $stmtDetalle = $conexion->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)");
                $stmtDetalle->bind_param("iiid", $id_venta, $id_producto, $item['cantidad'], $item['precio']);
                $stmtDetalle->execute();

                $stmtUpdate = $conexion->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id_producto = ?");
                $stmtUpdate->bind_param("ii", $item['cantidad'], $id_producto);
                $stmtUpdate->execute();
            }

            $conexion->commit();
            $_SESSION['carrito'] = [];
            header("Location: mis_compras.php?compra_exitosa=1");
            exit;

        } catch (Exception $e) {
            $conexion->rollback();
            $error = "Error al procesar la compra: " . $e->getMessage();
        }
    }
}

$total_carrito = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total_carrito += $item['precio'] * $item['cantidad'];
}

include '../php/header.php';
?>

<div class="container my-5">
  <h2 class="mb-4"><i class="fa-solid fa-cart-shopping text-accent"></i> Mi carrito</h2>

  <?php if ($error): ?><div class="alert alert-danger"><?php echo limpiar($error); ?></div><?php endif; ?>

  <?php if (empty($_SESSION['carrito'])): ?>
    <div class="card-nexus p-5 text-center">
      <p class="mb-3">Tu carrito está vacío.</p>
      <a href="productos.php" class="btn btn-nexus">Ir al catálogo</a>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-nexus align-middle">
        <thead>
          <tr><th>Producto</th><th>Precio unit.</th><th>Cantidad</th><th>Subtotal</th><th></th></tr>
        </thead>
        <tbody>
          <?php foreach ($_SESSION['carrito'] as $id_producto => $item): ?>
            <tr>
              <td><?php echo limpiar($item['nombre']); ?></td>
              <td>$<?php echo number_format($item['precio'], 2); ?></td>
              <td><?php echo $item['cantidad']; ?></td>
              <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
              <td><a href="comprar.php?quitar=<?php echo $id_producto; ?>" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-xmark"></i></a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="card-nexus p-4 mt-4" style="max-width:400px; margin-left:auto;">
      <h4 class="d-flex justify-content-between">Total: <span class="text-accent">$<?php echo number_format($total_carrito, 2); ?></span></h4>
      <form method="POST">
        <button type="submit" name="confirmar_compra" class="btn btn-nexus w-100 mt-3">
          <i class="fa-solid fa-check"></i> Confirmar compra
        </button>
      </form>
      <a href="productos.php" class="btn btn-outline-nexus w-100 mt-2">Seguir comprando</a>
    </div>
  <?php endif; ?>
</div>

<?php include '../php/footer.php'; ?>
