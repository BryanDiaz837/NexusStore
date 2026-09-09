<?php
require_once "../php/auth.php";
exigirLogin();

if ($_SESSION["tipo_usuario"] !== "cliente") {
    header("Location: ../admin/panel.php");
    exit;
}

require_once "../php/conexion.php";

$usuario_id = (int)$_SESSION["usuario_id"];
$stmt = $conexion->prepare("SELECT * FROM ventas WHERE id_usuario=? ORDER BY fecha DESC, id_venta DESC");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$ventas = $stmt->get_result();

$titulo = "Mis compras";
$nivel = "../";
include "../php/header.php";
?>
<main class="container py-5">
    <div class="mb-4">
        <p class="text-purple fw-bold mb-1">CLIENTE</p>
        <h1 class="section-title">Mis compras</h1>
    </div>

    <?php if (isset($_GET["ok"])): ?>
        <div class="alert alert-success">Compra registrada correctamente.</div>
    <?php endif; ?>

    <?php if ($ventas->num_rows === 0): ?>
        <div class="empty-state">
            <h4>Aún no tienes compras</h4>
            <p class="text-muted-custom">Visita el catálogo y selecciona tu primer videojuego.</p>
            <a href="productos.php" class="btn btn-primary">Ver catálogo</a>
        </div>
    <?php else: ?>
        <?php while ($v = $ventas->fetch_assoc()): ?>
            <div class="panel-card mb-3">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1">Compra #<?= (int)$v["id_venta"] ?></h5>
                        <span class="small-note"><?= htmlspecialchars($v["fecha"]) ?></span>
                    </div>
                    <div class="price">$<?= number_format($v["total"], 2) ?></div>
                </div>

                <?php
                $detalle = $conexion->prepare("SELECT d.cantidad, d.precio, p.nombre_producto
                                               FROM detalle_venta d
                                               INNER JOIN productos p ON d.id_producto = p.id_producto
                                               WHERE d.id_venta=?");
                $detalle->bind_param("i", $v["id_venta"]);
                $detalle->execute();
                $items = $detalle->get_result();
                ?>
                <div class="table-responsive mt-3">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                        <tbody>
                        <?php while ($i = $items->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($i["nombre_producto"]) ?></td>
                                <td><?= (int)$i["cantidad"] ?></td>
                                <td>$<?= number_format($i["precio"], 2) ?></td>
                                <td>$<?= number_format($i["precio"] * $i["cantidad"], 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</main>
<?php include "../php/footer.php"; ?>
