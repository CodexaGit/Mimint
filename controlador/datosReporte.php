<?php
require('bd.php');
require_once("../modelo/fpdf/fpdf.php");

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(4, 54, 74); // Color de texto del encabezado
        $this->Cell(0, 10, 'CODEXA MIMINT - ' . ucfirst($GLOBALS['seccionReporte']), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(4, 54, 74); // Color de texto del pie de página
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function DataCard($data)
    {
        $cardWidth = 150; // Ancho de la tarjeta
        $cardHeight = 85; // Alto de la tarjeta

        $this->SetFillColor(151, 254, 237); // Color de fondo
        $this->SetDrawColor(100, 204, 197); // Color de borde
        $this->RoundedRect($this->GetX(), $this->GetY(), $cardWidth, $cardHeight, 3.5, 'DF');
        
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(4, 54, 74); // Color de texto
        
        $y = $this->GetY();
        $isFirst = true;
        foreach ($data as $key => $value) {
            $this->SetXY($this->GetX(), $y);
            if ($isFirst) {
                $this->SetFont('Arial', 'B', 14); // Fuente más grande para el primer dato
                $this->Cell($cardWidth, 14, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ucfirst($key) . ': ' . $value), 0, 2, 'L');
                $y += 14; // Ajusta el incremento según el alto de la celda
                $isFirst = false;
                $this->SetFont('Arial', 'B', 10); // Restaurar la fuente para los datos restantes
            } else {
                $this->Cell($cardWidth, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', ucfirst($key) . ': ' . $value), 0, 2, 'L');
                $y += 10; // Ajusta el incremento según el alto de la tarjeta
            }
        }
    }

    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
}

$data = array();

if (isset($_POST['seccionReporte'])) {
    $seccionReporte = $_POST['seccionReporte'];

    $consulta = "SELECT * FROM $seccionReporte";
    $resultado = $conexion->query($consulta);

    if ($resultado) {
        while ($row = $resultado->fetch_assoc()) {
            $encodedRow = array_map(function($value) {
                return $value !== null ? mb_convert_encoding($value, 'UTF-8', 'auto') : '';
            }, $row);
            $data[] = $encodedRow;
        }
    } else {
        ob_start();
        echo 'NO SE PUDO REALIZAR EL REPORTE';
        ob_end_flush();
        exit;
    }
} else {
    ob_start();
    echo 'No se recibió el parámetro esperado';
    ob_end_flush();
    exit;
}
$conexion->close();

// Generar PDF
if (!empty($data)) {
    $pdf = new PDF();
    $pdf->AddFont('Arial','','Arial.php');
    $pdf->AddFont('Arial','B','Arial-Bold.php');
    $pdf->AddFont('Arial','I','Arial-Italic.php');
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetFont('Arial', '', 10);

    foreach($data as $item) {
        // Centrar la tarjeta en la página
        $pdf->SetX(($pdf->GetPageWidth() - 150) / 2); // Ajusta el ancho de la tarjeta aquí también
        $pdf->DataCard($item);
        $pdf->Ln(90); // Ajusta el espacio entre tarjetas según el alto de la tarjeta

        // Nueva página si llegamos al final
        if ($pdf->GetY() > 250) {
            $pdf->AddPage();
        }
    }

    $pdf->Output('I', 'reporte_' . $seccionReporte . '.pdf');
} else {
    ob_start();
    echo 'No hay datos para generar el reporte';
    ob_end_flush();
}
?>