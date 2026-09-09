<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estaLogueado(): bool {
    return isset($_SESSION['usuario_id']);
}

function esAdmin(): bool {
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'administrador';
}

function exigirLogin(): void {
    if (!estaLogueado()) {
        header("Location: ../login.php");
        exit;
    }
}

function exigirAdmin(): void {
    if (!estaLogueado() || !esAdmin()) {
        header("Location: ../login.php");
        exit;
    }
}
?>
