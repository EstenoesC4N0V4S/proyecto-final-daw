<?php

namespace ProyectoTFGRodrigo\Models;

use PDO;

class CocheModel extends Model
{
    public function obtenerCoches(): array
    {
        $sql = "SELECT id_coche, nombre FROM coche ORDER BY id_coche ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existeCoche(int $idCoche): bool
    {
        $sql = "SELECT id_coche FROM coche WHERE id_coche = :id_coche LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(":id_coche", $idCoche, PDO::PARAM_INT);
        $stmt->execute();

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
