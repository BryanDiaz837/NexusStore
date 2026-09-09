<?php
require_once "../php/auth.php";
exigirAdmin();
require_once "../php/conexion.php";

$usuarios = $conexion->query("SELECT COUNT(*) total FROM usuarios")->fetch_assoc()["total"];
$productos = $conexion->query("SELECT COUNT(*) total FROM productos")->fetch_assoc()["total"];
$ventas = $conexion->query("SELECT COUNT(*) total FROM ventas")->fetch_assoc()["total"];
$ingresos = $conexion->query("SELECT COALESCE(SUM(total),0) total FROM ventas")->fetch_assoc()["total"];

$titulo = "Panel de administrador";
$nivel = "../";
include "../php/header.php";
?>
<main class="container py-5">
    <div class="admin-title-row mb-4">
        <div>
            <p class="text-purple fw-bold mb-1">ADMINISTRACIÓN</p>
            <h1 class="section-title mb-0">Hola, <?= htmlspecialchars($_SESSION["nombre"]) ?></h1>
        </div>
        <a href="agregar_producto.php" class="btn btn-primary">+ Agregar producto</a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6 col-xl-3"><div class="panel-card"><span class="small-note">Usuarios</span><div class="stat-number"><?= (int)$usuarios ?></div></div></div>
        <div class="col-md-6 col-xl-3"><div class="panel-card"><span class="small-note">Productos</span><div class="stat-number"><?= (int)$productos ?></div></div></div>
        <div class="col-md-6 col-xl-3"><div class="panel-card"><span class="small-note">Ventas</span><div class="stat-number"><?= (int)$ventas ?></div></div></div>
        <div class="col-md-6 col-xl-3"><div class="panel-card"><span class="small-note">Total vendido</span><div class="stat-number">$<?= number_format($ingresos, 2) ?></div></div></div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <a class="panel-card d-block text-white" href="productos.php">
                <h4>Productos</h4>
                <p class="text-muted-custom mb-0">Ver, editar y eliminar videojuegos.</p>
            </a>
        </div>
        <div class="col-md-4">
            <a class="panel-card d-block text-white" href="ventas.php">
                <h4>Ventas</h4>
                <p class="text-muted-custom mb-0">Consultar ventas y sus detalles.</p>
            </a>
        </div>
        <div class="col-md-4">
            <a class="panel-card d-block text-white" href="usuarios.php">
                <h4>Usuarios</h4>
                <p class="text-muted-custom mb-0">Ver las cuentas registradas.</p>
            </a>
        </div>
    </div>
</main>
<?php include "../php/footer.php"; ?>
