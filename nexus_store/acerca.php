<?php
require_once 'php/funciones.php';
iniciar_sesion_segura();
require_once 'php/conexion.php';

$titulo_pagina = "Acerca de nosotros";
$ruta_base = '';

include 'php/header.php';
?>

<section class="nexus-hero text-center">
  <div class="container">
    <h1>Acerca de <span class="text-accent">NEXUS STORE</span></h1>
    <p class="lead mx-auto" style="max-width:650px;">Una tienda especializada en la más alta calidad para los gamers.</p>
  </div>
</section>

<div class="container my-5">

  <div class="row g-4 align-items-center mb-5">
    <div class="col-md-6">
      <h3 class="mb-3">Quiénes somos</h3>
      <p >
        Nexus Store nació como un proyecto diseñado para todos los gamers de El Salvador, contado con grandes distribuidores de productos de la más alta calidad para la mejor jugabilidad en todos los juegos.
      </p>
      <p>
        Elegimos el rubro gaming porque es algo que nos gusta como equipo: consolas, videojuegos
        y accesorios para armar un buen setup.
      </p>
    </div>
    <div class="col-md-6 text-center">
      <img src="img/logo.svg" alt="Nexus Store" style="width:260px;">
    </div>
  </div>

  <h3 class="mb-4 text-center">Lo que ofrecemos</h3>
  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="card-nexus p-4 text-center">
        <i class="fa-solid fa-gamepad fa-2x mb-3 text-accent"></i>
        <h5>Consolas</h5>
        <p class="small">Las principales consolas de última generación.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-nexus p-4 text-center">
        <i class="fa-solid fa-compact-disc fa-2x mb-3 text-accent"></i>
        <h5>Videojuegos</h5>
        <p class="small">Títulos para todas las plataformas y todos los gustos.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-nexus p-4 text-center">
        <i class="fa-solid fa-headset fa-2x mb-3 text-accent"></i>
        <h5>Accesorios</h5>
        <p class="small">Controles, audífonos, sillas y periféricos gamer.</p>
      </div>
    </div>
  </div>

  <div class="card-nexus p-4 p-md-5 text-center" style="max-width:700px; margin:0 auto;">
    <h4 class="mb-3">Contacto</h4>
    <p><i class="fa-solid fa-envelope text-accent"></i> contacto@nexusstore.com</p>
    <p><i class="fa-solid fa-location-dot text-accent"></i> Proyecto académico - Curso de Bases de Datos</p>
  </div>

</div>

<?php include 'php/footer.php'; ?>
