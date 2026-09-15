<?php

namespace ProyectoTFGRodrigo\Controllers;

use ProyectoTFGRodrigo\Config\Parameters;
use ProyectoTFGRodrigo\Models\UsuarioModel;

class UsuarioController
{
    public function procesarLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        if ($email === "" || $password === "") {
            $_SESSION["error_login"] = "campos_vacios";
            header("Location: " . Parameters::BASE_URL . "login");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error_login"] = "email_invalido";
            header("Location: " . Parameters::BASE_URL . "login");
            exit;
        }

        $model = new UsuarioModel();
        $usuario = $model->iniciarSesion($email, $password);

        if (!$usuario) {
            $_SESSION["error_login"] = "credenciales";
            header("Location: " . Parameters::BASE_URL . "login");
            exit;
        }

        session_regenerate_id(true);

        $_SESSION["id_usuario"] = $usuario["id_usuario"];
        $_SESSION["nombre_usuario"] = $usuario["nombre_usuario"];
        $_SESSION["correo"] = $usuario["correo"];
        $_SESSION["rol"] = $usuario["rol"];

        if (!empty($_POST["recordar"])) {
            setcookie("usuario_recordado", $usuario["correo"], time() + (86400 * 30), "/");
        } else {
            setcookie("usuario_recordado", "", time() - 3600, "/");
        }

