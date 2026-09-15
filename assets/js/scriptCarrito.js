document.addEventListener("DOMContentLoaded", iniciarCarrito);

/**
 * Inicializa la funcionalidad del carrito.
 */
function iniciarCarrito() {
  const paginaCarrito = document.querySelector(".cart-page");

  if (!paginaCarrito) return;

  const productos = document.querySelectorAll(".cart-item:not(.cart-empty)");
  const URL_BASE = document.body.dataset.baseUrl || "";

  productos.forEach((producto) => {
    configurarProducto(producto, URL_BASE);
  });

  configurarModalCompra();
  recalcularResumen();
}

/**
 * Abre y cierra el modal de resumen al finalizar la compra.
 */
function configurarModalCompra() {
  const botonAbrir = document.getElementById("open-checkout-modal");
  const modal = document.getElementById("checkout-modal");
  const cierres = document.querySelectorAll("[data-close-checkout]");

  if (!botonAbrir || !modal) return;

  botonAbrir.addEventListener("click", () => {
    modal.hidden = false;
    modal.setAttribute("aria-hidden", "false");
    document.body.classList.add("checkout-modal-open");

    window.requestAnimationFrame(() => {
      modal.classList.add("is-open");
    });
  });

  cierres.forEach((cierre) => {
    cierre.addEventListener("click", () => cerrarModalCompra(modal));
  });

  document.addEventListener("keydown", (evento) => {
    if (evento.key === "Escape" && modal.classList.contains("is-open")) {
      cerrarModalCompra(modal);
    }
  });
}

/**
 * Cierra el modal de compra y restaura el scroll.
 */
function cerrarModalCompra(modal) {
  modal.classList.remove("is-open");
  modal.setAttribute("aria-hidden", "true");
  document.body.classList.remove("checkout-modal-open");

  window.setTimeout(() => {
    if (!modal.classList.contains("is-open")) {
      modal.hidden = true;
    }
  }, 320);
}

/**
 * Configura los eventos de un producto del carrito.
 */
function configurarProducto(producto, URL_BASE) {
  const idProducto = producto.dataset.id;
  const inputCantidad = producto.querySelector(".cart-qty__input");
  const botonSumar = producto.querySelector(".plus");
  const botonRestar = producto.querySelector(".minus");
  const botonEliminar = producto.querySelector(".cart-remove");

  botonSumar.addEventListener("click", () => {
    cambiarCantidad(inputCantidad, idProducto, URL_BASE, 1);
  });

  botonRestar.addEventListener("click", () => {
    cambiarCantidad(inputCantidad, idProducto, URL_BASE, -1);
  });

  inputCantidad.addEventListener("change", () => {
    validarCantidad(inputCantidad);
    recalcularResumen();
    guardarCantidad(idProducto, inputCantidad.value, URL_BASE);
  });

  botonEliminar.addEventListener("click", () => {
    eliminarProducto(idProducto, producto, URL_BASE);
  });
}

/**
 * Suma o resta cantidad a un producto.
 */
function cambiarCantidad(inputCantidad, idProducto, URL_BASE, cambio) {
  let cantidad = parseInt(inputCantidad.value) || 1;
  cantidad += cambio;

  if (cantidad < 1) {
    cantidad = 1;
  }

  inputCantidad.value = cantidad;

  recalcularResumen();
  guardarCantidad(idProducto, cantidad, URL_BASE);
}

/**
 * Evita cantidades menores que 1.
 */
function validarCantidad(inputCantidad) {
  const cantidad = parseInt(inputCantidad.value);

  if (cantidad < 1 || isNaN(cantidad)) {
    inputCantidad.value = 1;
  }
}

/**
 * Elimina un producto del carrito mediante AJAX.
 */
function eliminarProducto(idProducto, producto, URL_BASE) {
  const datos = new FormData();

  datos.append("id_producto", idProducto);

  fetch(URL_BASE + "eliminarCarrito", {
    method: "POST",
    body: datos,
  })
    .then((respuesta) => respuesta.json())
    .then((datos) => {
      if (datos.ok) {
        producto.remove();

        const filaResumen = document.querySelector(
          `.cart-summary__row--product[data-id="${idProducto}"]`
        );

        if (filaResumen) {
          filaResumen.remove();
        }

        const filaModal = document.querySelector(
          `.checkout-modal__item[data-id="${idProducto}"]`
        );

        if (filaModal) {
          filaModal.remove();
        }

        recalcularResumen();

        if (document.querySelectorAll(".cart-item:not(.cart-empty)").length === 0) {
          location.reload();
        }
      }
    });
}

