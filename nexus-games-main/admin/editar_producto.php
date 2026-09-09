<?php
require_once "../php/auth.php";
exigirAdmin();
require_once "../php/conexion.php";

$id = (int)($_GET["id"] ?? 0);
$stmt = $conexion->prepare("SELECT * FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
    header("Location: productos.php");
    exit;
}

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre_producto"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = (float)($_POST["precio"] ?? 0);
    $cantidad = (int)($_POST["cantidad"] ?? 0);
    $id_categoria = (int)($_POST["id_categoria"] ?? 0);
    $imagen = trim($_POST["imagen"] ?? "");

    $up = $conexion->prepare("UPDATE productos SET nombre_producto=?, descripcion=?, precio=?, cantidad=?, id_categoria=?, imagen=? WHERE id_producto=?");
    $up->bind_param("ssdiisi", $nombre, $descripcion, $precio, $cantidad, $id_categoria, $imagen, $id);

    if ($up->execute()) {
        header("Location: productos.php");
        exit;
    }
    $mensaje = "No se pudo actualizar el producto.";
}

$categorias = $conexion->query("SELECT * FROM categorias ORDER BY nombre_categoria");
$titulo = "Editar producto";
$nivel = "../";
include "../php/header.php";
?>
<main class="container py-5">
    <div class="auth-card mx-auto" style="max-width:700px;">
        <span class="badge-category">ADMIN</span>
        <h2 class="section-title mt-3">Editar videojuego</h2>
        <?php if ($mensaje): ?><div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre_producto" class="form-control" value="<?= htmlspecialchars($producto["nombre_producto"]) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($producto["descripcion"]) ?></textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Precio</label>
                    <input type="number" name="precio" step="0.01" min="0" class="form-control" value="<?= htmlspecialchars($producto["precio"]) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" min="0" class="form-control" value="<?= (int)$producto["cantidad"] ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Categoría</label>
                    <select name="id_categoria" class="form-select" required>
                        <?php while ($c = $categorias->fetch_assoc()): ?>
                            <option value="<?= (int)$c["id_categoria"] ?>" <?= $c["id_categoria"] == $producto["id_categoria"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($c["nombre_categoria"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Imagen</label>
                    <input type="text" name="imagen" class="form-control" value="<?= htmlspecialchars($producto["imagen"]) ?>">
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">Guardar cambios</button>
                <a href="productos.php" class="btn btn-dark-soft">Cancelar</a>
            </div>
        </form>
    </div>
</main>
<?php include "../php/footer.php"; ?>
