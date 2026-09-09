<?php
require_once "../php/auth.php";
exigirAdmin();
require_once "../php/conexion.php";

$usuarios = $conexion->query("SELECT id_usuario, nombre, correo, tipo_usuario, creado_en FROM usuarios ORDER BY id_usuario DESC");
$titulo = "Usuarios";
$nivel = "../";
include "../php/header.php";
?>
<main class="container py-5">
    <div class="mb-4">
        <p class="text-purple fw-bold mb-1">ADMIN</p>
        <h1 class="section-title">Usuarios registrados</h1>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Registro</th></tr></thead>
            <tbody>
            <?php while ($u = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$u["id_usuario"] ?></td>
                    <td><?= htmlspecialchars($u["nombre"]) ?></td>
                    <td><?= htmlspecialchars($u["correo"]) ?></td>
                    <td><?= htmlspecialchars($u["tipo_usuario"]) ?></td>
                    <td><?= htmlspecialchars($u["creado_en"]) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include "../php/footer.php"; ?>