/**
 * Recalcula subtotal, envío, IVA, total, cantidad y desglose.
 */
function recalcularResumen() {
  const productos = document.querySelectorAll(".cart-item:not(.cart-empty)");

  let subtotal = 0;
  let totalProductos = 0;

  productos.forEach((producto) => {
    const id = producto.dataset.id;
    const precio = parseFloat(producto.dataset.price) || 0;
    const inputCantidad = producto.querySelector(".cart-qty__input");
    const cantidad = parseInt(inputCantidad.value) || 1;
    const nombre = producto.querySelector(".cart-item__title").textContent.trim();

    subtotal += precio * cantidad;
    totalProductos += cantidad;

    const filaResumen = document.querySelector(
      `.cart-summary__row--product[data-id="${id}"]`
    );

    if (filaResumen) {
      const nombreResumen = filaResumen.querySelector("span");
      const precioResumen = filaResumen.querySelector("strong");

      nombreResumen.textContent = `${nombre} x${cantidad}`;
      precioResumen.textContent = formatearPrecio(precio * cantidad);
    }

    const filaModal = document.querySelector(
      `.checkout-modal__item[data-id="${id}"]`
    );

    if (filaModal) {
      filaModal.querySelector("span").textContent = `${nombre} x${cantidad}`;
      filaModal.querySelector("strong").textContent = formatearPrecio(
        precio * cantidad
      );
    }
  });

  const envio = subtotal > 0 ? 6.99 : 0;
  const iva = 0;
  const total = subtotal + envio;

  actualizarResumen(subtotal, envio, iva, total, totalProductos);
  actualizarModalCompra(total);
}

/**
 * Actualiza los importes y la cantidad total del resumen del pedido.
 */
function actualizarResumen(subtotal, envio, iva, total, totalProductos) {
  const subtotalElemento = document.getElementById("cart-subtotal");
  const envioElemento = document.getElementById("cart-shipping");
  const ivaElemento = document.getElementById("cart-tax");
  const totalElemento = document.getElementById("cart-total");
  const contadorElemento = document.getElementById("cart-count");

  if (subtotalElemento) {
    subtotalElemento.textContent = formatearPrecio(subtotal);
  }

  if (envioElemento) {
    envioElemento.textContent = formatearPrecio(envio);
  }

  if (ivaElemento) {
    ivaElemento.textContent = formatearPrecio(iva);
  }

  if (totalElemento) {
    totalElemento.textContent = formatearPrecio(total);
  }

  if (contadorElemento) {
    contadorElemento.textContent =
      totalProductos + " " + (totalProductos === 1 ? "producto" : "productos");
  }

  actualizarContadorHeader(totalProductos);
}

/**
 * Sincroniza el numero del icono del carrito en el header.
 */
function actualizarContadorHeader(totalProductos) {
  const contadorHeader = document.getElementById("cart-header-count");

  if (!contadorHeader) return;

  if (totalProductos <= 0) {
    contadorHeader.remove();
    return;
  }

  contadorHeader.textContent = totalProductos;
}

/**
 * Mantiene actualizado el total del modal de finalizar compra.
 */
function actualizarModalCompra(total) {
  const totalModal = document.getElementById("checkout-modal-total");

  if (totalModal) {
    totalModal.textContent = formatearPrecio(total);
  }
}

/**
 * Guarda la cantidad actual en la sesión PHP mediante AJAX.
 */
function guardarCantidad(idProducto, cantidad, URL_BASE) {
  const datos = new FormData();

  datos.append("id_producto", idProducto);
  datos.append("cantidad", cantidad);

  fetch(URL_BASE + "actualizarCantidadCarrito", {
    method: "POST",
    body: datos,
  });
}

/**
 * Formatea un número como precio en euros.
 */
function formatearPrecio(numero) {
  return numero.toLocaleString("es-ES", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }) + " €";
}
