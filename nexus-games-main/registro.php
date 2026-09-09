<?php
session_start();
require_once "php/conexion.php";

$mensaje = "";
$tipo = "danger";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($nombre === "" || $correo === "" || $password === "") {
        $mensaje = "Completa todos los campos.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Ingresa un correo válido.";
    } elseif (strlen($password) < 6) {
        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $verificar = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $verificar->bind_param("s", $correo);
        $verificar->execute();
        $resultado = $verificar->get_result();

        if ($resultado->num_rows > 0) {
            $mensaje = "Ese correo ya está registrado.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $tipo_usuario = "cliente";
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena, tipo_usuario) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $correo, $hash, $tipo_usuario);

            if ($stmt->execute()) {
                $mensaje = "Cuenta creada correctamente. Ya puedes iniciar sesión.";
                $tipo = "success";
            } else {
                $mensaje = "No se pudo crear la cuenta.";
            }
        }
    }
}

$titulo = "Registro";
$nivel = "";
include "php/header.php";
?>
<main class="auth-wrap">
    <div class="container">
        <div class="auth-card mx-auto">
            <span class="badge-category">NUEVA CUENTA</span>
            <h2 class="section-title mt-3">Regístrate</h2>
            <p class="text-muted-custom">Crea tu cuenta para comprar videojuegos.</p>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" minlength="6" required>
                </div>
                <button class="btn btn-primary w-100">Crear cuenta</button>
            </form>
            <p class="small-note mt-3 mb-0">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
        </div>
    </div>
</main>
<?php include "php/footer.php"; ?>
