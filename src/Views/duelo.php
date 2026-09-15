<?php

use ProyectoTFGRodrigo\Config\Parameters;

include_once __DIR__ . "/layout/header.php";

$haySetups = !empty($setups);
$claseEquipoTabla = function (string $nombreCoche): string {
  $nombre = strtolower($nombreCoche);

  if (strpos($nombre, "ferrari") !== false) return "team-ferrari";
  if (strpos($nombre, "red bull") !== false) return "team-red-bull";
  if (strpos($nombre, "mercedes") !== false) return "team-mercedes";
  if (strpos($nombre, "aston") !== false) return "team-aston-martin";

  return "team-default";
};

?>

<main id="contenido" class="home-main">
  <section class="sim-head" data-aos="fade-up" data-aos-duration="1000">
    <span class="sim-head__badge" data-aos="zoom-in" data-aos-delay="100">
      SIMULADOR DE CARRERA
    </span>

    <h1 class="sim-head__title" data-aos="fade-up" data-aos-delay="180">
      Analiza el rendimiento de los setups en pista
    </h1>

    <p class="sim-head__desc" data-aos="fade-up" data-aos-delay="260">
      Selecciona los monoplazas y el circuito para comparar tiempos, ritmo
      de vuelta y rendimiento en tiempo real.
    </p>

    <form class="sim-head__select-wrap" method="GET" action="<?= Parameters::BASE_URL ?>duelo" data-aos="fade-up"
      data-aos-delay="340">
      <label for="circuitoSelect" class="sim-head__label">Circuito</label>

      <select id="circuitoSelect" name="id_circuito" class="sim-head__select" onchange="this.form.submit()">
        <?php foreach ($circuitos as $circuito): ?>
          <option value="<?= (int) $circuito["id_circuito"] ?>" <?= (int) $circuito["id_circuito"] === (int) ($circuitoSeleccionado["id_circuito"] ?? 0) ? "selected" : "" ?>>
           <?= htmlspecialchars($circuito["nombre_circuito"], ENT_QUOTES, "UTF-8") ?> · <?= htmlspecialchars($circuito["pais"], ENT_QUOTES, "UTF-8") ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>
  </section>

  <section class="sim-race">
    <aside class="sim-side sim-side--left" data-aos="fade-right" data-aos-duration="1000">
      <span class="sim-badge">SELECCIÓN</span>

      <h2 class="sim-title">Elige los coches</h2>

      <p class="sim-text">
        Selecciona dos monoplazas para simular la carrera en
        <?= htmlspecialchars($circuitoSeleccionado["nombre_circuito"] ?? "el circuito", ENT_QUOTES, "UTF-8") ?> · <?= htmlspecialchars($circuitoSeleccionado["pais"] ?? "País no disponible", ENT_QUOTES, "UTF-8") ?>.
      </p>

      <div class="sim-field" data-aos="fade-up" data-aos-delay="100">
        <label for="cocheA">Coche 1</label>

        <select id="cocheA" class="sim-select" <?= !$haySetups ? "disabled" : "" ?>>
          <?php if ($haySetups): ?>
            <?php foreach ($setups as $setup): ?>
              <?php
              $aeroFormula = 0.7 + (((int) $setup["angulo_aleron"] + 5) / 10) * 1.1;
              $alturaFormula = 0.8 + (((float) $setup["altura_suspension"]) / 20) * 0.6;
              ?>
              <option value="<?= (int) $setup["id_setup"] ?>" data-id-coche="<?= (int) $setup["id_coche"] ?>"
                data-nombre="<?= htmlspecialchars($setup["nombre_coche"], ENT_QUOTES, "UTF-8") ?>"
                data-setup="<?= htmlspecialchars($setup["nombre"], ENT_QUOTES, "UTF-8") ?>"
                data-aero="<?= number_format($aeroFormula, 2, ".", "") ?>"
                data-altura="<?= number_format($alturaFormula, 2, ".", "") ?>">
                <?= htmlspecialchars($setup["nombre_coche"] . " - " . $setup["nombre"], ENT_QUOTES, "UTF-8") ?>
              </option>
            <?php endforeach; ?>
          <?php else: ?>
            <option>No hay setups disponibles</option>
          <?php endif; ?>
        </select>
      </div>

      <div class="sim-field" data-aos="fade-up" data-aos-delay="180">
        <label for="cocheB">Coche 2</label>

        <select id="cocheB" class="sim-select" <?= !$haySetups ? "disabled" : "" ?>>
          <?php if ($haySetups): ?>
            <?php foreach ($setups as $setup): ?>
              <?php
              $aeroFormula = 0.7 + (((int) $setup["angulo_aleron"] + 5) / 10) * 1.1;
              $alturaFormula = 0.8 + (((float) $setup["altura_suspension"]) / 20) * 0.6;
              ?>
              <option value="<?= (int) $setup["id_setup"] ?>" data-id-coche="<?= (int) $setup["id_coche"] ?>"
                data-nombre="<?= htmlspecialchars($setup["nombre_coche"], ENT_QUOTES, "UTF-8") ?>"
                data-setup="<?= htmlspecialchars($setup["nombre"], ENT_QUOTES, "UTF-8") ?>"
                data-aero="<?= number_format($aeroFormula, 2, ".", "") ?>"
                data-altura="<?= number_format($alturaFormula, 2, ".", "") ?>">
                <?= htmlspecialchars($setup["nombre_coche"] . " - " . $setup["nombre"], ENT_QUOTES, "UTF-8") ?>
              </option>
            <?php endforeach; ?>
          <?php else: ?>
            <option>No hay setups disponibles</option>
          <?php endif; ?>
        </select>
      </div>

      <?php if (!$haySetups): ?>
        <p class="sim-empty-setups">No hay setups creados. Crea uno antes de iniciar un duelo.</p>
      <?php endif; ?>
    </aside>

    <div class="circuito-panel" data-aos="zoom-in" data-aos-duration="1100" data-aos-delay="120">
      <span class="sim-badge sim-badge--dark" data-aos="fade-down">
        SIMULACIÓN ACTIVA
      </span>

      <h1 data-aos="fade-up" data-aos-delay="120">
        <?= htmlspecialchars($circuitoSeleccionado["nombre_circuito"] ?? "Circuito no disponible", ENT_QUOTES, "UTF-8") ?>
      </h1>

      <p data-aos="fade-up" data-aos-delay="200">
        Simulación en tiempo real · Comparativa de ritmo y vuelta
      </p>

      <div class="race-start-panel" aria-live="polite">
        <div class="race-lights" id="race-lights" aria-label="Semáforo de salida">
          <?php for ($fila = 1; $fila <= 3; $fila++): ?>
            <?php for ($col = 1; $col <= 5; $col++): ?>
              <span class="race-light" data-step="<?= $col ?>"></span>
            <?php endfor; ?>
          <?php endfor; ?>

          <span class="race-go-light"></span>
        </div>

        <strong id="race-status">Preparado</strong>
      </div>

      <div class="circuito-svg-wrap" data-aos="fade-up" data-aos-delay="260">
        <?php if ($circuitoSeleccionado): ?>
          <svg viewBox="<?= htmlspecialchars($circuitoSeleccionado["view_box_circuito"], ENT_QUOTES, "UTF-8") ?>"
            class="circuito-svg" preserveAspectRatio="xMidYMid meet"
            data-punto-inicio="<?= htmlspecialchars($circuitoSeleccionado["punto_inicio"], ENT_QUOTES, "UTF-8") ?>"
            data-tiempo-base="<?= htmlspecialchars($circuitoSeleccionado["tiempo_base"], ENT_QUOTES, "UTF-8") ?>"
            data-factor-aero="<?= htmlspecialchars($circuitoSeleccionado["factor_aero"], ENT_QUOTES, "UTF-8") ?>"
            data-factor-altura="<?= htmlspecialchars($circuitoSeleccionado["factor_altura"], ENT_QUOTES, "UTF-8") ?>"
            data-nombre-circuito="<?= htmlspecialchars($circuitoSeleccionado["nombre_circuito"], ENT_QUOTES, "UTF-8") ?>"
            data-id-circuito="<?= (int) $circuitoSeleccionado["id_circuito"] ?>"
            data-guardar-carrera-url="<?= Parameters::BASE_URL ?>guardarCarrera">
            <path id="path4259" fill="none" stroke="rgba(0,0,0,0.18)" stroke-width="3"
              d="<?= htmlspecialchars($circuitoSeleccionado["svg_circuito"], ENT_QUOTES, "UTF-8") ?>">
            </path>

            <circle id="coche1" class="f1-car" r="6" fill="#e10600"></circle>
            <circle id="coche2" class="f1-car" r="6" fill="#00d2be"></circle>
          </svg>
        <?php else: ?>
          <p>No hay circuitos disponibles.</p>
        <?php endif; ?>
      </div>

      <div class="controls" data-aos="fade-up" data-aos-delay="340">
        <button id="start" type="button" <?= !$haySetups ? "disabled" : "" ?>>Start</button>
        <button id="stop" type="button">Stop</button>
        <button id="reset" type="button">Reset</button>
      </div>

      <div class="race-timers race-timers--mobile is-hidden" id="race-timers-mobile">
        <div class="race-timer mb-2">
          <div class="race-timer__top">
            <span class="timer-label-coche1">Coche 1</span>
            <small class="reaction-coche1">Tiempo reaccion: 0.000s</small>
          </div>
          <strong class="timer-coche1">0:00.000</strong>
        </div>

        <div class="race-timer mb-3">
          <div class="race-timer__top">
            <span class="timer-label-coche2">Coche 2</span>
            <small class="reaction-coche2">Tiempo reaccion: 0.000s</small>
          </div>
          <strong class="timer-coche2">0:00.000</strong>
        </div>
      </div>
    </div>

    <aside class="sim-side sim-side--right" data-aos="fade-left" data-aos-duration="1000">
      <span class="sim-badge">TIEMPOS</span>

      <h2 class="sim-title">Tabla de tiempos</h2>

      <p class="sim-text">
        Tabla de tiempos de los ganadores, con tiempos de vuelta y totales para cada coche.
      </p>

      <a class="excel-export-btn" href="<?= Parameters::BASE_URL ?>exportarTiemposExcel">
        <svg class="excel-export-btn__icon" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M4 3h10l6 6v12H4V3Zm10 1.8V9h4.2L14 4.8ZM7 12l2.1 3L7 18h2.1l1.1-1.8 1.1 1.8h2.1l-2.1-3 2.1-3h-2.1l-1.1 1.8L9.1 12H7Z" />
        </svg>
        Generar Excel de tiempos
      </a>

      <div class="race-timers is-hidden" id="race-timers">

        <div class="race-timer mb-2">
          <div class="race-timer__top">
            <span id="timer-label-1" class="timer-label-coche1">Coche 1</span>
            <small id="reaction-coche1">Tiempo reacción: 0.000s</small>
          </div>
          <strong id="timer-coche1" class="timer-coche1">0:00.000</strong>
        </div>
  
        <div class="race-timer mb-3">
          <div class="race-timer__top">
            <span id="timer-label-2" class="timer-label-coche2">Coche 2</span>
            <small id="reaction-coche2">Tiempo reacción: 0.000s</small>
          </div>
          <strong id="timer-coche2" class="timer-coche2">0:00.000</strong>
        </div>
      </div>


      <div class="tabla-tiempo-wrap" data-aos="fade-up" data-aos-delay="140">
        <table class="tabla-tiempo">
          <thead>
            <tr>
              <th>Circuito</th>
              <th>Ganador</th>
              <th>Tiempo</th>
              <th>Fecha</th>
            </tr>
          </thead>

          <tbody id="tabla-resultados-carrera">
            <?php if (!empty($carreras)): ?>
              <?php foreach ($carreras as $carrera): ?>
                <tr>
                  <td><?= htmlspecialchars($carrera["nombre_circuito"], ENT_QUOTES, "UTF-8") ?></td>
                  <?php $nombreCoche = $carrera["nombre_coche"] ?? "Coche eliminado"; ?>
                  <td>
                    <span class="team-chip <?= $claseEquipoTabla($nombreCoche) ?>">
                      <?= htmlspecialchars($nombreCoche, ENT_QUOTES, "UTF-8") ?>
                    </span>
                  </td>
                  <?php
                  $tiempoGanador = (float) $carrera["tiempo_ganador"];
                  $minutos = floor($tiempoGanador / 60);
                  $segundos = $tiempoGanador - ($minutos * 60);
                  ?>
                  <td><?= $minutos ?>:<?= str_pad(number_format($segundos, 3, ".", ""), 6, "0", STR_PAD_LEFT) ?></td>
                  <td><?= htmlspecialchars($carrera["fecha_carrera"], ENT_QUOTES, "UTF-8") ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">Los tiempos apareceran al terminar la carrera.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </aside>
  </section>
</main>

<?php include_once __DIR__ . "/layout/footer.php"; ?>
