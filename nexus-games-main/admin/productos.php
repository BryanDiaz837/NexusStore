<?php
require_once "../php/auth.php";
exigirAdmin();
require_once "../php/conexion.php";

if (isset($_GET["eliminar"])) {
    $id = (int)$_GET["eliminar"];
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id);
    try {
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        // Si el producto ya forma parte de una venta, MySQL protegerá la relación.
    }
    header("Location: productos.php");
    exit;
}

$sql = "SELECT p.*, c.nombre_categoria FROM productos p
        INNER JOIN categorias c ON p.id_categoria = c.id_categoria
        ORDER BY p.id_producto DESC";
$productos = $conexion->query($sql);

$titulo = "Administrar productos";
$nivel = "../";
include "../php/header.php";
?>
<main class="container py-5">
    <div class="admin-title-row mb-4">
        <div>
            <p class="text-purple fw-bold mb-1">ADMIN</p>
            <h1 class="section-title mb-0">Productos</h1>
        </div>
        <a href="agregar_producto.php" class="btn btn-primary">+ Agregar producto</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr><th>ID</th><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php while ($p = $productos->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$p["id_producto"] ?></td>
                    <td><?= htmlspecialchars($p["nombre_producto"]) ?></td>
                    <td><?= htmlspecialchars($p["nombre_categoria"]) ?></td>
                    <td>$<?= number_format($p["precio"], 2) ?></td>
                    <td><?= (int)$p["cantidad"] ?></td>
                    <td class="text-nowrap">
                        <a href="editar_producto.php?id=<?= (int)$p["id_producto"] ?>" class="btn btn-sm btn-outline-light">Editar</a>
                        <a href="productos.php?eliminar=<?= (int)$p["id_producto"] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include "../php/footer.php"; ?>
