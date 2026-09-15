<?php

use ProyectoTFGRodrigo\Config\Parameters;

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$errorLogin = $_SESSION["error_login"] ?? null;
$okRegistro = $_SESSION["ok_registro"] ?? null;
unset($_SESSION["error_login"], $_SESSION["ok_registro"]);

$correoRecordado = $_COOKIE["usuario_recordado"] ?? "";

$mensajesError = [
  "campos_vacios" => "Debes introducir el correo y la contraseña.",
  "email_invalido" => "El correo electrónico no tiene un formato válido.",
  "credenciales" => "El correo o la contraseña no son correctos.",
  "error_general" => "Ha ocurrido un error. Inténtalo de nuevo más tarde."
];

$mensajesOk["cuenta_creada"] = "Cuenta creada correctamente. Ya puedes iniciar sesion.";
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>F1 Setup Simulator - Login</title>

  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?= Parameters::BASE_URL ?>assets/style/styleLogin.css" />
</head>

<body>

<header class="login-header-bar">
  <div class="login-header-bar__inner">
    <a class="login-header-logo" href="<?= Parameters::BASE_URL ?>login">
      <img src="<?= Parameters::BASE_URL ?>assets/img/logo_f1.png" alt="Logo F1" class="login-header-logo__img" />
      <span class="login-header-logo__text">F1 Setup Simulator</span>
    </a>
  </div>
</header>

<main class="login-page">

  <section class="login-wrapper">

    <div class="login-brand" data-aos="fade-right" data-aos-duration="900">
      <div class="brand-line"></div>

      <p class="brand-kicker">Acceso restringido</p>

      <h1 id="titulo-f1">
        <span>PANEL DE</span>
        <span>CONTROL</span>
      </h1>

      <p class="brand-text">
        Inicia sesión para acceder al entorno de administración del sistema.
      </p>
    </div>

    <form 
      class="login-card" 
      method="POST" 
      action="<?= Parameters::BASE_URL ?>procesarLogin"
      data-aos="fade-left"
      data-aos-duration="900"
      data-aos-delay="150"
    >

      <div class="login-header">
        <span class="login-badge">F1 USER LOGIN</span>
        <h2>Bienvenido</h2>
        <p>Introduce tus credenciales para continuar</p>
      </div>

      <?php if ($errorLogin && isset($mensajesError[$errorLogin])): ?>
        <div class="login-error">
          <?= htmlspecialchars($mensajesError[$errorLogin], ENT_QUOTES, "UTF-8") ?>
        </div>
      <?php endif; ?>

      <?php if ($okRegistro && isset($mensajesOk[$okRegistro])): ?>
        <div class="login-success">
          <?= htmlspecialchars($mensajesOk[$okRegistro], ENT_QUOTES, "UTF-8") ?>
        </div>
      <?php endif; ?>

      <div class="input-group">
        <label for="email">Correo electrónico</label>
        <input 
          id="email" 
          name="email" 
          type="email" 
          placeholder="Introduce tu correo" 
          autocomplete="email"
          value="<?= htmlspecialchars($correoRecordado, ENT_QUOTES, "UTF-8") ?>"
           
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
            autocomplete="current-password"
             
          />

          <button type="button" id="verPassword" class="toggle-password" aria-label="Mostrar contraseña">
            <svg id="iconoOjo" viewBox="0 0 24 24" class="icono">
              <path fill="currentColor"
                d="M12 4.5C7 4.5 2.7 7.6 1 12c1.7 4.4 6 7.5 11 7.5s9.3-3.1 11-7.5c-1.7-4.4-6-7.5-11-7.5Zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
            </svg>

            <svg id="iconoOjoSlash" viewBox="0 0 24 24" class="icono oculto">
              <path fill="currentColor"
                d="M2.1 3.5 3.4 2.2 21.8 20.6 20.5 21.9l-3.1-3.1A11.8 11.8 0 0 1 12 19.5C7 19.5 2.7 16.4 1 12c.8-2.1 2.2-3.9 4-5.2L2.1 3.5Zm7.1 7.1a3 3 0 0 0 4.2 4.2l-4.2-4.2ZM12 4.5c5 0 9.3 3.1 11 7.5a12.7 12.7 0 0 1-3.2 4.6l-2.5-2.5A5 5 0 0 0 10 6.8L8.1 4.9A12.3 12.3 0 0 1 12 4.5Z" />
            </svg>
          </button>
        </div>
      </div>

      <div class="login-remember">
        <label for="recordar">
          <input 
            type="checkbox" 
            name="recordar" 
            id="recordar"
            <?= $correoRecordado !== "" ? "checked" : "" ?>
          />
          Recordar correo
        </label>
      </div>

      <button type="submit" class="login-btn">
        Iniciar sesión
      </button>

      <div class="login-extra">
        <p>¿No tienes cuenta?</p>
        <a href="<?= Parameters::BASE_URL ?>registro" class="login-register-btn">
          Crear cuenta
        </a>
      </div>

    </form>

  </section>

</main>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="<?= Parameters::BASE_URL ?>assets/js/script.js"></script>

<script>
  AOS.init({
    once: true,
    duration: 900,
    easing: "ease-out-cubic"
  });
</script>

</body>
</html>
