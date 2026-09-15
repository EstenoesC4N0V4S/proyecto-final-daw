<?php

require_once __DIR__ . "/vendor/autoload.php";

use ProyectoTFGRodrigo\Config\Parameters;

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener acción
$accion = $_GET["accion"] ?? "home";

/*
|----------------|
| Rutas públicas |
|----------------|
*/
$rutasPublicas = [
    "login",
    "procesarLogin",
    "registro",
    "procesarRegistro"
];

/*
|-------------------|
| Control de acceso |
|-------------------|
*/
if (!isset($_SESSION["id_usuario"]) && !in_array($accion, $rutasPublicas, true)) {
    header("Location: " . Parameters::BASE_URL . "login");
    exit;
}

/*
|--------|
| Router |
|--------|
*/
switch ($accion) {

    /*
    |-------------------|
    |   LOGIN / LOGOUT  |
    |-------------------|
    */

    case "login":

        if (isset($_SESSION["id_usuario"])) {
            header("Location: " . Parameters::BASE_URL . "home");
            exit;
        }

        require_once __DIR__ . "/src/Views/login.php";
        break;

    case "procesarLogin":
        $controller = new \ProyectoTFGRodrigo\Controllers\UsuarioController();
        $controller->procesarLogin();
        break;

    case "logout":
        $controller = new \ProyectoTFGRodrigo\Controllers\LogoutController();
        $controller->logout();
        break;

    case "registro":
        require_once __DIR__ . "/src/Views/registro.php";
        break;

    case "procesarRegistro":
        $controller = new \ProyectoTFGRodrigo\Controllers\UsuarioController();
        $controller->procesarRegistro();
        break;

    /*
    |-----------------|
    | Páginas privadas|
    |-----------------|
    */

    case "home":
        require_once __DIR__ . "/src/Views/home.php";
        break;

    case "setup":
        $controller = new \ProyectoTFGRodrigo\Controllers\SetupController();
        $controller->ver();
        break;

    case "crearSetup":
        $controller = new \ProyectoTFGRodrigo\Controllers\SetupController();
        $controller->crear();
        break;

    case "eliminarSetup":
        $controller = new \ProyectoTFGRodrigo\Controllers\SetupController();
        $controller->eliminar();
        break;

    case "duelo":
        $controller = new \ProyectoTFGRodrigo\Controllers\DueloController();
        $controller->ver();
        break;

    case "guardarCarrera":
        $controller = new \ProyectoTFGRodrigo\Controllers\DueloController();
        $controller->guardarCarrera();
        break;

    case "exportarTiemposExcel":
        $controller = new \ProyectoTFGRodrigo\Controllers\ExcelController();
        $controller->exportarTiemposDuelo();
        break;

    /* TIENDA Y CARRITO */

    case "tienda":
        $controller = new \ProyectoTFGRodrigo\Controllers\ProductoController();
        $controller->tienda();
        break;

    //Añadir al carrito
    case "agregarCarrito":
        $controller = new \ProyectoTFGRodrigo\Controllers\CarritoController();
        $controller->agregar();
        break;

    //Actualiza la cantidad de un producto en el carrito

    case "actualizarCantidadCarrito":
        $controller = new \ProyectoTFGRodrigo\Controllers\CarritoController();
        $controller->actualizarCantidad();
        break;

    //Elimina el carrito por
    case "eliminarCarrito":
        $controller = new \ProyectoTFGRodrigo\Controllers\CarritoController();
        $controller->eliminar();
        break;

    case "carrito":
        $controller = new \ProyectoTFGRodrigo\Controllers\CarritoController();
        $controller->ver();
        break;

    case "descargarResumenCompraPdf":
        $controller = new \ProyectoTFGRodrigo\Controllers\PdfController();
        $controller->resumenCompra();
        break;

    case "crearCircuito":

        if (($_SESSION["rol"] ?? "") !== "admin") {
            require_once __DIR__ . "/src/Views/errors/error403.php";
            break;
        }

        require_once __DIR__ . "/src/Views/admin/formCrearCircuito.php";
        break;

    case "guardarCircuito":
        $controller = new \ProyectoTFGRodrigo\Controllers\CircuitoController();
        $controller->guardar();
        break;

    /* Cambiar datos de usuario */

    case "datos":
        require_once __DIR__ . "/src/Views/cambiarDatos.php";
        break;

    case "guardarDatos":
        $controller = new \ProyectoTFGRodrigo\Controllers\UsuarioController();
        $controller->guardarDatos();
        break;

    case "guardarPassword":
        $controller = new \ProyectoTFGRodrigo\Controllers\UsuarioController();
        $controller->guardarPassword();
        break;

    /*
    |----------------|
    | Ruta no válida |
    |----------------|
    */

    default:
        require_once __DIR__ . "/src/Views/errors/error404.php";
        break;
}
