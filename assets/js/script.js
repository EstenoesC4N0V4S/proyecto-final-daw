/* ===============================
   MODO OSCURO
================================= */

function cambiarModo() {
  const botonModo = document.getElementById("modo-btn");
  const iconoModo = document.getElementById("icono-modo");

  if (!botonModo) return;

  function actualizarIcono() {
    const estaOscuro = document.body.classList.contains("modo-oscuro");

    if (!iconoModo) return;

    iconoModo.classList.toggle("bi-moon-fill", !estaOscuro);
    iconoModo.classList.toggle("bi-sun-fill", estaOscuro);
  }

  const modoGuardado = localStorage.getItem("modo");

  if (modoGuardado === "oscuro") {
    document.body.classList.add("modo-oscuro");
  } else {
    document.body.classList.remove("modo-oscuro");
  }

  actualizarIcono();

  botonModo.addEventListener("click", () => {
    document.body.classList.toggle("modo-oscuro");

    if (document.body.classList.contains("modo-oscuro")) {
      localStorage.setItem("modo", "oscuro");
    } else {
      localStorage.setItem("modo", "claro");
    }

    actualizarIcono();
  });
}

/* ===============================
   MOSTRAR VALORES DE LOS SLIDERS
================================= */

function sliderNumero() {
  const sliderAltura = document.getElementById("slider-altura");
  const valorAltura = document.getElementById("valor-altura");

  const sliderAngulo = document.getElementById("slider-angulo");
  const valorAngulo = document.getElementById("valor-angulo");

  if (sliderAltura && valorAltura) {
    valorAltura.textContent = sliderAltura.value;

    sliderAltura.addEventListener("input", function () {
      valorAltura.textContent = this.value;
    });
  }

  if (sliderAngulo && valorAngulo) {
    valorAngulo.textContent = sliderAngulo.value;

    sliderAngulo.addEventListener("input", function () {
      valorAngulo.textContent = this.value;
    });
  }
}

/* ===============================
   NIVELES TEXTUALES
================================= */

function actualizarNivelEstabilidad(estabilidadElemento, estabilidadValor) {
  if (!estabilidadElemento) return;

  if (estabilidadValor <= 20) {
    estabilidadElemento.textContent = "Muy baja";
  } else if (estabilidadValor <= 40) {
    estabilidadElemento.textContent = "Baja";
  } else if (estabilidadValor <= 60) {
    estabilidadElemento.textContent = "Media";
  } else if (estabilidadValor <= 80) {
    estabilidadElemento.textContent = "Alta";
  } else {
    estabilidadElemento.textContent = "Muy alta";
  }
}

function actualizarNivelRiesgo(riesgoElemento, riesgoValor) {
  if (!riesgoElemento) return;

  riesgoElemento.classList.remove(
    "riesgo-muy-alto",
    "riesgo-alto",
    "riesgo-medio",
    "riesgo-bajo",
    "riesgo-muy-bajo"
  );

  if (riesgoValor >= 80) {
    riesgoElemento.textContent = "Muy alto";
    riesgoElemento.classList.add("riesgo-muy-alto");
  } else if (riesgoValor >= 60) {
    riesgoElemento.textContent = "Alto";
    riesgoElemento.classList.add("riesgo-alto");
  } else if (riesgoValor >= 40) {
    riesgoElemento.textContent = "Medio";
    riesgoElemento.classList.add("riesgo-medio");
  } else if (riesgoValor >= 20) {
    riesgoElemento.textContent = "Bajo";
    riesgoElemento.classList.add("riesgo-bajo");
  } else {
    riesgoElemento.textContent = "Muy bajo";
    riesgoElemento.classList.add("riesgo-muy-bajo");
  }
}

function actualizarNivelVelocidad(elemento, valor) {
  if (!elemento) return;

  if (valor <= 20) {
    elemento.textContent = "Muy baja";
  } else if (valor <= 40) {
    elemento.textContent = "Baja";
  } else if (valor <= 60) {
    elemento.textContent = "Media";
  } else if (valor <= 80) {
    elemento.textContent = "Alta";
  } else {
    elemento.textContent = "Muy alta";
  }
}

function calcularAeroFormula(angulo) {
  return 0.7 + ((Number(angulo) + 5) / 10) * 1.1;
}

function calcularAlturaFormula(altura) {
  return 0.8 + (Number(altura) / 20) * 0.6;
}

function actualizarValoresFormula() {
  const sliderAltura = document.getElementById("slider-altura");
  const sliderAngulo = document.getElementById("slider-angulo");
  const valorAlturaFormula = document.getElementById("valor-altura-formula");
  const valorAeroFormula = document.getElementById("valor-aero-formula");

  if (sliderAltura && valorAlturaFormula) {
    valorAlturaFormula.textContent = calcularAlturaFormula(sliderAltura.value).toFixed(2);
  }

  if (sliderAngulo && valorAeroFormula) {
    valorAeroFormula.textContent = calcularAeroFormula(sliderAngulo.value).toFixed(2);
  }
}

