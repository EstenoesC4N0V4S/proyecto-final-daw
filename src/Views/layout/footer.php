<?php

use ProyectoTFGRodrigo\Config\Parameters;

?>

<footer id="contacto" class="footer-pro">
  <div class="footer-pro__topline"></div>
  <div class="footer-pro__glow"></div>

  <div class="footer-pro__inner">
    <div class="footer-pro__grid">

      <!-- BRAND -->
      <div class="footer-pro__brand" data-aos="zoom-in">
        <a
          class="footer-pro__logo"
          href="<?= Parameters::BASE_URL ?>home"
        >
          <img src="<?= Parameters::BASE_URL ?>assets/img/logo_f1.png" alt="Logo F1" />
          <span>F1 Setup Simulator</span>
        </a>

        <p class="footer-pro__desc">
          Diseña el setup perfecto, analiza telemetría y compite en duelos
          1v1. Experiencia de simulación con enfoque técnico y visual premium.
        </p>
      </div>

      <!-- SECCIONES -->
      <div class="footer-pro__col" data-aos="fade-up" data-aos-delay="200">
        <h3 class="footer-pro__title">Secciones</h3>

        <a class="footer-pro__link" href="<?= Parameters::BASE_URL ?>home">Inicio</a>
        <a class="footer-pro__link" href="<?= Parameters::BASE_URL ?>setup">Configura tu setup</a>
        <a class="footer-pro__link" href="<?= Parameters::BASE_URL ?>duelo">Duelo 1v1</a>
        <a class="footer-pro__link" href="<?= Parameters::BASE_URL ?>tienda">Tienda</a>
        <a class="footer-pro__link" href="<?= Parameters::BASE_URL ?>crearCircuito">Crear circuito</a>
      </div>

      <!-- PROYECTO -->
      <div class="footer-pro__col" data-aos="fade-up" data-aos-delay="400">
        <h3 class="footer-pro__title">Proyecto</h3>

        <a class="footer-pro__link" target="_blank" href="https://goo.su/fqXns">Tecnología</a>
        <a class="footer-pro__link" target="_blank" href="https://www.f1telemetry.com/es/live-timing">Telemetría</a>
        <a class="footer-pro__link" target="_blank" href="https://tickets.formula1.com/es/t-111-faq">FAQ</a>
        <a class="footer-pro__link" target="_blank" href="https://goo.su/z1xQ">Soporte</a>
      </div>

      <!-- CTA -->
      <div class="footer-pro__cta" data-aos="fade-up" data-aos-delay="600">
        <h3 class="footer-pro__title">Recibe novedades</h3>

        <p class="footer-pro__small">
          Actualizaciones del simulador, nuevos circuitos y drops de la tienda.
        </p>

        <form class="footer-pro__form" action="#" method="post">
          <label class="sr-only" for="footer-email">Email</label>

          <input
            id="footer-email"
            name="email"
            type="email"
            placeholder="tu@email.com"
            required
          />

          <button id="footer-subscribe-btn" type="submit">Suscribirme</button>
        </form>
      </div>

    </div>

    <!-- BOTTOM -->
    <div class="footer-pro__bottom" data-aos="fade-up">
      <span>© <span id="year"></span> F1 Setup Simulator</span>

      <div class="footer-pro__legal">
        <a href="#" class="footer-pro__legal-link">Privacidad</a>
        <span class="footer-pro__dot">•</span>
        <a href="#" class="footer-pro__legal-link">Términos</a>
        <span class="footer-pro__dot">•</span>
        <a href="#contenido" class="footer-pro__legal-link">Volver arriba</a>
      </div>
    </div>
  </div>
</footer>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/MotionPathPlugin.min.js"></script>

<script src="<?= Parameters::BASE_URL ?>assets/js/script.js"></script>
<script src="<?= Parameters::BASE_URL ?>assets/js/scriptAOS.js"></script>
<script src="<?= Parameters::BASE_URL ?>assets/js/scriptGSAP.js?v=2"></script>
<script src="<?= Parameters::BASE_URL ?>assets/js/scriptTienda.js"></script>
<script src="<?= Parameters::BASE_URL ?>assets/js/scriptCarrito.js?v=5"></script>
<script src="<?= Parameters::BASE_URL ?>assets/js/scriptValidarCliente.js"></script>
<script src="<?= Parameters::BASE_URL ?>assets/js/scriptHome.js"></script>


<script>
  AOS.init({
    duration: 900,
    easing: "ease-out-cubic",
    once: true,
  });

  const year = document.getElementById("year");
  if (year) year.textContent = new Date().getFullYear();
</script>

</body>
</html>
