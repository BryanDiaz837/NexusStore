<?php
require_once '../php/funciones.php';
iniciar_sesion_segura();
requerir_admin();
require_once '../php/conexion.php';

$titulo_pagina = "Editar producto";
$ruta_base = '../';
$error = '';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conexion->prepare("SELECT * FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
    header("Location: productos.php");
    exit;
}

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
        $update = $conexion->prepare("UPDATE productos SET nombre_producto=?, descripcion=?, precio=?, cantidad=?, id_categoria=?, imagen=? WHERE id_producto=?");
        $update->bind_param("ssdiisi", $nombre, $descripcion, $precio, $cantidad, $id_categoria, $imagen, $id);
        if ($update->execute()) {
            header("Location: productos.php?editado=1");
            exit;
        } else {
            $error = "Ocurrió un error al actualizar el producto.";
        }
        $update->close();
    }
    // refrescar datos del producto con lo enviado para mostrar en el formulario
    $producto = array_merge($producto, [
        'nombre_producto' => $nombre, 'descripcion' => $descripcion,
        'precio' => $precio, 'cantidad' => $cantidad, 'id_categoria' => $id_categoria,
        'imagen' => $imagen
    ]);
}

$categorias = $conexion->query("SELECT * FROM categorias ORDER BY nombre_categoria");

include '../php/header.php';
?>

<div class="container my-5">
  <div class="card-nexus p-4 p-md-5" style="max-width:650px; margin:0 auto;">
    <h3 class="mb-4"><i class="fa-solid fa-pen text-accent"></i> Editar producto</h3>

    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Nombre del producto</label>
        <input type="text" name="nombre_producto" class="form-control" required value="<?php echo limpiar($producto['nombre_producto']); ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3"><?php echo limpiar($producto['descripcion']); ?></textarea>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Precio ($)</label>
          <input type="number" step="0.01" min="0.01" name="precio" class="form-control" required value="<?php echo $producto['precio']; ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Cantidad en stock</label>
          <input type="number" min="0" name="cantidad" class="form-control" required value="<?php echo $producto['cantidad']; ?>">
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label">Categoría</label>
        <select name="id_categoria" class="form-select" required>
          <?php while ($c = $categorias->fetch_assoc()): ?>
            <option value="<?php echo $c['id_categoria']; ?>" <?php echo ($c['id_categoria'] == $producto['id_categoria']) ? 'selected' : ''; ?>>
                <?php echo limpiar($c['nombre_categoria']); ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label">Imagen del producto</label>
        <select name="imagen" class="form-select" required onchange="document.getElementById('previewImagen').src='../img/' + this.value;">
          <?php foreach (imagenes_disponibles() as $archivo => $etiqueta): ?>
            <option value="<?php echo $archivo; ?>" <?php echo ($archivo === $producto['imagen']) ? 'selected' : ''; ?>><?php echo $etiqueta; ?></option>
          <?php endforeach; ?>
        </select>
        <div class="text-center mt-3">
          <img id="previewImagen" src="../img/<?php echo limpiar($producto['imagen'] ?: 'default.svg'); ?>" alt="vista previa" style="width:90px;height:90px;border-radius:10px;">
        </div>
      </div>
      <button type="submit" class="btn btn-nexus w-100">Guardar cambios</button>
      <a href="productos.php" class="btn btn-outline-nexus w-100 mt-2">Cancelar</a>
    </form>
  </div>
</div>

<?php include '../php/footer.php'; ?>
