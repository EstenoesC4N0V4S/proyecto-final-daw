const PASSWORD_RULES = [
  {
    key: "minLength",
    label: "Mínimo 6 caracteres",
    validate: (password) => password.length >= 6,
  },
  {
    key: "uppercase",
    label: "Una mayúscula",
    validate: (password) => /[A-Z]/.test(password),
  },
  {
    key: "lowercase",
    label: "Una minúscula",
    validate: (password) => /[a-z]/.test(password),
  },
  {
    key: "number",
    label: "Un número",
    validate: (password) => /[0-9]/.test(password),
  },
  {
    key: "specialChar",
    label: "Un carácter especial",
    validate: (password) => /[^A-Za-z0-9]/.test(password),
  },
];

function crearItemValidacion(texto, valido) {
  const item = document.createElement("li");
  const icono = document.createElement("span");
  const textoRegla = document.createElement("span");

  item.classList.add("password-rule", valido ? "is-valid" : "is-invalid");

  icono.classList.add("password-rule__icon");
  icono.textContent = valido ? "✓" : "✕";

  textoRegla.textContent = texto;

  item.appendChild(icono);
  item.appendChild(textoRegla);

  return item;
}

function validarPassword(password, repetirPassword) {
  const resultados = {};

  PASSWORD_RULES.forEach((rule) => {
    resultados[rule.key] = rule.validate(password);
  });

  resultados.match = password === repetirPassword && repetirPassword !== "";

  return {
    resultados,
    esValida: Object.values(resultados).every(Boolean),
  };
}

function pintarReglasPassword(alertBox, resultados) {
  alertBox.replaceChildren();

  const titulo = document.createElement("strong");
  titulo.classList.add("password-rules__title");
  titulo.textContent = "La contraseña debe cumplir:";

  const lista = document.createElement("ul");
  lista.classList.add("password-rules__list");

  PASSWORD_RULES.forEach((rule) => {
    lista.appendChild(crearItemValidacion(rule.label, resultados[rule.key]));
  });

  lista.appendChild(
    crearItemValidacion("Las contraseñas deben coincidir", resultados.match),
  );

  alertBox.appendChild(titulo);
  alertBox.appendChild(lista);
}

function configurarValidacionPassword(formId, passwordId, repeatId, alertId) {
  const form = document.getElementById(formId);
  const passwordInput = document.getElementById(passwordId);
  const repeatInput = document.getElementById(repeatId);
  const alertBox = document.getElementById(alertId);

  if (!form || !passwordInput || !repeatInput || !alertBox) return;

  function actualizarValidacion() {
    const password = passwordInput.value;
    const repeatPassword = repeatInput.value;

    const { resultados, esValida } = validarPassword(password, repeatPassword);

    pintarReglasPassword(alertBox, resultados);

    const camposVacios = password === "" && repeatPassword === "";
    alertBox.classList.toggle("visible", !camposVacios);

    return esValida;
  }

  passwordInput.addEventListener("input", actualizarValidacion);
  repeatInput.addEventListener("input", actualizarValidacion);

  form.addEventListener("submit", (e) => {
    const password = passwordInput.value.trim();
    const repeatPassword = repeatInput.value.trim();

    // 👉 SI ESTÁ VACÍO → DEJA QUE PHP LO MANEJE
    if (password === "" && repeatPassword === "") {
      alertBox.classList.remove("visible");
      alertBox.replaceChildren();
      return;
    }

    // 👉 SOLO VALIDAR SI HAY ALGO ESCRITO
    if (!actualizarValidacion()) {
      e.preventDefault();
      alertBox.classList.add("visible");
    }
  });
}

function initTogglePassword() {
  const botones = document.querySelectorAll(".toggle-password");

  botones.forEach((btn) => {
    btn.addEventListener("click", () => {
      const inputId = btn.dataset.target;
      const input = document.getElementById(inputId);

      if (!input) return;

      const ojo = btn.querySelector(".icono-ojo");
      const ojoSlash = btn.querySelector(".icono-ojo-slash");
      const mostrar = input.type === "password";

      input.type = mostrar ? "text" : "password";
      btn.classList.toggle("active", mostrar);

      if (ojo) ojo.classList.toggle("oculto", mostrar);
      if (ojoSlash) ojoSlash.classList.toggle("oculto", !mostrar);
    });
  });
}

function iniciarToastPerfil() {
  const toast = document.getElementById("toastPerfil");

  if (!toast) return;

  setTimeout(() => {
    toast.classList.add("visible");
  }, 100);

  setTimeout(() => {
    toast.classList.remove("visible");
  }, 5000);
}

document.addEventListener("DOMContentLoaded", () => {
  configurarValidacionPassword(
    "formRegistro",
    "password",
    "password_confirm",
    "alertPassword",
  );

  configurarValidacionPassword(
    "formPasswordDatos",
    "password_nueva",
    "password_confirmar",
    "alertPasswordDatos",
  );

  initTogglePassword();
  iniciarToastPerfil();
});
