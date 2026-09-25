<?php
require_once 'php/funciones.php';
iniciar_sesion_segura();

$_SESSION = [];
session_unset();
session_destroy();

header("Location: index.php");
exit;
