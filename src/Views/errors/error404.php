<?php
include_once __DIR__ . "/../layout/header.php";
?>

<main class="error-page">
    <div class="error-container">
        <h1 class="error-code">404</h1>

        <h2 class="error-title">Página no encontrada</h2>

        <p class="error-text">
            La ruta no existe o ha sido movida fuera de pista 🏎️
        </p>

        <a href="<?= ProyectoTFGRodrigo\Config\Parameters::BASE_URL ?>home" class="error-btn">
            Volver al inicio
        </a>
    </div>
</main>
<?php include_once __DIR__ . "/../layout/footer.php"; ?>