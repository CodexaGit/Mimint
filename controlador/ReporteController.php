<?php
require('../modelo/ReporteModel.php'); // Asegúrate de que la ruta sea correcta
require('../vista/ReporteView.php');
require('bd.php'); // Incluir la conexión a la base de datos

// Verificar si la clase ReporteModel está definida
if (!class_exists('ReporteModel')) {
    echo "La clase ReporteModel no está definida.";
    die();
}

class ReporteController {
    private $model;
    private $view;

    public function __construct($conexion) {
        $this->model = new ReporteModel($conexion);
        $this->view = new ReporteView();
    }

    public function generarReporte() {
        if (isset($_POST['seccionReporte'])) {
            $seccionReporte = $_POST['seccionReporte'];

            $data = $this->model->obtenerDatosReporte($seccionReporte);

            if ($data !== false && !empty($data)) {
                ob_start(); // Iniciar el almacenamiento en búfer de salida
                $this->view->generarPDF($data, $seccionReporte);
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