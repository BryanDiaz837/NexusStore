<?php
require_once '../php/funciones.php';
iniciar_sesion_segura();
requerir_admin();
require_once '../php/conexion.php';

$titulo_pagina = "Agregar producto";
$ruta_base = '../';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiar($_POST['nombre_producto']);
    $descripcion = limpiar($_POST['descripcion']);
    $precio = (float) $_POST['precio'];
    $cantidad = (int) $_POST['cantidad'];
    $id_categoria = (int) $_POST['id_categoria'];
    $imagen = limpiar($_POST['imagen']);

    if (empty($nombre) || $precio <= 0 || $cantidad < 0 || $id_categoria <= 0 || empty($imagen)) {
        $error = "Por favor completa todos los campos correctamente.";
    } else {
        $stmt = $conexion->prepare("INSERT INTO productos (nombre_producto, descripcion, precio, cantidad, id_categoria, imagen) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $cantidad, $id_categoria, $imagen);
        if ($stmt->execute()) {
            header("Location: productos.php?agregado=1");
            exit;
        } else {
            $error = "Ocurrió un error al agregar el producto.";
        }
        $stmt->close();
    }
}

$categorias = $conexion->query("SELECT * FROM categorias ORDER BY nombre_categoria");

include '../php/header.php';
?>

<div class="container my-5">
  <div class="card-nexus p-4 p-md-5" style="max-width:650px; margin:0 auto;">
    <h3 class="mb-4"><i class="fa-solid fa-plus text-accent"></i> Agregar producto</h3>

    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Nombre del producto</label>
        <input type="text" name="nombre_producto" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3"></textarea>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Precio ($)</label>
          <input type="number" step="0.01" min="0.01" name="precio" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Cantidad en stock</label>
          <input type="number" min="0" name="cantidad" class="form-control" required>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label">Categoría</label>
        <select name="id_categoria" class="form-select" required>
          <option value="">Selecciona una categoría</option>
          <?php while ($c = $categorias->fetch_assoc()): ?>
            <option value="<?php echo $c['id_categoria']; ?>"><?php echo limpiar($c['nombre_categoria']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label">Imagen del producto</label>
        <select name="imagen" id="selectImagen" class="form-select" required onchange="document.getElementById('previewImagen').src='../img/' + this.value;">
          <?php foreach (imagenes_disponibles() as $archivo => $etiqueta): ?>
            <option value="<?php echo $archivo; ?>"><?php echo $etiqueta; ?></option>
          <?php endforeach; ?>
        </select>
        <div class="text-center mt-3">
          <img id="previewImagen" src="../img/consola.svg" alt="vista previa" style="width:90px;height:90px;border-radius:10px;">
        </div>
      </div>
      <button type="submit" class="btn btn-nexus w-100">Guardar producto</button>
      <a href="productos.php" class="btn btn-outline-nexus w-100 mt-2">Cancelar</a>
    </form>
  </div>
</div>

<?php include '../php/footer.php'; ?>
