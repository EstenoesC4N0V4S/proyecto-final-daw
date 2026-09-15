<?php

namespace ProyectoTFGRodrigo\Database;

use PDO;
use PDOException;
use ProyectoTFGRodrigo\Config\ConfigBD;

class Conexion
{
    public static function conectar()
    {
        try {
            $conexion = new PDO(
                'mysql:host=' . ConfigBD::$SERVER_NAME .
                ';port=' . ConfigBD::$SERVER_PORT .
                ';dbname=' . ConfigBD::$DB_NAME .
                ';charset=utf8mb4',
                ConfigBD::$USER_BD,
                ConfigBD::$PASSWORD_USER
            );

            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conexion;

        } catch (PDOException $e) {
            throw $e;
        }
    }
}
