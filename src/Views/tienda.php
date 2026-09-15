<?php

include_once __DIR__ . "/layout/header.php";

use ProyectoTFGRodrigo\Config\Parameters;

?>

<?php if (isset($_SESSION["mensaje_carrito"])): ?>
  <div class="toast-carrito" id="toast-carrito">
    <?= $_SESSION["mensaje_carrito"] ?>
  </div>

  <?php unset($_SESSION["mensaje_carrito"]); ?>
<?php endif; ?>

<main id="contenido">
  <section class="tienda-lista" id="tienda">

    <div class="tienda__header">
      <span class="tienda__tag">F1 STORE</span>

      <h1 class="tienda__titulo">
        Maquetas & Merchandising
      </h1>

      <p class="tienda__subtitulo">
        Modelos 3D interactivos de Fórmula 1.
      </p>
    </div>

    <div class="tienda-lista__contenedor">

      <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $index => $producto): ?>

          <?php
          $delayFila = 100 + ($index * 60);
          $delayModelo = 120 + ($index * 60);
          $delayInfo = 180 + ($index * 60);

          $nombre = $producto["nombre"];
          $descripcion = $producto["descripcion"];
          $modelo3d = $producto["modelo_3d"];
          $precio = $producto["precio"];

          $escalaModelo = $producto["escala_modelo"];
          $cameraOrbit = $producto["camera_orbit"];
          $fieldOfView = $producto["field_of_view"];
          ?>

          <article class="producto-fila" data-aos="fade-up" data-aos-delay="<?= $delayFila ?>">

            <div class="producto-fila__media producto-fila__media--modelo"
              data-aos="zoom-in"
              data-aos-delay="<?= $delayModelo ?>">

              <model-viewer
                src="<?= Parameters::BASE_URL ?>assets/models3D/<?= $modelo3d ?>"
                class="modelo-3d"
                alt="<?= $nombre ?> 3D"
                scale="<?= $escalaModelo ?>"
                camera-orbit="<?= $cameraOrbit ?>"
                field-of-view="<?= $fieldOfView ?>"
                camera-controls
                auto-rotate
                loading="lazy"
                auto-rotate-delay="800"
                rotation-per-second="9deg"
                interaction-prompt="none"
                shadow-intensity="0"
                exposure="1.15"
                environment-image="neutral"
                style="width: 100%; height: 100%; background: transparent">
              </model-viewer>

            </div>

            <div class="producto-fila__info" data-aos="fade-left" data-aos-delay="<?= $delayInfo ?>">

              <div class="producto-fila__top">
                <span class="producto-fila__categoria">Merchandising</span>
                <h3><?= $nombre ?></h3>
              </div>

              <p><?= $descripcion ?></p>

              <div class="producto-fila__bottom">

                <div class="producto-fila__precio-box">
                  <span class="producto-fila__precio-label">Precio</span>
                  <span class="precio"><?= $precio ?> €</span>
                </div>

                <form action="<?= Parameters::BASE_URL ?>agregarCarrito" method="POST">
                  <input type="hidden" name="id_producto" value="<?= $producto["id_producto"] ?>">

                  <button class="btn-carrito" type="submit">
                    Añadir al carrito
                  </button>
                </form>

              </div>

            </div>

          </article>

        <?php endforeach; ?>

      <?php else: ?>

        <p>No hay productos disponibles.</p>

      <?php endif; ?>

    </div>
  </section>
</main>

<script>

</script>

<?php include_once __DIR__ . "/layout/footer.php"; ?>