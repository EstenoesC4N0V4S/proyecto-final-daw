<?php

use ProyectoTFGRodrigo\Config\Parameters;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errorRegistro = $_SESSION["error_registro"] ?? null;

$oldRegistro = $_SESSION["old_registro"] ?? [
    "nombre_usuario" => "",
    "correo" => ""
];

unset($_SESSION["error_registro"], $_SESSION["old_registro"]);

$mensajesError = [
    "campos_vacios" => "Debes rellenar todos los campos.",
    "nombre_corto" => "El nombre de usuario debe tener al menos 3 caracteres.",
    "nombre_largo" => "El nombre de usuario no puede superar los 50 caracteres.",
    "email_invalido" => "El correo electrónico no tiene un formato válido.",
    "password_insegura" => "La contraseña debe tener mínimo 6 caracteres, una mayúscula, una minúscula, un número y un carácter especial.",
    "password_no_coincide" => "Las contraseñas no coinciden.",
    "correo_existe" => "Ya existe una cuenta con ese correo.",
    "error_general" => "Ha ocurrido un error. Inténtalo de nuevo más tarde."
];

?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>F1 Setup Simulator - Registro</title>

  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleLogin.css" />
</head>

<body>

<header class="login-header-bar">
  <div class="login-header-bar__inner">
    <a class="login-header-logo" href="<?= Parameters::BASE_URL ?>login">
      <img
        src="<?= Parameters::BASE_URL ?>assets/img/logo_f1.png"
        alt="Logo F1"
        class="login-header-logo__img"
      />

      <span class="login-header-logo__text">
        F1 Setup Simulator
      </span>
    </a>
  </div>
</header>

<main class="login-page">
  <section class="login-wrapper">

    <div class="login-brand">
      <div class="brand-line"></div>

      <p class="brand-kicker">Nuevo piloto</p>

      <h1 id="titulo-f1">
        <span>CREA TU</span>
        <span>CUENTA</span>
      </h1>

      <p class="brand-text">
        Regístrate para acceder al simulador, guardar tus datos y gestionar tu perfil.
      </p>
    </div>

    <form
      id="formRegistro"
      class="login-card"
      method="POST"
      action="<?= Parameters::BASE_URL ?>procesarRegistro"
      novalidate
    >

      <div class="login-header">
        <span class="login-badge">F1 USER REGISTER</span>
        <h2 class="login-title-underline">Registro</h2>
        <p>Crea tu cuenta para continuar</p>
      </div>

      <?php if ($errorRegistro && isset($mensajesError[$errorRegistro])): ?>
        <div class="login-error">
          <?= htmlspecialchars($mensajesError[$errorRegistro], ENT_QUOTES, "UTF-8") ?>
        </div>
      <?php endif; ?>

      <div class="input-group">
        <label for="nombre_usuario">Nombre de usuario</label>
        <input
          id="nombre_usuario"
          name="nombre_usuario"
          type="text"
          placeholder="Introduce tu nombre"
          autocomplete="name"
          value="<?= htmlspecialchars($oldRegistro["nombre_usuario"] ?? "", ENT_QUOTES, "UTF-8") ?>"
        />
      </div>

      <div class="input-group">
        <label for="correo">Correo electrónico</label>
        <input
          id="correo"
          name="correo"
          type="email"
          placeholder="Introduce tu correo"
          autocomplete="email"
          value="<?= htmlspecialchars($oldRegistro["correo"] ?? "", ENT_QUOTES, "UTF-8") ?>"
        />
      </div>

      <div class="input-group">
        <label for="password">Contraseña</label>

        <div class="input-wrapper">
          <input
            id="password"
            name="password"
            type="password"
            placeholder="Introduce tu contraseña"
            autocomplete="new-password"
          />

          <button
            type="button"
            class="toggle-password"
            data-target="password"
            aria-label="Mostrar u ocultar contraseña"
          >
            <svg class="icono icono-ojo" viewBox="0 0 24 24">
              <path
                fill="currentColor"
                d="M12 4.5C7 4.5 2.7 7.6 1 12c1.7 4.4 6 7.5 11 7.5s9.3-3.1 11-7.5c-1.7-4.4-6-7.5-11-7.5Zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
              />
            </svg>

            <svg class="icono icono-ojo-slash oculto" viewBox="0 0 24 24">
              <path
                fill="currentColor"
                d="M2.1 3.5 3.4 2.2 21.8 20.6 20.5 21.9l-3.1-3.1A11.8 11.8 0 0 1 12 19.5C7 19.5 2.7 16.4 1 12c.8-2.1 2.2-3.9 4-5.2L2.1 3.5Zm7.1 7.1a3 3 0 0 0 4.2 4.2l-4.2-4.2ZM12 4.5c5 0 9.3 3.1 11 7.5a12.7 12.7 0 0 1-3.2 4.6l-2.5-2.5A5 5 0 0 0 10 6.8L8.1 4.9A12.3 12.3 0 0 1 12 4.5Z"
              />
            </svg>
          </button>
        </div>
      </div>

      <div class="input-group">
        <label for="password_confirm">Repetir contraseña</label>

        <div class="input-wrapper">
          <input
            id="password_confirm"
            name="password_confirm"
            type="password"
            placeholder="Repite tu contraseña"
            autocomplete="new-password"
          />

          <button
            type="button"
            class="toggle-password"
            data-target="password_confirm"
            aria-label="Mostrar u ocultar contraseña"
          >
            <svg class="icono icono-ojo" viewBox="0 0 24 24">
              <path
                fill="currentColor"
                d="M12 4.5C7 4.5 2.7 7.6 1 12c1.7 4.4 6 7.5 11 7.5s9.3-3.1 11-7.5c-1.7-4.4-6-7.5-11-7.5Zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
              />
            </svg>

            <svg class="icono icono-ojo-slash oculto" viewBox="0 0 24 24">
              <path
                fill="currentColor"
                d="M2.1 3.5 3.4 2.2 21.8 20.6 20.5 21.9l-3.1-3.1A11.8 11.8 0 0 1 12 19.5C7 19.5 2.7 16.4 1 12c.8-2.1 2.2-3.9 4-5.2L2.1 3.5Zm7.1 7.1a3 3 0 0 0 4.2 4.2l-4.2-4.2ZM12 4.5c5 0 9.3 3.1 11 7.5a12.7 12.7 0 0 1-3.2 4.6l-2.5-2.5A5 5 0 0 0 10 6.8L8.1 4.9A12.3 12.3 0 0 1 12 4.5Z"
              />
            </svg>
          </button>
        </div>
      </div>

      <div id="alertPassword" class="password-rules"></div>

      <button type="submit" class="login-btn">
        Crear cuenta
      </button>

      <div class="login-extra">
        <p>¿Ya tienes cuenta?</p>
        <a href="<?= Parameters::BASE_URL ?>login" class="login-register-btn">
          Iniciar sesión
        </a>
      </div>

    </form>
  </section>
</main>

<script src="<?= Parameters::BASE_URL ?>assets/js/script.js"></script>
<script src="<?= Parameters::BASE_URL ?>assets/js/scriptValidarCliente.js"></script>

</body>
</html>
