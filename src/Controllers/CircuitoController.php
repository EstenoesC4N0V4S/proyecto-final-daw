<?php

namespace ProyectoTFGRodrigo\Controllers;

use ProyectoTFGRodrigo\Config\Parameters;
use ProyectoTFGRodrigo\Models\CircuitoModel;

class CircuitoController
{
    public function guardar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $nombreCircuito = trim($_POST["nombre_circuito"] ?? "");
        $viewBox = trim($_POST["view_box"] ?? "");
        $pathD = trim($_POST["path_d"] ?? "");
        $factorAltura = (float) ($_POST["factor_altura"] ?? 0);
        $factorAero = (float) ($_POST["factor_aero"] ?? 0);

        if ($nombreCircuito === "" || $viewBox === "" || $pathD === "") {
            $_SESSION["error_circuito"] = "Debes completar todos los campos.";
            header("Location: " . Parameters::BASE_URL . "crearCircuito");
            exit;
        }

        if ($factorAltura <= 0 || $factorAero <= 0) {
            $_SESSION["error_circuito"] = "Los factores deben ser mayores que 0.";
            header("Location: " . Parameters::BASE_URL . "crearCircuito");
            exit;
        }

        $circuitoModel = new CircuitoModel();

        $guardado = $circuitoModel->crearCircuito(
            $nombreCircuito,
            $pathD,
            $viewBox,
            $factorAero,
            $factorAltura
        );

        if (!$guardado) {
            $_SESSION["error_circuito"] = "Error al guardar circuito.";
            header("Location: " . Parameters::BASE_URL . "crearCircuito");
            exit;
        }

        $_SESSION["ok_circuito"] = "Circuito enviado. Los administradores validarán el circuito y colocarán el punto de inicio.";

        header("Location: " . Parameters::BASE_URL . "crearCircuito");
        exit;
    }
}