<?php
namespace ProyectoTFGRodrigo\Models;

use ProyectoTFGRodrigo\Database\Conexion;

class Model

{
    protected $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();

        
    }
}
?>