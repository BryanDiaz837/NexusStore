<?php
require_once 'php/funciones.php';
iniciar_sesion_segura();
require_once 'php/conexion.php';

$titulo_pagina = "Regístrate";
$ruta_base = '';
$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiar($_POST['nombre']);
    $correo = limpiar($_POST['correo']);
    $clave = $_POST['contrasena'];
    $clave_confirmar = $_POST['confirmar'];

    if (empty($nombre) || empty($correo) || empty($clave)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";
    } elseif (strlen($clave) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } elseif ($clave !== $clave_confirmar) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el correo ya existe
        $stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Ya existe una cuenta registrada con ese correo.";
        } else {
            $hash = password_hash($clave, PASSWORD_DEFAULT);
            $stmt2 = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena, tipo_usuario) VALUES (?, ?, ?, 'cliente')");
            $stmt2->bind_param("sss", $nombre, $correo, $hash);
            if ($stmt2->execute()) {
                $exito = "¡Cuenta creada con éxito! Ya puedes iniciar sesión.";
            } else {
                $error = "Ocurrió un error al registrar la cuenta.";
            }
            $stmt2->close();
        }
        $stmt->close();
    }
}

include 'php/header.php';
?>

<div class="container">
  <div class="card-auth p-4 p-md-5">
    <h3 class="text-center mb-4"><i class="fa-solid fa-user-plus text-accent"></i> Crear cuenta</h3>

    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <?php if ($exito): ?>
        <div class="alert alert-success"><?php echo $exito; ?> <a href="login.php" class="alert-link">Iniciar sesión</a></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="mb-3">
        <label class="form-label">Nombre completo</label>
        <input type="text" name="nombre" class="form-control" required value="<?php echo isset($_POST['nombre']) ? limpiar($_POST['nombre']) : ''; ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="correo" class="form-control" required value="<?php echo isset($_POST['correo']) ? limpiar($_POST['correo']) : ''; ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" name="contrasena" class="form-control" required minlength="6">
      </div>
      <div class="mb-4">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" name="confirmar" class="form-control" required minlength="6">
      </div>
      <button type="submit" class="btn btn-nexus w-100">Registrarme</button>
    </form>
    <p class="text-center small mt-3 mb-0">¿Ya tienes cuenta? <a href="login.php" class="text-accent">Inicia sesión</a></p>
  </div>
</div>

<?php include 'php/footer.php'; ?>
