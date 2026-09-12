<?php
require_once '../php/funciones.php';
iniciar_sesion_segura();
requerir_admin();
require_once '../php/conexion.php';

$titulo_pagina = "Usuarios registrados";
$ruta_base = '../';

$usuarios = $conexion->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC");

include '../php/header.php';
?>

<div class="container my-5">
  <h2 class="mb-4"><i class="fa-solid fa-users text-accent"></i> Usuarios registrados</h2>

  <div class="table-responsive">
    <table class="table table-nexus align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Tipo</th>
          <th>Fecha de registro</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($u = $usuarios->fetch_assoc()): ?>
          <tr>
            <td>#<?php echo $u['id_usuario']; ?></td>
            <td><?php echo limpiar($u['nombre']); ?></td>
            <td><?php echo limpiar($u['correo']); ?></td>
            <td>
              <span class="badge <?php echo $u['tipo_usuario'] === 'admin' ? 'badge-admin' : 'badge-cliente'; ?>">
                <?php echo ucfirst($u['tipo_usuario']); ?>
              </span>
            </td>
            <td><?php echo date('d/m/Y H:i', strtotime($u['fecha_registro'])); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../php/footer.php'; ?>
