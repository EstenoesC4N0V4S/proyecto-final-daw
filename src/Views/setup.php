<?php

include_once __DIR__ . "/layout/header.php";

use ProyectoTFGRodrigo\Config\Parameters;

$okSetup = $_SESSION["ok_setup"] ?? null;
$errorSetup = $_SESSION["error_setup"] ?? null;
unset($_SESSION["ok_setup"], $_SESSION["error_setup"]);

?>

<main class="setup-page">
  <section class="setup-wrap">
    <div class="setup-heading" data-aos="fade-up">
      <span class="setup-eyebrow">Simulador de setup</span>

      <h1 class="titulo-setup">Configurar setup del monoplaza</h1>

      <p class="subtitulo-setup">
        Ajusta la altura y la carga aerodinámica para encontrar el equilibrio
        perfecto entre estabilidad, riesgo y velocidad.
      </p>
    </div>

    <form id="form-setup" class="setup-panel-principal" method="POST" action="<?= Parameters::BASE_URL ?>crearSetup">
      <?php if ($okSetup): ?>
        <div class="setup-alert setup-alert--ok">
          <?= htmlspecialchars($okSetup, ENT_QUOTES, "UTF-8") ?>
        </div>
      <?php endif; ?>

      <?php if ($errorSetup): ?>
        <div class="setup-alert setup-alert--error">
          <?= htmlspecialchars($errorSetup, ENT_QUOTES, "UTF-8") ?>
        </div>
      <?php endif; ?>
      <div class="setup-selector-coche" data-aos="fade-up" data-aos-delay="100">
        <label for="select-coche" class="setup-selector-coche__label">
          Selecciona tu coche
        </label>

        <div class="setup-selector-coche__control">
          <select id="select-coche" name="id_coche" class="setup-select" required>
            <?php foreach ($coches as $coche): ?>
              <option value="<?= (int) $coche["id_coche"] ?>">
                <?= htmlspecialchars($coche["nombre"], ENT_QUOTES, "UTF-8") ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="setup-zona">
        <!-- IZQUIERDA -->
        <div class="setup-lateral" data-aos="fade-right">
          <div class="setup-bloque">
            <div class="setup-bloque__titulo">
              <span>Altura del monoplaza</span>
            </div>

            <div class="setup-bloque__subtexto">
              Modifica la distancia respecto al suelo para variar estabilidad
              y comportamiento general.
            </div>

            <div class="setup-rango">
              <span>0 cm</span>
              <span>20 cm</span>
            </div>

            <input type="range" min="0" max="20" value="10" step="0.5" name="altura_suspension" class="setup-slider"
              id="slider-altura" />

            <p class="setup-numero">
              Altura del monoplaza:
              <span id="valor-altura">10</span> cm
            </p>

            <p class="setup-numero">
              Valor para formula:
              <span id="valor-altura-formula">1.10</span>
            </p>
          </div>
        </div>

        <!-- CENTRO -->
        <div class="setup-centro" data-aos="zoom-in" data-aos-duration="1200">
          <div class="setup-coche-placeholder">
            <div class="setup-coche-badge">Setup activo</div>

            <img src="<?= Parameters::BASE_URL ?>assets/img/img_coche/Coche sin ruedas.png" class="coche-base"
              id="coche-base" alt="Monoplaza" />

            <img src="<?= Parameters::BASE_URL ?>assets/img/img_coche/ruedas.png" class="coche-ruedas"
              alt="Ruedas del monoplaza" />

            <img src="<?= Parameters::BASE_URL ?>assets/img/img_coche/aleron_0.png" class="aleron" id="aleron"
              alt="Alerón del monoplaza" />
          </div>
        </div>

        <!-- DERECHA -->
        <div class="setup-lateral" data-aos="fade-left">
          <div class="setup-bloque">
            <div class="setup-bloque__titulo">
              <span>Ángulo del alerón</span>
            </div>

            <div class="setup-bloque__subtexto">
              Ajusta la carga aerodinámica para mejorar el paso por curva o
              ganar velocidad punta.
            </div>

            <div class="setup-rango">
              <span>-5°</span>
              <span>5°</span>
            </div>

            <input type="range" min="-5" max="5" value="0" step="1" name="angulo_aleron" class="setup-slider"
              id="slider-angulo" />

            <p class="setup-numero">
              Ángulo del alerón:
              <span id="valor-angulo">0</span>°
            </p>
            <p class="setup-numero">
              Valor para formula:
              <span id="valor-aero-formula">1.25</span>
            </p>
          </div>
        </div>
      </div>

      <!-- MÉTRICAS -->
      <div class="metricas" data-aos="fade-up" data-aos-delay="150">
        <div class="metrica-card metrica-card--estabilidad">
          <span class="metrica-label">Estabilidad</span>
          <p id="estabilidad">Media</p>
        </div>

        <div class="metrica-card metrica-card--riesgo">
          <span class="metrica-label">Riesgo</span>
          <p id="riesgo">Medio</p>
        </div>

        <div class="metrica-card metrica-card--curvas">
          <span class="metrica-label">Velocidad curvas</span>
          <p id="velocidad-curvas">Media</p>
        </div>

        <div class="metrica-card metrica-card--rectas">
          <span class="metrica-label">Velocidad rectas</span>
          <p id="velocidad-recta">Media</p>
        </div>
      </div>

      <!-- FORM -->
      <div class="setup-formulario" data-aos="fade-up" data-aos-delay="200">
        <input type="text" name="nombre" placeholder="Nombre de tu setup" class="setup-input" maxlength="100"
          required />

        <button class="setup-boton" type="submit">CREAR SETUP</button>
      </div>
    </form>

    <?php if (!empty($setups)): ?>
      <div class="setup-panel-principal mt-4 setups-creados-panel" data-aos="fade-up" data-aos-delay="300">
        <h2 class="setups-creados-title">Setups creados</h2>
        <div class="setups-creados-linea"></div>

        <div class="setups-creados-tabla-wrap">
          <table class="setups-creados-tabla">
            <thead>
              <tr>
                <th>Setup</th>
                <th>Coche</th>
                <th>Alerón</th>
                <th>Altura</th>
                <th>Aero fórmula</th>
                <th>Altura fórmula</th>

                <th class="setup-delete-heading" aria-label="Acciones"></th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($setups as $setup): ?>
                <?php
                $aeroFormula = 0.7 + (((int) $setup["angulo_aleron"] + 5) / 10) * 1.1;
                $alturaFormula = 0.8 + (((float) $setup["altura_suspension"]) / 20) * 0.6;
                ?>

                <tr>
                  <td data-label="Setup"><?= htmlspecialchars($setup["nombre"], ENT_QUOTES, "UTF-8") ?></td>
                  <td data-label="Coche"><?= htmlspecialchars($setup["nombre_coche"], ENT_QUOTES, "UTF-8") ?></td>
                  <td data-label="Alerón"><?= (int) $setup["angulo_aleron"] ?>°</td>
                  <td data-label="Altura"><?= number_format((float) $setup["altura_suspension"], 1, ",", ".") ?> cm</td>
                  <td data-label="Aero fórmula"><?= number_format($aeroFormula, 2, ".", "") ?></td>
                  <td data-label="Altura fórmula"><?= number_format($alturaFormula, 2, ".", "") ?></td>
                  <td data-label="" class="setup-delete-cell">
                    <form class="setup-delete-form" action="<?= Parameters::BASE_URL ?>eliminarSetup" method="POST"
                      onsubmit="return confirm('Eliminar este setup?');">
                      <input type="hidden" name="id_setup" value="<?= (int) $setup["id_setup"] ?>">
                      <button class="setup-delete-btn" type="submit" aria-label="Eliminar setup">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                          <path d="M9 3h6l1 2h4v2H4V5h4l1-2Zm-2 6h10l-.7 11H7.7L7 9Zm3 2v7h2v-7h-2Zm4 0v7h2v-7h-2Z" />
                        </svg>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </section>
</main>

<?php include_once __DIR__ . "/layout/footer.php"; ?>
