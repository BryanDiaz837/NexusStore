<?php
// correr esto una sola vez para crear el usuario admin, despues borrar el archivo

require_once 'php/conexion.php';

$correo_admin = 'admin@nexusstore.com';
$clave_admin = 'admin123';

$stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo_admin);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "El admin ya existe, no se creo nada. Ya puedes borrar este archivo.";
} else {
    $hash = password_hash($clave_admin, PASSWORD_DEFAULT);
    $nombre = 'Administrador Nexus';
    $insert = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasena, tipo_usuario) VALUES (?, ?, ?, 'admin')");
    $insert->bind_param("sss", $nombre, $correo_admin, $hash);
    if ($insert->execute()) {
        echo "Admin creado.<br>";
        echo "Correo: $correo_admin<br>";
        echo "Clave: $clave_admin<br><br>";
        echo "Borra este archivo ahora.";
    } else {
        echo "Error al crear el admin.";
    }
}
