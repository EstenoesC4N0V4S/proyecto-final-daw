<?php

namespace ProyectoTFGRodrigo\Controllers;

use ProyectoTFGRodrigo\Models\CarreraModel;

class ExcelController
{
    public function exportarTiemposDuelo()
    {
        $carreraModel = new CarreraModel();
        $carreras = $carreraModel->obtenerCarreras();

        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=tiempos_duelo.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "\xEF\xBB\xBF";
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                    font-family: Arial, sans-serif;
                }

                th {
                    background: #111111;
                    color: #ffffff;
                    padding: 12px;
                    border: 1px solid #222222;
                    text-align: left;
                    font-weight: 700;
                }

                td {
                    padding: 10px;
                    border: 1px solid #d9d9d9;
                    color: #151515;
                }

                .titulo {
                    background: #e10600;
                    color: #ffffff;
                    font-size: 18px;
                    font-weight: 700;
                    text-align: center;
                }

                .ferrari { background: #e10600; color: #ffffff; font-weight: 700; }
                .red-bull { background: #0b1f5e; color: #ffffff; font-weight: 700; }
                .mercedes { background: #00d2be; color: #101010; font-weight: 700; }
                .aston-martin { background: #006f62; color: #ffffff; font-weight: 700; }
                .sin-equipo { background: #eeeeee; color: #222222; font-weight: 700; }
            </style>
        </head>
        <body>
            <table>
                <tr>
                    <td class="titulo" colspan="4">Tabla de tiempos - Duelo</td>
                </tr>
                <tr>
                    <th>Circuito</th>
                    <th>Coche ganador</th>
                    <th>Tiempo</th>
                    <th>Fecha</th>
                </tr>

                <?php if (!empty($carreras)): ?>
                    <?php foreach ($carreras as $carrera): ?>
                        <?php $nombreCoche = $carrera["nombre_coche"] ?? "Coche eliminado"; ?>
                        <tr>
                            <td><?= htmlspecialchars($carrera["nombre_circuito"], ENT_QUOTES, "UTF-8") ?></td>
                            <td class="<?= $this->claseEquipoExcel($nombreCoche) ?>">
                                <?= htmlspecialchars($nombreCoche, ENT_QUOTES, "UTF-8") ?>
                            </td>
                            <td><?= $this->formatearTiempoExcel((float) $carrera["tiempo_ganador"]) ?></td>
                            <td><?= htmlspecialchars($carrera["fecha_carrera"], ENT_QUOTES, "UTF-8") ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay tiempos guardados.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </body>
        </html>
        <?php
    }

    private function formatearTiempoExcel(float $segundosTotales): string
    {
        $minutos = floor($segundosTotales / 60);
        $segundos = $segundosTotales - ($minutos * 60);

        return $minutos . ":" . str_pad(number_format($segundos, 3, ".", ""), 6, "0", STR_PAD_LEFT);
    }

    private function claseEquipoExcel(string $nombreCoche): string
    {
        $nombre = strtolower($nombreCoche);

        if (strpos($nombre, "ferrari") !== false) {
            return "ferrari";
        }

        if (strpos($nombre, "red bull") !== false) {
            return "red-bull";
        }

        if (strpos($nombre, "mercedes") !== false) {
            return "mercedes";
        }

        if (strpos($nombre, "aston") !== false) {
            return "aston-martin";
        }

        return "sin-equipo";
    }
}
