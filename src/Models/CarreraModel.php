<?php

namespace ProyectoTFGRodrigo\Models;

use PDO;

class CarreraModel extends Model
{
    public function guardarCarrera(int $idCircuito, int $idCocheGanador, float $tiempoGanador): bool
    {
        $sql = "INSERT INTO carrera (id_circuito, id_coche_ganador, tiempo_ganador)
                VALUES (:id_circuito, :id_coche_ganador, :tiempo_ganador)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":id_circuito" => $idCircuito,
            ":id_coche_ganador" => $idCocheGanador,
            ":tiempo_ganador" => $tiempoGanador
        ]);
    }

    public function registrarSetupCircuito(int $idSetup, int $idCircuito): bool
    {
        $sql = "INSERT IGNORE INTO setup_circuito (id_setup, id_circuito)
                VALUES (:id_setup, :id_circuito)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":id_setup" => $idSetup,
            ":id_circuito" => $idCircuito
        ]);
    }

    public function obtenerCarreras(): array
    {
        $sql = "SELECT ca.id_carrera, ca.fecha_carrera, ca.tiempo_ganador,
                       ci.nombre_circuito,
                       co.nombre AS nombre_coche
                FROM carrera ca
                INNER JOIN circuito ci ON ci.id_circuito = ca.id_circuito
                LEFT JOIN coche co ON co.id_coche = ca.id_coche_ganador
                ORDER BY ca.fecha_carrera DESC, ca.id_carrera DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
