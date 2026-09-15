<?php

include_once __DIR__ . "/layout/header.php";

use ProyectoTFGRodrigo\Config\Parameters;

$subtotal = 0;
$totalProductos = 0;

if (!empty($carrito)) {
  foreach ($carrito as $item) {
    $cantidad = (int) $item["cantidad"];
    $subtotal += (float) $item["precio"] * $cantidad;
    $totalProductos += $cantidad;
  }
}

$envio = !empty($carrito) ? 6.99 : 0;
$iva = 0; // IVA ya incluido en los precios
$total = $subtotal + $envio;
?>

<main id="contenido" class="cart-page">
  <section class="cart-hero" data-aos="fade-up" data-aos-duration="1000">
    <span class="cart-hero__badge">CARRITO DE COMPRA</span>

    <h1 class="cart-hero__title">
      Revisa tu pedido antes de salir a pista
    </h1>

    <p class="cart-hero__desc">
      Gestiona los productos de tu carrito, ajusta cantidades y consulta el
      resumen total de tu compra.
    </p>
  </section>

  <section class="cart-layout">

    <div class="cart-list <?= empty($carrito) ? 'cart-list--empty' : '' ?>" data-aos="fade-right"
      data-aos-duration="1000">

      <?php if (!empty($carrito)): ?>

        <?php foreach ($carrito as $item): ?>

          <?php
          $nombre = htmlspecialchars($item["nombre"]);
          $descripcion = htmlspecialchars($item["descripcion"]);
          $precio = (float) $item["precio"];
          $cantidad = (int) $item["cantidad"];

          $imagenes = [
            "ferrari.glb" => "ferrari-carrito.png",
            "aston_martin.glb" => "aston-martin-carrito.png",
            "red_bull.glb" => "red-bull-carrito.png",
            "casco.glb" => "casco-carrito.png"
          ];

          $imagen = $imagenes[$item["modelo_3d"]] ?? "ferrari-carrito.png";
          ?>

          <article class="cart-item" data-id="<?= $item["id_producto"] ?>" data-price="<?= $precio ?>">
            <div class="cart-item__img-wrap">
              <img src="<?= Parameters::BASE_URL ?>assets/img/img_carrito/<?= $imagen ?>" alt="<?= $nombre ?>"
                class="cart-item__img" />
            </div>

            <div class="cart-item__info">
              <h2 class="cart-item__title"><?= $nombre ?></h2>

              <p class="cart-item__desc">
                <?= $descripcion ?>
              </p>

              <span class="cart-item__price">
                <?= number_format($precio, 2, ",", ".") ?> €
              </span>
            </div>

            <div class="cart-item__actions">
              <div class="cart-qty">
                <button type="button" class="cart-qty__btn minus" aria-label="Restar cantidad">
                  −
                </button>

                <input type="number" class="cart-qty__input" value="<?= $cantidad ?>" min="1" />

                <button type="button" class="cart-qty__btn plus" aria-label="Sumar cantidad">
                  +
                </button>
              </div>

              <button type="button" class="cart-remove" data-id="<?= $item["id_producto"] ?>">
                🗑
              </button>
            </div>
          </article>

        <?php endforeach; ?>

      <?php else: ?>

        <article class="cart-item cart-empty">
          <div class="cart-empty__icon">🛒</div>

          <div class="cart-empty__content">
            <h2 class="cart-empty__title">
              No hay artículos en el carrito
            </h2>

            <p class="cart-empty__text">
              Empieza añadiendo productos desde la tienda.
            </p>

            <a href="<?= Parameters::BASE_URL ?>tienda" class="cart-empty__btn">
              Ir a la tienda
            </a>
          </div>
        </article>

      <?php endif; ?>

    </div>

    <aside class="cart-summary" data-aos="fade-left" data-aos-duration="1000">
      <span class="cart-summary__badge">RESUMEN</span>

      <h2 class="cart-summary__title">Resumen del pedido</h2>

      <p class="cart-summary__text">
        Consulta el importe final antes de finalizar tu compra.
      </p>

      <div class="cart-summary__rows">

        <div class="cart-summary__row cart-summary__row--count">
          <span>Productos en carrito</span>
          <strong id="cart-count">
            <?= $totalProductos ?> <?= $totalProductos === 1 ? "producto" : "productos" ?>
          </strong>
        </div>

        <?php if (!empty($carrito)): ?>
          <div class="cart-summary__breakdown">
            <h3 class="cart-summary__subtitle">Desglose de productos</h3>

            <?php foreach ($carrito as $item): ?>
              <?php
              $nombreResumen = htmlspecialchars($item["nombre"]);
              $precioResumen = (float) $item["precio"];
              $cantidadResumen = (int) $item["cantidad"];
              $totalProducto = $precioResumen * $cantidadResumen;
              ?>

              <div class="cart-summary__row cart-summary__row--product" data-id="<?= $item["id_producto"] ?>">
                <span>
                  <?= $nombreResumen ?> x<?= $cantidadResumen ?>
                </span>

                <strong>
                  <?= number_format($totalProducto, 2, ",", ".") ?> €
                </strong>
              </div>

            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="cart-summary__row">
          <span>Subtotal productos</span>
          <strong id="cart-subtotal">
            <?= number_format($subtotal, 2, ",", ".") ?> €
          </strong>
        </div>

        <div class="cart-summary__row">
          <span>Gastos de envío</span>
          <strong id="cart-shipping">
            <?= number_format($envio, 2, ",", ".") ?> €
          </strong>
        </div>

        <div class="cart-summary__row cart-summary__row--total">
          <span>Total</span>
          <strong id="cart-total">
            <?= number_format($total, 2, ",", ".") ?> €
          </strong>
        </div>
      </div>

      <button type="button" class="cart-summary__btn" id="open-checkout-modal" <?= empty($carrito) ? "disabled" : "" ?>>
        Finalizar compra
      </button>

      <p class="cart-summary__note">
        Pago seguro · Envío calculado automáticamente · Confirmación inmediata
      </p>
    </aside>

  </section>

  <?php if (!empty($carrito)): ?>
    <div class="checkout-modal" id="checkout-modal" aria-hidden="true" hidden>
      <div class="checkout-modal__backdrop" data-close-checkout></div>

      <section class="checkout-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="checkout-modal-title">
        <button type="button" class="checkout-modal__close" data-close-checkout aria-label="Cerrar resumen">
          &times;
        </button>

        <span class="checkout-modal__badge">PEDIDO LISTO</span>

        <h2 class="checkout-modal__title" id="checkout-modal-title">
          Resumen de la compra
        </h2>

        <div class="checkout-modal__items">
          <?php foreach ($carrito as $item): ?>
            <?php
            $cantidadModal = (int) $item["cantidad"];
            $precioModal = (float) $item["precio"];
            ?>
            <div class="checkout-modal__item" data-id="<?= $item["id_producto"] ?>">
              <span><?= htmlspecialchars($item["nombre"], ENT_QUOTES, "UTF-8") ?> x<?= $cantidadModal ?></span>
              <strong><?= number_format($precioModal * $cantidadModal, 2, ",", ".") ?> &euro;</strong>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="checkout-modal__total">
          <span>Total</span>
          <strong id="checkout-modal-total"><?= number_format($total, 2, ",", ".") ?> &euro;</strong>
        </div>

        <a class="checkout-modal__pdf" href="<?= Parameters::BASE_URL ?>descargarResumenCompraPdf">
          <i class="bi bi-filetype-pdf"></i>
          Descargar PDF
        </a>
      </section>
    </div>
  <?php endif; ?>
</main>

<?php include_once __DIR__ . "/layout/footer.php"; ?>
