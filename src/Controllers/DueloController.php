<?php

namespace ProyectoTFGRodrigo\Controllers;

use ProyectoTFGRodrigo\Models\CircuitoModel;
use ProyectoTFGRodrigo\Models\CarreraModel;
use ProyectoTFGRodrigo\Models\SetupModel;

class DueloController
{
    public function ver()
    {
        $model = new CircuitoModel();
        $setupModel = new SetupModel();
        $carreraModel = new CarreraModel();
        $circuitos = $model->obtenerCircuitos();
        $setups = $setupModel->obtenerSetups();
        $carreras = $carreraModel->obtenerCarreras();

        $idCircuito = (int) ($_GET["id_circuito"] ?? 0);
        $circuitoSeleccionado = $idCircuito > 0
            ? $model->obtenerCircuitoPorId($idCircuito)
            : null;

        if (!$circuitoSeleccionado && !empty($circuitos)) {
            $circuitoSeleccionado = $circuitos[0];
        }

        require_once __DIR__ . "/../Views/duelo.php";
    }

    public function guardarCarrera()
    {
        header("Content-Type: application/json; charset=UTF-8");

        $idCircuito = (int) ($_POST["id_circuito"] ?? 0);
        $idCocheGanador = (int) ($_POST["id_coche_ganador"] ?? 0);
        $tiempoGanador = (float) ($_POST["tiempo_ganador"] ?? 0);
        $idsSetup = $_POST["ids_setup"] ?? [];

        if ($idCircuito <= 0 || $idCocheGanador <= 0 || $tiempoGanador <= 0) {
            http_response_code(422);
            echo json_encode(["ok" => false, "error" => "Datos de carrera invalidos."]);
            return;
        }

        $carreraModel = new CarreraModel();

        if (!$carreraModel->guardarCarrera($idCircuito, $idCocheGanador, $tiempoGanador)) {
            http_response_code(500);
            echo json_encode(["ok" => false, "error" => "No se ha podido guardar la carrera."]);
            return;
        }

        if (!is_array($idsSetup)) {
            $idsSetup = [$idsSetup];
        }

        foreach (array_unique(array_map("intval", $idsSetup)) as $idSetup) {
            if ($idSetup > 0) {
                $carreraModel->registrarSetupCircuito($idSetup, $idCircuito);
            }
        }

        echo json_encode([
            "ok" => true,
            "fecha_carrera" => date("Y-m-d H:i:s")
        ]);
    }

}
