<?php
session_start();
require_once "php/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conexion->prepare("SELECT id_usuario, nombre, correo, contrasena, tipo_usuario FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($password, $usuario["contrasena"])) {
            $_SESSION["usuario_id"] = $usuario["id_usuario"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["tipo_usuario"] = $usuario["tipo_usuario"];

            if ($usuario["tipo_usuario"] === "administrador") {
                header("Location: admin/panel.php");
            } else {
                header("Location: cliente/productos.php");
            }
            exit;
        }
    }

    $mensaje = "Correo o contraseña incorrectos.";
}

$titulo = "Iniciar sesión";
$nivel = "";
include "php/header.php";
?>
<main class="auth-wrap">
    <div class="container">
        <div class="auth-card mx-auto">
            <span class="badge-category">ACCESO</span>
            <h2 class="section-title mt-3">Iniciar sesión</h2>
            <p class="text-muted-custom">Entra a tu cuenta de NEXUS GAMES.</p>

            <?php if ($mensaje): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Entrar</button>
            </form>
            <div class="small-note mt-4">
                <strong>Administrador:</strong> admin@nexusgames.com / admin123<br>
                <strong>Cliente demo:</strong> cliente@nexusgames.com / demo123
            </div>
        </div>
    </div>
</main>
<?php include "php/footer.php"; ?>
