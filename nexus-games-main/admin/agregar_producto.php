<?php
require_once "../php/auth.php";
exigirAdmin();
require_once "../php/conexion.php";

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre_producto"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = (float)($_POST["precio"] ?? 0);
    $cantidad = (int)($_POST["cantidad"] ?? 0);
    $id_categoria = (int)($_POST["id_categoria"] ?? 0);
    $imagen = trim($_POST["imagen"] ?? "game_1.svg");

    if ($nombre && $precio >= 0 && $cantidad >= 0 && $id_categoria > 0) {
        $stmt = $conexion->prepare("INSERT INTO productos (nombre_producto, descripcion, precio, cantidad, id_categoria, imagen) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $cantidad, $id_categoria, $imagen);
        if ($stmt->execute()) {
            header("Location: productos.php");
            exit;
        }
    }
    $mensaje = "Revisa los datos ingresados.";
}

$categorias = $conexion->query("SELECT * FROM categorias ORDER BY nombre_categoria");
$titulo = "Agregar producto";
$nivel = "../";
include "../php/header.php";
?>
<main class="container py-5">
    <div class="auth-card mx-auto" style="max-width:700px;">
        <span class="badge-category">ADMIN</span>
        <h2 class="section-title mt-3">Agregar videojuego</h2>
        <?php if ($mensaje): ?><div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre_producto" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Precio</label>
                    <input type="number" name="precio" step="0.01" min="0" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" min="0" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Categoría</label>
                    <select name="id_categoria" class="form-select" required>
                        <?php while ($c = $categorias->fetch_assoc()): ?>
                            <option value="<?= (int)$c["id_categoria"] ?>"><?= htmlspecialchars($c["nombre_categoria"]) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Imagen</label>
                    <input type="text" name="imagen" class="form-control" value="game_1.svg">
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">Guardar</button>
                <a href="productos.php" class="btn btn-dark-soft">Cancelar</a>
            </div>
        </form>
    </div>
</main>
<?php include "../php/footer.php"; ?>
