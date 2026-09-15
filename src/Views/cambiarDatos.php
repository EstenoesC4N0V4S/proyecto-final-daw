<?php

include_once __DIR__ . "/layout/header.php";

use ProyectoTFGRodrigo\Config\Parameters;

$toastMensaje = null;
$toastTipo = null;

if (!empty($_SESSION["ok_datos"])) {
  $toastMensaje = "Datos actualizados correctamente.";
  $toastTipo = "ok";
  unset($_SESSION["ok_datos"]);
}

if (!empty($_SESSION["ok_password"])) {
  $toastMensaje = "Contraseña actualizada correctamente.";
  $toastTipo = "ok";
  unset($_SESSION["ok_password"]);
}

$errorDatos = $_SESSION["error_datos"] ?? null;
$errorPassword = $_SESSION["error_password"] ?? null;

unset($_SESSION["error_datos"], $_SESSION["error_password"]);

?>

<main id="contenido" class="datos-page">

  <?php if ($toastMensaje): ?>
    <div id="toastPerfil" class="toast-perfil toast-perfil--<?= htmlspecialchars($toastTipo) ?>">
      <?= htmlspecialchars($toastMensaje) ?>
    </div>
  <?php endif; ?>

  <section class="datos-hero">
    <span class="datos-hero__badge">MI PERFIL</span>

    <h1 class="datos-hero__title">
      Gestiona tus datos de usuario
    </h1>

    <p class="datos-hero__desc">
      Cambia tu nombre de usuario, actualiza tu correo o modifica tu contraseña de acceso.
    </p>
  </section>

  <section class="datos-layout">

    <article class="datos-card">
      <div class="datos-card__header">
        <span class="datos-card__icon">
          <i class="bi bi-person-circle"></i>
        </span>

        <div>
          <h2>Datos personales</h2>
          <p>Introduce un nuevo nombre de usuario o modifica tu correo.</p>
        </div>
      </div>

      <form class="datos-form" action="<?= Parameters::BASE_URL ?>guardarDatos" method="post">

        <div class="datos-form__grupo">
          <label for="nombre_usuario">Nombre de usuario</label>
          <input type="text" id="nombre_usuario" name="nombre_usuario"
            value="<?= htmlspecialchars($_SESSION["nombre_usuario"] ?? "") ?>" >
        </div>

        <div class="datos-form__grupo">
          <label for="correo_actual">Correo actual</label>
          <input type="email" id="correo_actual" value="<?= htmlspecialchars($_SESSION["correo"] ?? "") ?>" readonly>
        </div>

        <div class="datos-form__grupo">
          <label for="correo">Nuevo correo electrónico</label>
          <input type="email" id="correo" name="correo" placeholder="Introduce el nuevo correo electrónico">
        </div>

        <div class="datos-form__grupo">
          <label for="correo_confirmar">Confirmar nuevo correo electrónico</label>
          <input type="email" id="correo_confirmar" name="correo_confirmar"
            placeholder="Repite el nuevo correo electrónico">
        </div>

        <?php if (!empty($errorDatos)): ?>
          <div class="form-error">
            <?= htmlspecialchars($errorDatos) ?>
          </div>
        <?php endif; ?>

        <button type="submit" class="datos-btn">
          Guardar cambios
        </button>

      </form>
    </article>

    <article class="datos-card">
      <div class="datos-card__header">
        <span class="datos-card__icon">
          <i class="bi bi-shield-lock"></i>
        </span>

        <div>
          <h2>Cambiar contraseña</h2>
          <p>Actualiza tu contraseña para mantener segura tu cuenta.</p>
        </div>
      </div>

      <form id="formPasswordDatos" class="datos-form" action="<?= Parameters::BASE_URL ?>guardarPassword" method="post">

        <?php
        $passwordInputs = [
          ["id" => "password_actual", "label" => "Contraseña actual"],
          ["id" => "password_nueva", "label" => "Nueva contraseña"],
          ["id" => "password_confirmar", "label" => "Confirmar contraseña"],
        ];
        ?>

        <?php foreach ($passwordInputs as $input): ?>
          <div class="datos-form__grupo">
            <label for="<?= $input["id"] ?>"><?= $input["label"] ?></label>

            <div class="input-wrapper">
              <input type="password" id="<?= $input["id"] ?>" name="<?= $input["id"] ?>">

              <button type="button" class="toggle-password" data-target="<?= $input["id"] ?>"
                aria-label="Mostrar u ocultar contraseña">

                <i class="bi bi-eye icono-ojo"></i>
                <i class="bi bi-eye-slash icono-ojo-slash oculto"></i>
              </button>
            </div>
          </div>
        <?php endforeach; ?>

        <?php if (!empty($errorPassword)): ?>
          <div class="form-error">
            <?= htmlspecialchars($errorPassword) ?>
          </div>
        <?php endif; ?>

        <div id="alertPasswordDatos" class="alert-password"></div>

        <button type="submit" class="datos-btn datos-btn--secundario">
          Cambiar contraseña
        </button>
      </form>
    </article>

  </section>
</main>

<?php include_once __DIR__ . "/layout/footer.php"; ?>