<?php
include_once __DIR__ . "/../layout/header.php";
use ProyectoTFGRodrigo\Config\Parameters;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

  <main class="error-page">

    <div class="error-container">

      <!-- ICONO -->
      <div class="error-icon">
        <i class="bi bi-shield-lock"></i>
      </div>

      <!-- TITULO -->
      <h1 class="error-code">403</h1>

      <h2 class="error-title">
        Acceso restringido
      </h2>

      <!-- MENSAJE -->
      <p class="error-text">
        No tienes permisos para acceder a esta sección del sistema.
        <br>
        Si crees que esto es un error, contacta con el administrador.
      </p>

      <!-- BOTONES -->
      <div class="error-actions">

        <a href="<?= Parameters::BASE_URL ?>home" class="btn-error btn-error-primary">
          <i class="bi bi-house"></i>
          Volver al inicio
        </a>

        <a href="<?= Parameters::BASE_URL ?>logout" class="btn-error btn-error-secondary">
          <i class="bi bi-box-arrow-right"></i>
          Cerrar sesión
        </a>

      </div>

    </div>

  </main>


<?php include_once __DIR__ . "/../layout/footer.php"; ?>