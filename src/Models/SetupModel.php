<?php

namespace ProyectoTFGRodrigo\Models;

use PDO;

class SetupModel extends Model
{
    public function crearSetup(int $idCoche, string $nombre, int $anguloAleron, float $alturaSuspension): bool
    {
        $sql = "INSERT INTO setup (id_coche, nombre, angulo_aleron, altura_suspension, fecha_creacion)
                VALUES (:id_coche, :nombre, :angulo_aleron, :altura_suspension, NOW())";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":id_coche" => $idCoche,
            ":nombre" => $nombre,
            ":angulo_aleron" => $anguloAleron,
            ":altura_suspension" => $alturaSuspension
        ]);
    }

    public function registrarCocheUsuario(int $idUsuario, int $idCoche): bool
    {
        $sql = "INSERT IGNORE INTO usuario_coche (id_usuario, id_coche)
                VALUES (:id_usuario, :id_coche)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":id_usuario" => $idUsuario,
            ":id_coche" => $idCoche
        ]);
    }

    public function eliminarSetup(int $idSetup): bool
    {
        $sqlRelacion = "DELETE FROM setup_circuito WHERE id_setup = :id_setup";
        $stmtRelacion = $this->conexion->prepare($sqlRelacion);
        $stmtRelacion->execute([":id_setup" => $idSetup]);

        $sqlSetup = "DELETE FROM setup WHERE id_setup = :id_setup";
        $stmtSetup = $this->conexion->prepare($sqlSetup);
        $stmtSetup->execute([":id_setup" => $idSetup]);

        return $stmtSetup->rowCount() > 0;
    }

    public function obtenerSetups(): array
    {
        $sql = "SELECT s.id_setup, s.id_coche, s.nombre, s.angulo_aleron, s.altura_suspension, s.fecha_creacion,
                       c.nombre AS nombre_coche
                FROM setup s
                INNER JOIN coche c ON c.id_coche = s.id_coche
                ORDER BY s.fecha_creacion DESC, s.id_setup DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
