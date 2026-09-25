<?php
// conexion a la base de datos

$servidor = "localhost";
$usuario_db = "root";
$clave_db = "";
$base_datos = "nexus_store";

$conexion = new mysqli($servidor, $usuario_db, $clave_db, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexion a la base de datos: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
