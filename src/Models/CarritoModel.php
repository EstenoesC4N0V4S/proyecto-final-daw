<?php

namespace ProyectoTFGRodrigo\Models;

use ProyectoTFGRodrigo\Models\Model;
use PDO;

class CarritoModel extends Model
{
    /**
     * Añade un producto al carrito.
     * Si ya existe, aumenta la cantidad.
     */
    public function agregarProducto(int $idUsuario, int $idProducto): bool
    {
        $sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad)
                VALUES (:id_usuario, :id_producto, 1)
                ON DUPLICATE KEY UPDATE cantidad = cantidad + 1";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":id_usuario" => $idUsuario,
            ":id_producto" => $idProducto
        ]);
    }

    /**
     * Obtiene todos los productos del carrito de un usuario.
     */
    public function obtenerCarrito(int $idUsuario): array
    {
        $sql = "SELECT 
                    c.id_producto,
                    c.cantidad,
                    p.nombre,
                    p.descripcion,
                    p.modelo_3d,
                    p.precio
                FROM carrito c
                INNER JOIN producto p ON c.id_producto = p.id_producto
                WHERE c.id_usuario = :id_usuario";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ":id_usuario" => $idUsuario
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cuenta la cantidad total de productos del carrito de un usuario.
     */
    public function contarProductos(int $idUsuario): int
    {
        $sql = "SELECT COALESCE(SUM(cantidad), 0) AS total
                FROM carrito
                WHERE id_usuario = :id_usuario";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ":id_usuario" => $idUsuario
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Elimina un producto del carrito.
     */
    public function eliminarProducto(int $idUsuario, int $idProducto): bool
    {
        $sql = "DELETE FROM carrito 
                WHERE id_usuario = :id_usuario 
                AND id_producto = :id_producto";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":id_usuario" => $idUsuario,
            ":id_producto" => $idProducto
        ]);
    }

    /**
     * Actualiza la cantidad de un producto del carrito.
     */
    public function actualizarCantidad(int $idUsuario, int $idProducto, int $cantidad): bool
    {
        $sql = "UPDATE carrito 
                SET cantidad = :cantidad
                WHERE id_usuario = :id_usuario 
                AND id_producto = :id_producto";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":cantidad" => $cantidad,
            ":id_usuario" => $idUsuario,
            ":id_producto" => $idProducto
        ]);
    }

    /**
     * Calcula los totales del carrito.
     */
    public function calcularTotales(int $idUsuario): array
    {
        $carrito = $this->obtenerCarrito($idUsuario);

        $subtotal = 0;

        foreach ($carrito as $item) {
            $subtotal += (float) $item["precio"] * (int) $item["cantidad"];
        }

        $envio = $subtotal > 0 ? 6.99 : 0;
        $iva = 0;
        $total = $subtotal + $envio;

        return [
            "subtotal" => $subtotal,
            "envio" => $envio,
            "iva" => $iva,
            "total" => $total
        ];
    }
}
