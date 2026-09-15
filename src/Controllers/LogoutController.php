<?php

namespace ProyectoTFGRodrigo\Controllers;

use ProyectoTFGRodrigo\Config\Parameters;

class LogoutController
{
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        session_destroy();
        
        header("Location: " . Parameters::BASE_URL . "login");
        exit;
    }
}