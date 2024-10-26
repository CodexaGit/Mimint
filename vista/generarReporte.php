<?php
require_once("../modelo/fpdf/fpdf.php");
require_once("../modelo/phpspreadsheet/vendor/autoload.php");
require_once("../modelo/ReporteModel.php");
require_once("../controlador/bd.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$tipo = $_GET['tipo'] ?? 'pdf';
$seccionReporte = $_GET['seccion'] ?? 'todoRegistros';

$modelo = new ReporteModel($conexion);

$data = $modelo->obtenerDatosReporte($seccionReporte);

$reporteView = new ReporteView();

if ($tipo === 'pdf') {
    $reporteView->generarPDF($data, $seccionReporte);
} elseif ($tipo === 'excel') {
    $reporteView->generarExcel($data, $seccionReporte);
} else {
    throw new Exception('Tipo de reporte no soportado.');
}
?>