function validarCreacionSetup() {
  const formSetup = document.getElementById("form-setup");
  const sliderAltura = document.getElementById("slider-altura");

  if (!formSetup || !sliderAltura) return;

  formSetup.addEventListener("submit", (event) => {
    const altura = Number(sliderAltura.value);

    if (altura >= 4) return;

    event.preventDefault();
    alert("No se puede crear el setup: la altura es demasiado baja y el riesgo es muy alto.");
  });
}

/* ===============================
   ALTURA DEL COCHE
================================= */

function alturaCoche() {
  const sliderAltura = document.getElementById("slider-altura");
  const valorAltura = document.getElementById("valor-altura");
  const cocheBase = document.getElementById("coche-base");
  const aleron = document.getElementById("aleron");

  const estabilidad = document.getElementById("estabilidad");
  const riesgo = document.getElementById("riesgo");

  if (
    !sliderAltura ||
    !valorAltura ||
    !cocheBase ||
    !aleron ||
    !estabilidad ||
    !riesgo
  ) {
    return;
  }

  function actualizarAltura(valor) {
    const valorNumero = parseFloat(valor);

    valorAltura.textContent = valorNumero;

    const desplazamiento = valorNumero * -0.4;

    cocheBase.style.transform = `translateY(${desplazamiento}px)`;
    aleron.style.transform = `translate(-50%, calc(-50% + ${desplazamiento}px))`;

    let riesgoValor = 100 - valorNumero * 5;
    let estabilidadValor = 100 - valorNumero * 5;

    riesgoValor = Math.max(0, Math.min(100, riesgoValor));
    estabilidadValor = Math.max(0, Math.min(100, estabilidadValor));

    actualizarNivelEstabilidad(estabilidad, estabilidadValor);
    actualizarNivelRiesgo(riesgo, riesgoValor);
    actualizarValoresFormula();
  }

  actualizarAltura(sliderAltura.value);

  sliderAltura.addEventListener("input", function () {
    actualizarAltura(this.value);
  });
}

/* ===============================
   ÁNGULO DEL ALERÓN
================================= */

function anguloAleron() {
  const sliderAngulo = document.getElementById("slider-angulo");
  const valorAngulo = document.getElementById("valor-angulo");
  const aleron = document.getElementById("aleron");

  const velocidadCurvas = document.getElementById("velocidad-curvas");
  const velocidadRecta = document.getElementById("velocidad-recta");

  if (
    !sliderAngulo ||
    !valorAngulo ||
    !aleron ||
    !velocidadCurvas ||
    !velocidadRecta
  ) {
    return;
  }

  function actualizarAleron(valor) {
    const valorNumero = parseInt(valor, 10);

    valorAngulo.textContent = valorNumero;

    aleron.src = `/assets/img/img_coche/aleron_${valorNumero}.png`;

    let curvasValor = 50 + valorNumero * 7;
    let rectaValor = 50 - valorNumero * 7;

    curvasValor = Math.max(0, Math.min(100, curvasValor));
    rectaValor = Math.max(0, Math.min(100, rectaValor));

    actualizarNivelVelocidad(velocidadCurvas, curvasValor);
    actualizarNivelVelocidad(velocidadRecta, rectaValor);
    actualizarValoresFormula();
  }

  actualizarAleron(sliderAngulo.value);

  sliderAngulo.addEventListener("input", function () {
    actualizarAleron(this.value);
  });
}

/* ===============================
   VER / OCULTAR CONTRASEÑA
================================= */

function verContrasenia() {
  const pass = document.getElementById("password");
  const boton = document.getElementById("verPassword");
  const iconoOjo = document.getElementById("iconoOjo");
  const iconoOjoSlash = document.getElementById("iconoOjoSlash");

  if (!pass || !boton || !iconoOjo || !iconoOjoSlash) return;

  boton.addEventListener("click", () => {
    const esVisible = pass.type === "text";

    pass.type = esVisible ? "password" : "text";

    iconoOjo.classList.toggle("oculto", !esVisible);
    iconoOjoSlash.classList.toggle("oculto", esVisible);
  });
}

/* ===============================
   INICIALIZACIÓN
================================= */

document.addEventListener("DOMContentLoaded", () => {
  cambiarModo();
  sliderNumero();
  alturaCoche();
  anguloAleron();
  actualizarValoresFormula();
  validarCreacionSetup();
  verContrasenia();
});
