<?php
// Este archivo asume que ya se hizo session_start() y se incluyó funciones.php
$ruta_base = isset($ruta_base) ? $ruta_base : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titulo_pagina) ? $titulo_pagina . ' | Nexus Store' : 'Nexus Store'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $ruta_base; ?>css/estilos.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark nexus-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?php echo $ruta_base; ?>index.php">
      <img src="<?php echo $ruta_base; ?>img/logo.svg" alt="Nexus Store" height="38">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="<?php echo $ruta_base; ?>index.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $ruta_base; ?>acerca.php">Nosotros</a></li>

        <?php if (esta_logueado() && !es_admin()): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo $ruta_base; ?>cliente/productos.php">Catálogo</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $ruta_base; ?>cliente/comprar.php">
                <i class="fa-solid fa-cart-shopping"></i> Carrito
                <?php if (!empty($_SESSION['carrito'])): ?>
                    <span class="badge rounded-pill bg-danger"><?php echo count($_SESSION['carrito']); ?></span>
                <?php endif; ?>
            </a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $ruta_base; ?>cliente/mis_compras.php">Mis compras</a></li>
        <?php endif; ?>

        <?php if (esta_logueado() && es_admin()): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo $ruta_base; ?>admin/panel.php">Panel Admin</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $ruta_base; ?>admin/productos.php">Productos</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $ruta_base; ?>admin/ventas.php">Ventas</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo $ruta_base; ?>admin/usuarios.php">Usuarios</a></li>
        <?php endif; ?>

        <?php if (esta_logueado()): ?>
            <li class="nav-item ms-lg-3">
                <span class="nav-link text-muted small">
                    <i class="fa-solid fa-user"></i> <?php echo limpiar($_SESSION['nombre']); ?>
                </span>
            </li>
            <li class="nav-item">
                <a class="btn btn-outline-nexus btn-sm ms-lg-2" href="<?php echo $ruta_base; ?>logout.php">Cerrar sesión</a>
            </li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo $ruta_base; ?>login.php">Iniciar sesión</a></li>
            <li class="nav-item">
                <a class="btn btn-nexus btn-sm ms-lg-2" href="<?php echo $ruta_base; ?>registro.php">Regístrate</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
