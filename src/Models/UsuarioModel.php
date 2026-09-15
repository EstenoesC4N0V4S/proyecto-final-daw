<?php

namespace ProyectoTFGRodrigo\Models;

use PDO;

class UsuarioModel extends Model
{
    public function iniciarSesion(string $email, string $password): ?array
    {
        $sql = "SELECT * FROM usuario WHERE correo = :correo LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(":correo", $email, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || !password_verify($password, $usuario["password"])) { // Verifica si el usuario existe y si la contraseña (hash) es correcta
            return null;
        }

        return $usuario;
    }

    public function existeCorreo(string $correo): bool
    {
        $sql = "SELECT id_usuario FROM usuario WHERE correo = :correo LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
        $stmt->execute();

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarUsuario($nombre, $correo, $password)
    {
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuario (nombre_usuario, correo, password, rol) 
                VALUES (:nombre, :correo, :password, :rol)";

            $stmt = $this->conexion->prepare($sql);

            return $stmt->execute([//Se insertan los datos del nuevo usuario
                ":nombre" => $nombre,
                ":correo" => $correo,
                ":password" => $passwordHash,
                ":rol" => "user"//Inserta el usuario como usuario normal 
            ]);

        } catch (\PDOException $e) {
            return false;
        }
    }

    /* Funciones para actualizar datos de usuario */

    public function getUsuarioById(int $idUsuario): ?array
    {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :id_usuario LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(":id_usuario", $idUsuario, PDO::PARAM_INT);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ?: null;
    }

    public function existeCorreoOtroUsuario(string $correo, int $idUsuario): bool
    {
        $sql = "SELECT id_usuario 
            FROM usuario 
            WHERE correo = :correo 
            AND id_usuario != :id_usuario 
            LIMIT 1";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            ":correo" => $correo,
            ":id_usuario" => $idUsuario
        ]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarDatosUsuario(int $idUsuario, string $nombre, string $correo): bool
    {
        $sql = "UPDATE usuario 
            SET nombre_usuario = :nombre, correo = :correo 
            WHERE id_usuario = :id_usuario";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":nombre" => $nombre,
            ":correo" => $correo,
            ":id_usuario" => $idUsuario
        ]);
    }

    public function actualizarPassword(int $idUsuario, string $passwordNueva): bool
    {
        $passwordHash = password_hash($passwordNueva, PASSWORD_DEFAULT);

        $sql = "UPDATE usuario 
            SET password = :password 
            WHERE id_usuario = :id_usuario";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ":password" => $passwordHash,
            ":id_usuario" => $idUsuario
        ]);
    }
}