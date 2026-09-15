<?php
use ProyectoTFGRodrigo\Config\Parameters;

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$cantidadCarritoHeader = 0;

if (!empty($_SESSION["id_usuario"])) {
  $carritoHeaderModel = new \ProyectoTFGRodrigo\Models\CarritoModel();
  $cantidadCarritoHeader = $carritoHeaderModel->contarProductos((int) $_SESSION["id_usuario"]);
}
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="description" content="Simula y configura el setup de un monoplaza de F1" />

  <title>F1 Setup Simulator</title>
  <link rel="icon" type="image/png" href="<?= Parameters::BASE_URL ?>assets/img/logo_f1.png">
  
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">
  
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />

  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleHome.css" />
  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleHeader.css?v=3" />
  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleFooter.css?v=4" />
  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleSetup.css" />
  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleTienda.css" />
  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleDuelo.css?v=2" />
  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleCarrito.css?v=4" />
  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleCrearCircuito.css" />
  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleError.css" />
  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleDatos.css" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />

  <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
</head>

<body>

  <header class="barra-superior">
    <div class="barra-superior__interior">

      <div class="marca marca-menu">
        <a class="marca-logo-escritorio" href="<?= Parameters::BASE_URL ?>home">
          <img src="<?= Parameters::BASE_URL ?>assets/img/logo_f1.png" alt="Logo F1" />
        </a>

        <button class="logo-burger" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuF1Mobile">
          <img src="<?= Parameters::BASE_URL ?>assets/img/logo_f1.png" alt="Menú" />
        </button>

        <a class="marca__nombre" href="<?= Parameters::BASE_URL ?>home">
          F1 Setup Simulator & Store
        </a>
      </div>

      <nav class="navegacion">
        <a href="<?= Parameters::BASE_URL ?>setup">Configura tu setup</a>
        <a href="<?= Parameters::BASE_URL ?>duelo">Duelo 1v1</a>
        <?php if (!empty($_SESSION["rol"]) && $_SESSION["rol"] === "admin"): ?>
          <a href="<?= Parameters::BASE_URL ?>crearCircuito" class="navegacion__enlace">
            Crear circuito
          </a>
        <?php endif; ?>
        <a href="<?= Parameters::BASE_URL ?>tienda">Tienda</a>
      </nav>

      <div class="acciones-header">

        <button id="modo-btn" class="boton-tema">
          <i id="icono-modo" class="bi bi-moon-fill"></i>
        </button>

        <!-- PERFIL -->
        <?php if (!empty($_SESSION["id_usuario"])): ?>
          <a href="<?= Parameters::BASE_URL ?>datos" class="login-icono">
            <i class="bi bi-person"></i>
          </a>
        <?php endif; ?>

        <!-- LOGIN / LOGOUT -->
        <?php if (!empty($_SESSION["id_usuario"])): ?>

          <a href="<?= Parameters::BASE_URL ?>logout" class="login-icono">
            <i class="bi bi-box-arrow-right"></i>
          </a>

        <?php else: ?>

          <a href="<?= Parameters::BASE_URL ?>login" class="login-icono">
            <i class="bi bi-box-arrow-in-right"></i>
          </a>

        <?php endif; ?>

        <!-- CARRITO -->
        <a href="<?= Parameters::BASE_URL ?>carrito" class="login-icono cart-header-link">
          <i class="bi bi-cart3 "></i>
          <?php if ($cantidadCarritoHeader > 0): ?>
            <span class="cart-header-count" id="cart-header-count"><?= $cantidadCarritoHeader ?></span>
          <?php endif; ?>
        </a>

      </div>

    </div>
  </header>

  <!-- MENU MOVIL -->
  <div class="offcanvas offcanvas-start menu-f1-offcanvas" tabindex="-1" id="menuF1Mobile">

    <div class="offcanvas-header">
      <h5 class="offcanvas-title">F1 Setup Simulator</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">

      <nav class="menu-f1-mobile-nav">
        <a href="<?= Parameters::BASE_URL ?>home">Inicio</a>
        <a href="<?= Parameters::BASE_URL ?>setup">Setup</a>
        <a href="<?= Parameters::BASE_URL ?>duelo">Duelo</a>
        <?php if (!empty($_SESSION["rol"]) && $_SESSION["rol"] === "admin"): ?>
          <a href="<?= Parameters::BASE_URL ?>crearCircuito">Crear circuito</a>
        <?php endif; ?>
        <a href="<?= Parameters::BASE_URL ?>tienda">Tienda</a>

        <?php if (!empty($_SESSION["id_usuario"])): ?>

          <a href="<?= Parameters::BASE_URL ?>datos">Perfil</a>
          <a href="<?= Parameters::BASE_URL ?>logout">Cerrar sesión</a>

        <?php else: ?>

          <a href="<?= Parameters::BASE_URL ?>login">Login</a>

        <?php endif; ?>

        <a href="<?= Parameters::BASE_URL ?>carrito" class="menu-cart-link">
          Carrito
          <?php if ($cantidadCarritoHeader > 0): ?>
            <span class="cart-header-count cart-header-count--mobile"><?= $cantidadCarritoHeader ?></span>
          <?php endif; ?>
        </a>
      </nav>

    </div>
  </div>
