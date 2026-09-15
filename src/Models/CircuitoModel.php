<?php

namespace ProyectoTFGRodrigo\Models;

use PDO;

class CircuitoModel extends Model
{
    public function crearCircuito(
        string $nombreCircuito,
        string $pathD,
        string $viewBox,
        float $factorAero,
        float $factorAltura
    ): bool {
        $sql = "INSERT INTO circuito (
                nombre_circuito,
                svg_circuito,
                view_box_circuito,
                factor_aero,
                factor_altura,
                tiempo_base,
                punto_inicio,
                pais
            )
            VALUES (
                :nombre_circuito,
                :svg_circuito,
                :view_box_circuito,
                :factor_aero,
                :factor_altura,
                :tiempo_base,
                :punto_inicio,
                :pais
            )";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":nombre_circuito" => $nombreCircuito,
            ":svg_circuito" => $pathD,
            ":view_box_circuito" => $viewBox,
            ":factor_aero" => $factorAero,
            ":factor_altura" => $factorAltura,
            ":tiempo_base" => 90.0,
            ":punto_inicio" => 0.0,
            ":pais" => "Pendiente"
        ]);
    }

    public function obtenerCircuitos(): array
    {
        $sql = "SELECT id_circuito, nombre_circuito, pais, svg_circuito, view_box_circuito, factor_aero, factor_altura, tiempo_base, punto_inicio
                FROM circuito
                ORDER BY id_circuito ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCircuitoPorId(int $idCircuito): ?array
    {
        $sql = "SELECT id_circuito, nombre_circuito, pais, svg_circuito, view_box_circuito, factor_aero, factor_altura, tiempo_base, punto_inicio
                FROM circuito
                WHERE id_circuito = :id_circuito
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(":id_circuito", $idCircuito, PDO::PARAM_INT);
        $stmt->execute();

        $circuito = $stmt->fetch(PDO::FETCH_ASSOC);

        return $circuito ?: null;
    }
}
