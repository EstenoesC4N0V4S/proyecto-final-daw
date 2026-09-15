<?php

namespace ProyectoTFGRodrigo\Controllers;

use ProyectoTFGRodrigo\Config\Parameters;
use ProyectoTFGRodrigo\Lib\FPDF;
use ProyectoTFGRodrigo\Models\CarritoModel;

class PdfController
{
    public function resumenCompra()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["id_usuario"])) {
            header("Location: " . Parameters::BASE_URL . "login");
            exit;
        }

        $carritoModel = new CarritoModel();
        $carrito = $carritoModel->obtenerCarrito((int) $_SESSION["id_usuario"]);
        $totales = $carritoModel->calcularTotales((int) $_SESSION["id_usuario"]);

        $pdf = new FPDF();
        $pdf->AddPage();

        $logoPath = __DIR__ . "/../../assets/img/logo_f1.png";

        // =========================
        // CABECERA
        // =========================
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 160, 12, 35);
        }

        $pdf->SetXY(18, 18);
        $pdf->SetTextColor(225, 6, 0);
        $pdf->SetFont("Arial", "B", 23);
        $pdf->Cell(120, 10, "F1 STORE", 0, 1);

        $pdf->SetX(18);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->SetFont("Arial", "B", 15);
        $pdf->Cell(120, 8, "Resumen de compra", 0, 1);

        $pdf->SetX(18);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->SetFont("Arial", "", 9);
        $pdf->Cell(120, 6, "Documento generado el " . date("d/m/Y H:i"), 0, 1);

        $pdf->Ln(14);

        // =========================
        // DATOS DEL CLIENTE
        // =========================
        $nombreUsuario = $_SESSION["nombre_usuario"] ?? $_SESSION["nombre"] ?? "Usuario registrado";
        $correoUsuario = $_SESSION["correo"] ?? $_SESSION["email"] ?? "No disponible";

        $pdf->SetX(18);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetDrawColor(225, 225, 225);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->SetFont("Arial", "B", 11);
        $pdf->Cell(174, 9, "Datos del cliente", 1, 1, "L", true);

        $pdf->SetX(18);
        $pdf->SetFont("Arial", "", 10);
        $pdf->Cell(87, 8, "Cliente: " . $this->limpiarTexto($nombreUsuario, 32), 1);
        $pdf->Cell(87, 8, "Correo: " . $this->limpiarTexto($correoUsuario, 34), 1, 1);

        $pdf->Ln(12);

        // =========================
        // PRODUCTOS
        // =========================
        $pdf->SetX(18);
        $pdf->SetTextColor(225, 6, 0);
        $pdf->SetFont("Arial", "B", 14);
        $pdf->Cell(174, 8, "Productos del carrito", 0, 1);

        $pdf->Ln(3);

        $pdf->SetX(18);
        $pdf->SetFillColor(18, 18, 20);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont("Arial", "B", 10);

        $pdf->Cell(82, 10, "Producto", 1, 0, "L", true);
        $pdf->Cell(22, 10, "Cant.", 1, 0, "C", true);
        $pdf->Cell(35, 10, "Precio", 1, 0, "R", true);
        $pdf->Cell(35, 10, "Subtotal", 1, 1, "R", true);

        $pdf->SetFont("Arial", "", 10);
        $pdf->SetTextColor(30, 30, 30);

        if (empty($carrito)) {
            $pdf->SetX(18);
            $pdf->Cell(174, 9, "No hay productos en el carrito.", 1, 1);
        }

        $fila = 0;

        foreach ($carrito as $item) {
            $cantidad = (int) $item["cantidad"];
            $precio = (float) $item["precio"];
            $subtotalProducto = $precio * $cantidad;

            $pdf->SetFillColor($fila % 2 === 0 ? 255 : 248, $fila % 2 === 0 ? 255 : 248, $fila % 2 === 0 ? 255 : 248);

            $pdf->SetX(18);
            $pdf->Cell(82, 9, $this->limpiarTexto($item["nombre"], 36), 1, 0, "L", true);
            $pdf->Cell(22, 9, (string) $cantidad, 1, 0, "C", true);
            $pdf->Cell(35, 9, $this->formatearPrecio($precio), 1, 0, "R", true);
            $pdf->Cell(35, 9, $this->formatearPrecio($subtotalProducto), 1, 1, "R", true);

            $fila++;
        }

        $pdf->Ln(12);

        // =========================
        // TOTALES
        // =========================
        $pdf->SetX(87);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->SetFont("Arial", "B", 10);

        $pdf->Cell(70, 9, "Subtotal productos", 1, 0, "R", true);
        $pdf->Cell(35, 9, $this->formatearPrecio($totales["subtotal"]), 1, 1, "R", true);

        $pdf->SetX(87);
        $pdf->Cell(70, 9, "Gastos de envio", 1, 0, "R", true);
        $pdf->Cell(35, 9, $this->formatearPrecio($totales["envio"]), 1, 1, "R", true);

        $pdf->SetX(87);
        $pdf->SetFillColor(225, 6, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont("Arial", "B", 12);

        $pdf->Cell(50, 12, "TOTAL", 1, 0, "R", true);
        $pdf->Cell(55, 12, $this->formatearPrecio($totales["total"]), 1, 1, "R", true);

        $pdf->Ln(16);

        // =========================
        // ESTADO DEL PEDIDO
        // =========================
        $pdf->SetX(18);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->SetFont("Arial", "B", 11);
        $pdf->Cell(174, 9, "Estado del pedido", 1, 1, "L", true);

        $pdf->SetX(18);
        $pdf->SetFont("Arial", "", 10);
        $pdf->Cell(174, 8, "Pago simulado correctamente. Pedido pendiente de confirmacion final.", 1, 1);

        $pdf->Ln(18);

        // =========================
        // PIE
        // =========================
        $pdf->SetX(18);
        $pdf->SetTextColor(95, 95, 95);
        $pdf->SetFont("Arial", "I", 9);
        $pdf->Cell(174, 6, "F1 Setup Simulator & Store - Documento generado automaticamente", 0, 1, "C");

        $pdf->Output("I", "resumen_compra.pdf");
    }

    private function formatearPrecio(float $precio): string
    {
        return number_format($precio, 2, ",", ".") . " EUR";
    }

    private function limpiarTexto(string $texto, int $limite = 42): string
    {
        $texto = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $texto);

        if (function_exists("mb_substr")) {
            return mb_substr($texto, 0, $limite);
        }

        return substr($texto, 0, $limite);
    }
}
