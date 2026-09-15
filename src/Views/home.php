<?php

include_once __DIR__ . "/layout/header.php";

use ProyectoTFGRodrigo\Config\Parameters;

$nombreUsuario = $_SESSION["nombre_usuario"] ?? "piloto";
$nombreUsuario = mb_convert_case($nombreUsuario, MB_CASE_TITLE, "UTF-8"); //Convierte la primera letra dle nombre en mayuscula, tambien lo corrige si hay tildes

$rolUsuario = $_SESSION["rol"] ?? "user";

$tratamiento = $rolUsuario === "admin" ? "ingeniero" : "piloto";

?>

<main id="contenido" class="seccion-principal">
  <!-- IZQUIERDA -->
  <section class="seccion-principal__izquierda" data-aos="fade" data-aos-duration="1000">
    <div class="texto-hero">

      <!-- INSIGNIA -->
      <div class="insignia" data-aos="fade-up" data-aos-delay="200">
        <span class="insignia__punto"></span>
        <span>Setup • Aerodinámica • Simulación</span>
      </div>

      <!-- TITULO -->
      <h1 id="titulo-f1" data-aos="fade-up" data-aos-delay="400">
        Hola <?= htmlspecialchars($tratamiento, ENT_QUOTES, "UTF-8") ?>
        <?= htmlspecialchars($nombreUsuario, ENT_QUOTES, "UTF-8") ?>
      </h1>

      <!-- TEXTO -->
      <p class="descripcion-pagina" data-aos="fade-up" data-aos-delay="600">
        <strong>Bienvenido a F1 Setup Simulator & Store.</strong>
        Aquí no solo observas la Fórmula 1… la construyes. Diseña el setup perfecto ajustando aerodinámica, altura y
        balance como un auténtico ingeniero de competición. Analiza cada decisión y exprime cada milésima en pista.
        <br><br>

        Entra en el <strong>modo duelo 1v1</strong> y demuestra tu nivel frente a otros jugadores, o explora la
        <strong>tienda</strong> con contenido exclusivo pensado para los verdaderos fans del motor. Personaliza tu
        experiencia y lleva tu monoplaza al siguiente nivel con cada mejora.
        <br><br>

        Vive una experiencia inmersiva donde cada elección marca la diferencia entre ganar o quedarte atrás en la
        parrilla. Domina la estrategia, perfecciona tu estilo de conducción y conviértete en una leyenda dentro del
        simulador.
      </p>

      <!-- BOTONES -->
      <div class="fila-botones">

        <div class="contenedor-boton">
          <a class="boton boton--principal" href="<?= Parameters::BASE_URL ?>setup">
            Configura tu setup
          </a>

          <div class="tarjeta-hover">
            Ajusta aerodinámica, altura y balance para optimizar el rendimiento
            del monoplaza y mejorar tu tiempo por vuelta.
          </div>
        </div>

        <div class="contenedor-boton">
          <a class="boton boton--secundario" href="<?= Parameters::BASE_URL ?>duelo">
            Ver modo duelo
          </a>

          <div class="tarjeta-hover">
            Enfréntate en un duelo 1v1 y demuestra que tu configuración
            es la más rápida en pista.
          </div>
        </div>

        <div class="contenedor-boton">
          <a class="boton boton--secundario" href="<?= Parameters::BASE_URL ?>tienda">
            Ver tienda
          </a>

          <div class="tarjeta-hover">
            Explora productos exclusivos y merchandising inspirado
            en el universo F1.
          </div>
        </div>

      </div>

      <!-- INDICADORES -->
      <dl class="indicadores">

        <div class="indicador" data-aos="fade" data-aos-delay="1200">
          <dt class="indicador__texto">Ángulo alerones</dt>
          <dd class="indicador__numero">0–20°</dd>
        </div>

        <div class="indicador" data-aos="fade" data-aos-delay="1300">
          <dt class="indicador__texto">Altura monoplaza</dt>
          <dd class="indicador__numero">5–10 cm</dd>
        </div>

        <div class="indicador" data-aos="fade" data-aos-delay="1400">
          <dt class="indicador__texto">Duelo</dt>
          <dd class="indicador__numero">1v1</dd>
        </div>

      </dl>

    </div>
  </section>

  <!-- DERECHA -->
  <section class="seccion-principal__derecha">

    <video class="video-hero" autoplay muted loop playsinline preload="metadata">
      <source src="<?= Parameters::BASE_URL ?>assets/video/videoF1.mp4" type="video/mp4">
    </video>

    <div class="capa-sombra"></div>
    <div class="capa-grano"></div>

    <!-- TARJETA TELEMETRÍA -->
    <!-- TARJETA TELEMETRÍA -->
    <aside class="tarjeta-flotante" data-aos="zoom-in" data-aos-delay="1500">
      <div class="tarjeta-flotante__titulo">
        SETUP TELEMETRY •<br>
        <span id="circuito">AUTODROMO DI MONZA</span>
      </div>

      <div class="tarjeta-flotante__fila">
        <span>Balance Aero</span>
        <strong id="balance-aero">40% Front</strong>
      </div>

      <div class="tarjeta-flotante__fila">
        <span>Downforce</span>
        <strong id="downforce">2.525 N</strong>
      </div>

      <div class="tarjeta-flotante__fila">
        <span>Vel. Punta</span>
        <strong id="vel-punta">345 km/h</strong>
      </div>

      <div class="tarjeta-flotante__fila">
        <span>Drag Coef.</span>
        <strong id="drag-coef">0.89 Cd</strong>
      </div>

      <div class="tarjeta-flotante__fila">
        <span>Lap Est.</span>
        <strong id="lap-est">1:22.438</strong>
      </div>
    </aside>

  </section>
</main>

<?php include_once __DIR__ . "/layout/footer.php"; ?>