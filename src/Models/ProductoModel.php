<?php

namespace ProyectoTFGRodrigo\Models;

use PDO;

class ProductoModel extends Model
{
    /**
     * Vuelve todos los datos en la tienda, pasando por controles de seguridad y formato
     * @return array
     */
    public function obtenerProductos(): array
    {
        $sql = "SELECT * FROM producto ORDER BY id_producto ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vuelca los datos de un producto específico por su ID
     * @param int $idProducto
     * @return array|null
     */
    public function obtenerProductoPorId(int $idProducto): ?array
{
    $sql = "SELECT * FROM producto WHERE id_producto = :id_producto LIMIT 1";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bindParam(":id_producto", $idProducto, PDO::PARAM_INT);
    $stmt->execute();

    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    return $producto ?: null;
}
}