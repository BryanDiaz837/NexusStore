<?php
require_once "../php/auth.php";
exigirLogin();

if ($_SESSION["tipo_usuario"] !== "cliente") {
    header("Location: ../admin/panel.php");
    exit;
}

require_once "../php/conexion.php";

$categoria = (int)($_GET["categoria"] ?? 0);

if ($categoria > 0) {
    $stmt = $conexion->prepare(
        "SELECT p.*, c.nombre_categoria
         FROM productos p
         INNER JOIN categorias c ON p.id_categoria = c.id_categoria
         WHERE p.id_categoria = ?
         ORDER BY p.nombre_producto"
    );
    $stmt->bind_param("i", $categoria);
    $stmt->execute();
    $productos = $stmt->get_result();
} else {
    $productos = $conexion->query(
        "SELECT p.*, c.nombre_categoria
         FROM productos p
         INNER JOIN categorias c ON p.id_categoria = c.id_categoria
         ORDER BY p.nombre_producto"
    );
}

$categorias = $conexion->query(
    "SELECT * FROM categorias ORDER BY nombre_categoria"
);

$titulo = "Catálogo";
$nivel = "../";
include "../php/header.php";
?>

<main class="container py-5">
    <div class="admin-title-row mb-4">
        <div>
            <p class="text-purple fw-bold mb-1">CATÁLOGO</p>
            <h1 class="section-title mb-0">Elige tu próximo juego</h1>
        </div>

        <form method="GET">
            <select name="categoria" class="form-select" onchange="this.form.submit()">
                <option value="0">Todas las plataformas</option>
                <?php while ($c = $categorias->fetch_assoc()): ?>
                    <option
                        value="<?= (int)$c["id_categoria"] ?>"
                        <?= $categoria === (int)$c["id_categoria"] ? "selected" : "" ?>
                    >
                        <?= htmlspecialchars($c["nombre_categoria"]) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>

    <div class="row g-4">
        <?php while ($p = $productos->fetch_assoc()): ?>

            <?php
            $imagen = $p["imagen"] ?? "";

            if (
                strpos($imagen, "http://") === 0 ||
                strpos($imagen, "https://") === 0
            ) {
                $srcImagen = $imagen;
            } else {
                $srcImagen = "../img/games/" . $imagen;
            }
            ?>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card-nexus">
                    <img
                        src="<?= htmlspecialchars($srcImagen) ?>"
                        class="game-cover"
                        alt="<?= htmlspecialchars($p["nombre_producto"]) ?>"
                    >

                    <div class="p-3">
                        <span class="badge-category">
                            <?= htmlspecialchars($p["nombre_categoria"]) ?>
                        </span>

                        <h5 class="mt-3">
                            <?= htmlspecialchars($p["nombre_producto"]) ?>
                        </h5>

                        <p class="small-note">
                            <?= htmlspecialchars($p["descripcion"]) ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="price">
                                $<?= number_format($p["precio"], 2) ?>
                            </span>

                            <span class="small-note">
                                Stock <?= (int)$p["cantidad"] ?>
                            </span>
                        </div>

                        <?php if ((int)$p["cantidad"] > 0): ?>
                            <a
                                href="comprar.php?id=<?= (int)$p["id_producto"] ?>"
                                class="btn btn-primary w-100"
                            >
                                Comprar
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>
                                Agotado
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</main>

<?php include "../php/footer.php"; ?>
