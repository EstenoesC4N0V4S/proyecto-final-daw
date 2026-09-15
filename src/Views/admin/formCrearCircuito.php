<?php

use ProyectoTFGRodrigo\Config\Parameters;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../layout/header.php";
?>

<main id="contenido" class="crear-circuito-page">
  <section class="crear-circuito-hero" data-aos="fade-up" data-aos-duration="1200">
    <span class="tienda__tag" data-aos="zoom-in" data-aos-delay="100">
      CREAR CIRCUITO
    </span>

    <h1>Crear nuevo circuito</h1>

    <p class="crear-circuito-subtexto">
      Inserta un nuevo circuito indicando su nombre, el viewBox, el path del trazado
      y los factores técnicos para aerodinámica y altura.
    </p>
  </section>

  <section class="crear-circuito-panel" data-aos="zoom-in" data-aos-duration="1400">
    <form
      id="form-crear-circuito"
      class="form-crear-circuito"
      action="<?= Parameters::BASE_URL ?>guardarCircuito"
      method="post"
    >
      <div class="form-grid">

        <!-- NOMBRE -->
        <div class="form-campo">
          <label for="nombre-circuito">Nombre del circuito</label>
          <input
            type="text"
            id="nombre-circuito"
            name="nombre_circuito"
            placeholder="Ej. Silverstone"
            required
          />
        </div>

        <!-- FACTOR AERO -->
        <div class="form-campo">
          <label for="factor-aero">Factor aero</label>
          <input
            type="number"
            id="factor-aero"
            name="factor_aero"
            placeholder="Ej. 1.10"
            step="0.01"
            min="0"
            required
          />
        </div>

        <!-- FACTOR ALTURA -->
        <div class="form-campo">
          <label for="factor-altura">Factor altura</label>
          <input
            type="number"
            id="factor-altura"
            name="factor_altura"
            placeholder="Ej. 1.20"
            step="0.01"
            min="0"
            required
          />
        </div>

        <!-- VIEWBOX -->
        <div class="form-campo form-campo--full">
          <label for="viewbox">ViewBox del circuito</label>
          <input
            type="text"
            id="viewbox"
            name="view_box"
            placeholder="Ej. 0 0 1000 500"
            required
          />
        </div>

        <!-- PATH D -->
        <div class="form-campo form-campo--full">
          <label for="path-d">Path (d) del circuito</label>
          <textarea
            id="path-d"
            name="path_d"
            rows="6"
            placeholder="Ej. M 100 200 C 150 100 300 100 400 200. Puedes buscar SVGs de circuitos en https://freesvg.org/ (ej: '🔍racecircuit')."
            required
          ></textarea>
        </div>

      </div>

      <div class="form-acciones">
        <button type="reset" class="btn-secundario">Limpiar</button>
        <button type="submit" class="btn-principal">
          Guardar circuito
        </button>
      </div>
    </form>
  </section>
</main>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>