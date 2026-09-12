<?php
require_once '../php/funciones.php';
iniciar_sesion_segura();
requerir_admin();
require_once '../php/conexion.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: productos.php?eliminado=1");
exit;
