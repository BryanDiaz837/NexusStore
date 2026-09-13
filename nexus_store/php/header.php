<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titulo_pagina) ? $titulo_pagina . ' | Nexus Store' : 'Nexus Store'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>css/estilos.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-nexus">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>index.php">
            <img src="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>img/logo.svg"
                 alt="Nexus Store"
                 height="42">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>acerca.php">Acerca de</a>
                </li>

                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>admin/panel.php">Panel</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>cliente/productos.php">Catálogo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>cliente/carrito.php">Carrito</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>cliente/mis_compras.php">Mis compras</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <span class="nav-link text-primary">Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>logout.php">Salir</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>login.php">Iniciar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ms-lg-2" href="<?php echo isset($ruta_base) ? $ruta_base : ''; ?>registro.php">Registrarse</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>