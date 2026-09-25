<?php
require_once '../php/funciones.php';
iniciar_sesion_segura();
requerir_admin();
require_once '../php/conexion.php';

$titulo_pagina = "Panel de administrador";
$ruta_base = '../';

$total_productos = $conexion->query("SELECT COUNT(*) AS c FROM productos")->fetch_assoc()['c'];
$total_usuarios = $conexion->query("SELECT COUNT(*) AS c FROM usuarios WHERE tipo_usuario='cliente'")->fetch_assoc()['c'];
$total_ventas = $conexion->query("SELECT COUNT(*) AS c FROM ventas")->fetch_assoc()['c'];
$total_ingresos = $conexion->query("SELECT COALESCE(SUM(total),0) AS s FROM ventas")->fetch_assoc()['s'];

include '../php/header.php';
?>

<div class="container my-5">
  <h2 class="mb-4"><i class="fa-solid fa-shield-halved text-accent"></i> Panel de administrador</h2>
  <>Bienvenido, <?php echo limpiar($_SESSION['nombre']); ?>. Aquí puedes gestionar la tienda Nexus Store.</p>

  <div class="row g-4 my-3">
    <div class="col-md-3 col-6">
      <div class="stat-card">
        <div class="stat-numero"><?php echo $total_productos; ?></div>
        <div class="small">Productos</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="stat-card">
        <div class="stat-numero"><?php echo $total_usuarios; ?></div>
        <div class="small">Clientes registrados</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="stat-card">
        <div class="stat-numero"><?php echo $total_ventas; ?></div>
        <div class="small">Ventas realizadas</div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="stat-card">
        <div class="stat-numero">$<?php echo number_format($total_ingresos, 2); ?></div>
        <div class="small">Ingresos totales</div>
      </div>
    </div>
  </div>

  <div class="row g-4 mt-2">
    <div class="col-md-4">
      <a href="productos.php" class="card-nexus p-4 d-block text-center">
        <i class="fa-solid fa-box-open fa-2x mb-3 text-accent"></i>
        <h5>Administrar productos</h5>
        <p class="small mb-0">Ver, agregar, modificar y eliminar productos.</p>
      </a>
    </div>
    <div class="col-md-4">
      <a href="ventas.php" class="card-nexus p-4 d-block text-center">
        <i class="fa-solid fa-chart-line fa-2x mb-3 text-accent"></i>
        <h5>Ver ventas</h5>
        <p class="small mb-0">Historial completo de ventas realizadas.</p>
      </a>
    </div>
    <div class="col-md-4">
      <a href="usuarios.php" class="card-nexus p-4 d-block text-center">
        <i class="fa-solid fa-users fa-2x mb-3 text-accent"></i>
        <h5>Ver usuarios</h5>
        <p class="small mb-0">Usuarios registrados en la plataforma.</p>
      </a>
    </div>
  </div>
</div>

<?php include '../php/footer.php'; ?>
