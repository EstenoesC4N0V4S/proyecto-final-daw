<?php

namespace ProyectoTFGRodrigo\Controllers;

use ProyectoTFGRodrigo\Models\ProductoModel;

class ProductoController
{
    /**
     * Carga los productos y los prepara para la vista
     */
    public function tienda()
    {
        $model = new ProductoModel();
        $productosBD = $model->obtenerProductos();

        $productos = [];

        foreach ($productosBD as $p) {

            if (empty($p["nombre"]) || empty($p["descripcion"]) || empty($p["modelo_3d"])) continue;
            if (!is_numeric($p["precio"])) continue;

            $precioLimpio = floor((float)$p["precio"] * 100) / 100;

            $productos[] = [
                "id_producto" => (int)$p["id_producto"],
                "nombre" => htmlspecialchars($p["nombre"]),
                "descripcion" => htmlspecialchars($p["descripcion"]),
                "modelo_3d" => htmlspecialchars($p["modelo_3d"]),
                "precio" => number_format($precioLimpio, 2, ",", "."),

                // CAMPOS 3D DESDE BD, se descuadraba el diseño con valores muy altos, así que se les asignao un valor por defecto en la BBDD
                "escala_modelo" => htmlspecialchars($p["escala_modelo"] ?? "1 1 1"),
                "camera_orbit" => htmlspecialchars($p["camera_orbit"] ?? "40deg 70deg 62.5%"),
                "field_of_view" => htmlspecialchars($p["field_of_view"] ?? "28deg")
            ];
        }

        require_once __DIR__ . "/../Views/tienda.php";
    }

    
}