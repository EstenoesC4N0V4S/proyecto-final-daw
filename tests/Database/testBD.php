<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use ProyectoTFGRodrigo\Database\Conexion;

try {
    $conexion = Conexion::conectar();

    echo "✅ Conexión correcta a la base de datos";

} catch (\PDOException $e) {
    echo "❌ ERROR DE CONEXIÓN<br>";
    echo "Mensaje: " . $e->getMessage() . "<br>";
    echo "Código: " . $e->getCode();
}
