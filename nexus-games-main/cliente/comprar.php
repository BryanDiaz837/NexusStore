<?php
require_once "../php/auth.php";
exigirLogin();

if ($_SESSION["tipo_usuario"] !== "cliente") {
    header("Location: ../admin/panel.php");
    exit;
}

require_once "../php/conexion.php";

$id = (int)($_GET["id"] ?? $_POST["id_producto"] ?? 0);
$stmt = $conexion->prepare("SELECT p.*, c.nombre_categoria FROM productos p
                            INNER JOIN categorias c ON p.id_categoria=c.id_categoria
                            WHERE p.id_producto=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
    header("Location: productos.php");
    exit;
}

$mensaje = "";
$tipo = "danger";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cantidad = (int)($_POST["cantidad"] ?? 1);

    if ($cantidad < 1 || $cantidad > (int)$producto["cantidad"]) {
        $mensaje = "La cantidad seleccionada no está disponible.";
    } else {
        $total = $cantidad * (float)$producto["precio"];
        $usuario_id = (int)$_SESSION["usuario_id"];

        try {
            $conexion->begin_transaction();

            $venta = $conexion->prepare("INSERT INTO ventas (fecha, total, id_usuario) VALUES (NOW(), ?, ?)");
            $venta->bind_param("di", $total, $usuario_id);
            $venta->execute();
            $id_venta = $conexion->insert_id;

            $detalle = $conexion->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)");
            $precio_unitario = (float)$producto["precio"];
            $detalle->bind_param("iiid", $id_venta, $id, $cantidad, $precio_unitario);
            $detalle->execute();

            $stock = $conexion->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id_producto = ?");
            $stock->bind_param("ii", $cantidad, $id);
            $stock->execute();

            $conexion->commit();
            header("Location: mis_compras.php?ok=1");
            exit;
        } catch (Throwable $e) {
            $conexion->rollback();
            $mensaje = "No se pudo completar la compra.";
        }
    }
}

$titulo = "Comprar";
$nivel = "../";
include "../php/header.php";
?>
<main class="container py-5">
    <div class="row g-4 align-items-start">
        <div class="col-md-5">
       <?php
$imagen = $producto["imagen"];
$srcImagen = preg_match('/^https?:\/\//i', $imagen)
    ? $imagen
    : '../img/games/' . $imagen;
?>

<img src="<?= htmlspecialchars($srcImagen) ?>"
     class="game-cover rounded-4"
     alt="<?= htmlspecialchars($producto["nombre_producto"]) ?>">
        </div>
        <div class="col-md-7">
            <span class="badge-category"><?= htmlspecialchars($producto["nombre_categoria"]) ?></span>
            <h1 class="section-title mt-3"><?= htmlspecialchars($producto["nombre_producto"]) ?></h1>
            <p class="text-muted-custom"><?= htmlspecialchars($producto["descripcion"]) ?></p>
            <div class="price mb-4">$<?= number_format($producto["precio"], 2) ?></div>

            <?php if ($mensaje): ?><div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

            <form method="POST" class="panel-card">
                <input type="hidden" name="id_producto" value="<?= (int)$producto["id_producto"] ?>">
                <label class="form-label">Cantidad</label>
                <input class="form-control qty-input mb-3" type="number" name="cantidad" min="1" max="<?= (int)$producto["cantidad"] ?>" value="1" required>
                <div class="small-note mb-3">Disponibles: <?= (int)$producto["cantidad"] ?></div>
                <button class="btn btn-primary">Confirmar compra</button>
                <a href="productos.php" class="btn btn-dark-soft">Volver</a>
            </form>
        </div>
    </div>
</main>
<?php include "../php/footer.php"; ?>
