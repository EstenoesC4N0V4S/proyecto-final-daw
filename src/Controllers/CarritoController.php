<?php

namespace ProyectoTFGRodrigo\Controllers;

use ProyectoTFGRodrigo\Config\Parameters;
use ProyectoTFGRodrigo\Models\ProductoModel;
use ProyectoTFGRodrigo\Models\CarritoModel;

class CarritoController
{
    /**
     * Añade un producto al carrito del usuario.
     */
    public function agregar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["id_usuario"])) {
            header("Location: " . Parameters::BASE_URL . "login");
            exit;
        }

        $idUsuario = (int) $_SESSION["id_usuario"];
        $idProducto = (int) ($_POST["id_producto"] ?? 0);

        if ($idProducto <= 0) {
            header("Location: " . Parameters::BASE_URL . "tienda");
            exit;
        }

        $productoModel = new ProductoModel();
        $producto = $productoModel->obtenerProductoPorId($idProducto);

        if (!$producto) {
            header("Location: " . Parameters::BASE_URL . "tienda");
            exit;
        }

        $carritoModel = new CarritoModel();
        $carritoModel->agregarProducto($idUsuario, $idProducto);

        $_SESSION["mensaje_carrito"] = "Producto añadido al carrito correctamente";

        header("Location: " . Parameters::BASE_URL . "tienda");
        exit;
    }

    /**
     * Muestra el carrito del usuario.
     */
    public function ver()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["id_usuario"])) {
            header("Location: " . Parameters::BASE_URL . "login");
            exit;
        }

        $idUsuario = (int) $_SESSION["id_usuario"];

        $carritoModel = new CarritoModel();
        $carrito = $carritoModel->obtenerCarrito($idUsuario);

        require_once __DIR__ . "/../Views/carrito.php";
    }

    /**
     * Elimina un producto del carrito mediante AJAX.
     */
    public function eliminar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header("Content-Type: application/json");

        if (!isset($_SESSION["id_usuario"])) {
            echo json_encode([
                "ok" => false,
                "mensaje" => "Usuario no autenticado"
            ]);
            return;
        }

        $idUsuario = (int) $_SESSION["id_usuario"];
        $idProducto = (int) ($_POST["id_producto"] ?? 0);

        $carritoModel = new CarritoModel();

        if ($idProducto > 0) {
            $carritoModel->eliminarProducto($idUsuario, $idProducto);
        }

        $totales = $carritoModel->calcularTotales($idUsuario);

        echo json_encode([
            "ok" => true,
            "subtotal" => $totales["subtotal"],
            "envio" => $totales["envio"],
            "iva" => $totales["iva"],
            "total" => $totales["total"]
        ]);
    }

    /**
     * Actualiza la cantidad de un producto mediante AJAX.
     */
    public function actualizarCantidad()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header("Content-Type: application/json");

        if (!isset($_SESSION["id_usuario"])) {
            echo json_encode([
                "ok" => false,
                "mensaje" => "Usuario no autenticado"
            ]);
            return;
        }

        $idUsuario = (int) $_SESSION["id_usuario"];
        $idProducto = (int) ($_POST["id_producto"] ?? 0);
        $cantidad = (int) ($_POST["cantidad"] ?? 0);

        if ($idProducto <= 0 || $cantidad < 1) {
            echo json_encode([
                "ok" => false,
                "mensaje" => "Datos inválidos"
            ]);
            return;
        }

        $carritoModel = new CarritoModel();
        $carritoModel->actualizarCantidad($idUsuario, $idProducto, $cantidad);

        $totales = $carritoModel->calcularTotales($idUsuario);

        echo json_encode([
            "ok" => true,
            "subtotal" => $totales["subtotal"],
            "envio" => $totales["envio"],
            "iva" => $totales["iva"],
            "total" => $totales["total"]
        ]);
    }
}