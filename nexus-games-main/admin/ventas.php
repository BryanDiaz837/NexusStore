<?php
require_once "../php/auth.php";
exigirAdmin();
require_once "../php/conexion.php";

$sql = "SELECT v.id_venta, v.fecha, v.total, u.nombre, u.correo
        FROM ventas v INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
        ORDER BY v.fecha DESC, v.id_venta DESC";
$ventas = $conexion->query($sql);

$titulo = "Ventas";
$nivel = "../";
include "../php/header.php";
?>
<main class="container py-5">
    <div class="mb-4">
        <p class="text-purple fw-bold mb-1">ADMIN</p>
        <h1 class="section-title">Ventas realizadas</h1>
    </div>

    <?php if ($ventas->num_rows === 0): ?>
        <div class="empty-state">Todavía no hay ventas registradas.</div>
    <?php else: ?>
        <?php while ($v = $ventas->fetch_assoc()): ?>
            <div class="panel-card mb-3">
                <div class="d-flex flex-wrap justify-content-between gap-3">
                    <div>
                        <h5 class="mb-1">Venta #<?= (int)$v["id_venta"] ?> · <?= htmlspecialchars($v["nombre"]) ?></h5>
                        <div class="small-note"><?= htmlspecialchars($v["correo"]) ?> · <?= htmlspecialchars($v["fecha"]) ?></div>
                    </div>
                    <div class="price">$<?= number_format($v["total"], 2) ?></div>
                </div>
                <?php
                $detalle = $conexion->prepare("SELECT d.cantidad, d.precio, p.nombre_producto
                                               FROM detalle_venta d
                                               INNER JOIN productos p ON d.id_producto = p.id_producto
                                               WHERE d.id_venta = ?");
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