        header("Location: " . Parameters::BASE_URL . "home");
        exit;
    }

    public function procesarRegistro()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $nombre = trim($_POST["nombre_usuario"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $password = $_POST["password"] ?? "";
        $passwordConfirm = $_POST["password_confirm"] ?? "";

        $_SESSION["old_registro"] = [
            "nombre_usuario" => $nombre,
            "correo" => $correo
        ];

        if ($nombre === "" || $correo === "" || $password === "" || $passwordConfirm === "") {
            $_SESSION["error_registro"] = "campos_vacios";
            header("Location: " . Parameters::BASE_URL . "registro");
            exit;
        }

        if (strlen($nombre) < 3) {
            $_SESSION["error_registro"] = "nombre_corto";
            header("Location: " . Parameters::BASE_URL . "registro");
            exit;
        }

        if (strlen($nombre) > 50) {
            $_SESSION["error_registro"] = "nombre_largo";
            header("Location: " . Parameters::BASE_URL . "registro");
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error_registro"] = "email_invalido";
            header("Location: " . Parameters::BASE_URL . "registro");
            exit;
        }

        if (!$this->passwordSegura($password)) {
            $_SESSION["error_registro"] = "password_insegura";
            header("Location: " . Parameters::BASE_URL . "registro");
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION["error_registro"] = "password_no_coincide";
            header("Location: " . Parameters::BASE_URL . "registro");
            exit;
        }

        $model = new UsuarioModel();

        if ($model->existeCorreo($correo)) {
            $_SESSION["error_registro"] = "correo_existe";
            header("Location: " . Parameters::BASE_URL . "registro");
            exit;
        }

        $creado = $model->registrarUsuario($nombre, $correo, $password);

        if (!$creado) {
            $_SESSION["error_registro"] = "error_general";
            header("Location: " . Parameters::BASE_URL . "registro");
            exit;
        }

        unset($_SESSION["old_registro"]);

        $_SESSION["ok_registro"] = "cuenta_creada";
        header("Location: " . Parameters::BASE_URL . "login");
        exit;
    }

    private function passwordSegura(string $password): bool
    {
        return strlen($password) >= 6
            && preg_match('/[A-Z]/', $password)
            && preg_match('/[a-z]/', $password)
            && preg_match('/[0-9]/', $password)
            && preg_match('/[^A-Za-z0-9]/', $password);
    }

    public function guardarDatos()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["id_usuario"])) {
            header("Location: " . Parameters::BASE_URL . "login");
            exit;
        }

        $idUsuario = (int) $_SESSION["id_usuario"];
        $correoActual = $_SESSION["correo"] ?? "";

        $nombre = trim($_POST["nombre_usuario"] ?? "");
        $correoNuevo = trim($_POST["correo"] ?? "");
        $correoConfirmar = trim($_POST["correo_confirmar"] ?? "");

        if ($nombre === "") {
            $_SESSION["error_datos"] = "El nombre no puede estar vacío.";
            header("Location: " . Parameters::BASE_URL . "datos");
            exit;
        }

        $model = new UsuarioModel();

        $quiereCambiarCorreo = $correoNuevo !== "" || $correoConfirmar !== "";

        if ($quiereCambiarCorreo) {
            if ($correoNuevo === "" || $correoConfirmar === "") {
                $_SESSION["error_datos"] = "Debes introducir y confirmar el nuevo correo electrónico.";
                header("Location: " . Parameters::BASE_URL . "datos");
                exit;
            }

            if (!filter_var($correoNuevo, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["error_datos"] = "El nuevo correo introducido no es válido.";
                header("Location: " . Parameters::BASE_URL . "datos");
                exit;
            }

            if ($correoNuevo !== $correoConfirmar) {
                $_SESSION["error_datos"] = "Los correos electrónicos no coinciden.";
                header("Location: " . Parameters::BASE_URL . "datos");
                exit;
            }

            if ($correoNuevo === $correoActual) {
                $_SESSION["error_datos"] = "El nuevo correo no puede ser igual al correo actual.";
                header("Location: " . Parameters::BASE_URL . "datos");
                exit;
            }

            if ($model->existeCorreoOtroUsuario($correoNuevo, $idUsuario)) {
                $_SESSION["error_datos"] = "Ese correo ya está registrado por otro usuario.";
                header("Location: " . Parameters::BASE_URL . "datos");
                exit;
            }

            $correoFinal = $correoNuevo;
        } else {
            $correoFinal = $correoActual;
        }

        if (!$model->actualizarDatosUsuario($idUsuario, $nombre, $correoFinal)) {
            $_SESSION["error_datos"] = "No se han podido actualizar los datos.";
            header("Location: " . Parameters::BASE_URL . "datos");
            exit;
        }

        $_SESSION["nombre_usuario"] = $nombre;
        $_SESSION["correo"] = $correoFinal;
        $_SESSION["ok_datos"] = true;

        header("Location: " . Parameters::BASE_URL . "datos");
        exit;
    }

    public function guardarPassword()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["id_usuario"])) {
            header("Location: " . Parameters::BASE_URL . "login");
            exit;
        }

        $idUsuario = (int) $_SESSION["id_usuario"];

        $passwordActual = trim($_POST["password_actual"] ?? "");
        $passwordNueva = trim($_POST["password_nueva"] ?? "");
        $passwordConfirmar = trim($_POST["password_confirmar"] ?? "");

        if ($passwordActual === "") {
            $_SESSION["error_password"] = "Debes introducir tu contraseña actual.";
            header("Location: " . Parameters::BASE_URL . "datos");
            exit;
        }

        if ($passwordNueva === "") {
            $_SESSION["error_password"] = "Debes introducir una nueva contraseña.";
            header("Location: " . Parameters::BASE_URL . "datos");
            exit;
        }

        if ($passwordConfirmar === "") {
            $_SESSION["error_password"] = "Debes confirmar la nueva contraseña.";
            header("Location: " . Parameters::BASE_URL . "datos");
            exit;
        }

        if (!$this->passwordSegura($passwordNueva)) {
            $_SESSION["error_password"] = "La nueva contraseña no cumple los requisitos de seguridad.";
            header("Location: " . Parameters::BASE_URL . "datos");
            exit;
        }

        if ($passwordNueva !== $passwordConfirmar) {
            $_SESSION["error_password"] = "Las contraseñas nuevas no coinciden.";
            header("Location: " . Parameters::BASE_URL . "datos");
            exit;
        }

        $model = new UsuarioModel();
        $usuario = $model->getUsuarioById($idUsuario);

        if (!$usuario || !password_verify($passwordActual, $usuario["password"])) {
            $_SESSION["error_password"] = "La contraseña actual no coincide con tu contraseña.";
            header("Location: " . Parameters::BASE_URL . "datos");
            exit;
        }

        if (!$model->actualizarPassword($idUsuario, $passwordNueva)) {
            $_SESSION["error_password"] = "No se ha podido actualizar la contraseña.";
            header("Location: " . Parameters::BASE_URL . "datos");
            exit;
        }

        $_SESSION["ok_password"] = true;

        header("Location: " . Parameters::BASE_URL . "datos");
        exit;
    }
}