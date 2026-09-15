<?php

spl_autoload_register(function ($clase) {
    $namespace = "ProyectoTFGRodrigo\\";

    if (strpos($clase, $namespace) !== 0) {
        return;
    }

    // Quitar el namespace principal
    $ruta = substr($clase, strlen($namespace));

    // Cambiar "\" por "/"
    $ruta = str_replace("\\", DIRECTORY_SEPARATOR, $ruta);

    // Ruta final al archivo
    $archivo = dirname(__DIR__) . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . $ruta . ".php";

    if (file_exists($archivo)) {
        require_once $archivo;
    }
});
