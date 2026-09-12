<?php
require_once 'php/funciones.php';
iniciar_sesion_segura();
require_once 'php/conexion.php';

$titulo_pagina = "Iniciar sesión";
$ruta_base = '';
$error = '';

// Si ya está logueado, redirigir
if (esta_logueado()) {
    header("Location: " . (es_admin() ? "admin/panel.php" : "cliente/productos.php"));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = limpiar($_POST['correo']);
    $clave = $_POST['contrasena'];

    $stmt = $conexion->prepare("SELECT id_usuario, nombre, contrasena, tipo_usuario FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($clave, $usuario['contrasena'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            header("Location: " . ($usuario['tipo_usuario'] === 'admin' ? "admin/panel.php" : "cliente/productos.php"));
            exit;
        } else {
            $error = "Correo o contraseña incorrectos.";
        }
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
    $stmt->close();
}

include 'php/header.php';
?>

<div class="container">
  <div class="card-auth p-4 p-md-5">
    <h3 class="text-center mb-4"><i class="fa-solid fa-right-to-bracket text-accent"></i> Iniciar sesión</h3>

    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <form method="POST" novalidate>
      <div class="mb-3">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="correo" class="form-control" required>
      </div>
      <div class="mb-4">
        <label class="form-label">Contraseña</label>
        <input type="password" name="contrasena" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-nexus w-100">Ingresar</button>
    </form>
    <p class="text-center small  mt-3 mb-0">¿No tienes cuenta? <a href="registro.php" class="text-accent">Regístrate</a></p>
    <p class="text-center small  mt-2 mb-0">Admin demo: admin@nexusstore.com / admin123</p>
  </div>
</div>

<?php include 'php/footer.php'; ?>
