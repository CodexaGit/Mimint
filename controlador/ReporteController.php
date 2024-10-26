<?php
require_once('../modelo/ReporteModel.php'); // Asegúrate de que la ruta sea correcta
require_once('../vista/ReporteView.php');
require_once('../modelo/phpspreadsheet/vendor/autoload.php'); // Autoload de Composer
require_once('bd.php'); // Incluir la conexión a la base de datos

class ReporteController {
    private $model;
    private $view;

    public function __construct($conexion) {
        $this->model = new ReporteModel($conexion);
        $this->view = new ReporteView();
    }

    public function generarReporte() {
        if (isset($_GET['seccionReporte']) && isset($_GET['formatoReporte'])) {
            $seccionReporte = $_GET['seccionReporte'];
            $formatoReporte = $_GET['formatoReporte'];

            $data = $this->model->obtenerDatosReporte($seccionReporte);

            if ($data !== false && !empty($data)) {
                ob_start(); // Iniciar el almacenamiento en búfer de salida
                if ($formatoReporte === 'pdf') {
                    $this->view->generarPDF($data, $seccionReporte);
                } elseif ($formatoReporte === 'excel') {
                    $this->view->generarExcel($data, $seccionReporte);
                } else {
                    echo 'Formato no soportado';
                    exit;
                }
                ob_end_flush(); // Enviar el contenido del búfer de salida y deshabilitar el almacenamiento en búfer de salida
            } else {
                echo 'NO SE PUDO REALIZAR EL REPORTE';
                exit;
            }
        } else {
            echo 'No se recibieron los parámetros esperados';
            exit;
        }
    }
}

$controller = new ReporteController($conexion);
$controller->generarReporte();
?>