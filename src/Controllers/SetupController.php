<?php

namespace ProyectoTFGRodrigo\Controllers;

use ProyectoTFGRodrigo\Config\Parameters;
use ProyectoTFGRodrigo\Models\CocheModel;
use ProyectoTFGRodrigo\Models\SetupModel;

class SetupController
{
    public function ver()
    {
        $cocheModel = new CocheModel();
        $setupModel = new SetupModel();

        $coches = $cocheModel->obtenerCoches();
        $setups = $setupModel->obtenerSetups();

        require_once __DIR__ . "/../Views/setup.php";
    }

    public function crear()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idCoche = (int) ($_POST["id_coche"] ?? 0);
        $idUsuario = (int) ($_SESSION["id_usuario"] ?? 0);
        $nombre = trim($_POST["nombre"] ?? "");
        $anguloAleron = (int) ($_POST["angulo_aleron"] ?? 0);
        $alturaSuspension = (float) ($_POST["altura_suspension"] ?? 0);

        if ($idUsuario <= 0) {
            $_SESSION["error_setup"] = "Debes iniciar sesion para crear un setup.";
            header("Location: " . Parameters::BASE_URL . "login");
            exit;
        }

        if ($nombre === "" || $idCoche <= 0) {
            $_SESSION["error_setup"] = "Debes indicar un nombre y un coche.";
            header("Location: " . Parameters::BASE_URL . "setup");
            exit;
        }

        if ($anguloAleron < -5 || $anguloAleron > 5 || $alturaSuspension < 0 || $alturaSuspension > 20) {
            $_SESSION["error_setup"] = "Los valores del setup no son validos.";
            header("Location: " . Parameters::BASE_URL . "setup");
            exit;
        }

        if ($alturaSuspension < 4) {
            $_SESSION["error_setup"] = "No se puede crear el setup: la altura es demasiado baja y el riesgo es muy alto.";
            header("Location: " . Parameters::BASE_URL . "setup");
            exit;
        }

        $cocheModel = new CocheModel();

        if (!$cocheModel->existeCoche($idCoche)) {
            $_SESSION["error_setup"] = "El coche seleccionado no existe.";
            header("Location: " . Parameters::BASE_URL . "setup");
            exit;
        }

        $setupModel = new SetupModel();

        if (!$setupModel->crearSetup($idCoche, $nombre, $anguloAleron, $alturaSuspension)) {
            $_SESSION["error_setup"] = "No se ha podido crear el setup.";
            header("Location: " . Parameters::BASE_URL . "setup");
            exit;
        }

        $setupModel->registrarCocheUsuario($idUsuario, $idCoche);

        $_SESSION["ok_setup"] = "Setup creado correctamente.";
        header("Location: " . Parameters::BASE_URL . "setup");
        exit;
    }

    public function eliminar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idSetup = (int) ($_POST["id_setup"] ?? 0);

        if ($idSetup <= 0) {
            $_SESSION["error_setup"] = "No se ha indicado el setup a eliminar.";
            header("Location: " . Parameters::BASE_URL . "setup");
            exit;
        }

        $setupModel = new SetupModel();

        if (!$setupModel->eliminarSetup($idSetup)) {
            $_SESSION["error_setup"] = "No se ha podido eliminar el setup.";
            header("Location: " . Parameters::BASE_URL . "setup");
            exit;
        }

        $_SESSION["ok_setup"] = "Setup eliminado correctamente.";
        header("Location: " . Parameters::BASE_URL . "setup");
        exit;
    }
}
