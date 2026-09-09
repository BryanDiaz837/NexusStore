<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nivel = $nivel ?? "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? htmlspecialchars($titulo) . " | " : "" ?>NEXUS GAMES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $nivel ?>css/estilos.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-nexus sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= $nivel ?>index.php">
            <span class="logo-box">NG</span>
            <span>NEXUS GAMES</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="<?= $nivel ?>index.php">Inicio</a></li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <?php if ($_SESSION['tipo_usuario'] === 'administrador'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= $nivel ?>admin/panel.php">Panel</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= $nivel ?>cliente/productos.php">Catálogo</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $nivel ?>cliente/mis_compras.php">Mis compras</a></li>
                    <?php endif; ?>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-light btn-sm" href="<?= $nivel ?>logout.php">Cerrar sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $nivel ?>login.php">Iniciar sesión</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-primary btn-sm" href="<?= $nivel ?>registro.php">Crear cuenta</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
