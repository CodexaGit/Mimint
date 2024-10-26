<?php
require_once("../modelo/fpdf/fpdf.php");
require_once("../modelo/phpspreadsheet/vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PDF extends FPDF {
    private $seccionReporte;

    public function setSeccionReporte($seccionReporte) {
        $this->seccionReporte = $seccionReporte;
    }

    function Header() {
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(4, 54, 74);
        $this->Cell(0, 10, $this->convertToIso('CODEXA MIMINT - ' . ucfirst($this->seccionReporte)), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(4, 54, 74);
        $this->Cell(0, 10, $this->convertToIso('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    /**
     * Añade una fila a la tabla
     *
     * @param array $data Array de datos para la fila
     * @param array $colWidths Anchos de las columnas
     */
    function Row($data, $colWidths) {
        // Calcular el número máximo de líneas de la fila
        $nb = 0;
        foreach($data as $i => $cell) {
            $nb = max($nb, $this->NbLines($colWidths[$i], $this->convertToIso($cell)));
        }
        $h = 6 * $nb; // Altura de la fila

        // Verificar si hay espacio suficiente para la fila, de lo contrario, añadir página
        $this->CheckPageBreak($h);

        // Guardar posición actual
        $x = $this->GetX();
        $y = $this->GetY();

        // Dibujar cada celda
        foreach($data as $i => $cell) {
            $w = $colWidths[$i];
            // Dibujar borde de la celda
            $this->Rect($x, $y, $w, $h);
            // Imprimir texto dentro de la celda
            $this->MultiCell($w, 6, $this->convertToIso($cell), 0, 'L');
            // Mover posición X a la siguiente celda
            $x += $w;
            // Restaurar posición Y
            $this->SetXY($x, $y);
        }

        // Mover cursor a la siguiente línea
        $this->Ln($h);
    }

    /**
     * Verifica si hay espacio suficiente para una nueva fila, y añade una nueva página si es necesario
     *
     * @param float $h Altura de la fila
     */
    function CheckPageBreak($h) {
        // Si la altura de la fila excede el espacio restante, añadir una nueva página
        if($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            // Reimprimir encabezado después de un salto de página
            $this->SetFont('Arial', 'B', 12);
            $this->SetFillColor(192, 242, 255);
            $this->SetTextColor(0);
            $this->SetDrawColor(9, 129, 126);
            $this->SetLineWidth(.3);

            // Definir anchos de columnas (reutilizar lógica existente)
            // Asegúrate de que esta lógica sea consistente con el método Table
            // Podrías considerar pasar $colWidths como parámetro o almacenarlo como propiedad
            // Para simplificar, supondré que los anchos de columna ya están definidos
            // Reescribe esta sección según tus necesidades específicas
        }
    }

    /**
     * Método para imprimir una tabla completa
     *
     * @param array $header Encabezados de la tabla
     * @param array $data Datos de la tabla
     */
    function Table($header, $data) {
        // Configuración de estilos
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(192, 242, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(9, 129, 126);
        $this->SetLineWidth(.3);

        // Definir anchos de columnas
        $colWidths = [];
        foreach ($header as $col) {
            if ($col == 'dia' || $col == 'horainicio' || $col == 'horafin') {
                $colWidths[] = 20;
            } elseif ($col == 'email') {
                $colWidths[] = 60;
            } else {
                $colWidths[] = 40;
            }
        }

        // Calcular ancho total de la tabla
        $tableWidth = array_sum($colWidths);

        // Calcular posición X para centrar la tabla
        $startX = ($this->w - $tableWidth) / 2;

        // Imprimir encabezado
        $this->SetXY($startX, $this->GetY());
        foreach ($header as $i => $col) {
            $colName = ($col == 'fechaModificacion') ? 'modificacion' : $col;
            $this->Cell($colWidths[$i], 7, $this->convertToIso(strtoupper($colName)), 1, 0, 'C', true); // Convertir a mayúsculas
        }
        $this->Ln();

        // Configuración para datos
        $this->SetFont('Arial', '', 10);

        // Recorrer cada fila de datos
        foreach ($data as $row) {
            // Calcular el número máximo de líneas en la fila
            $nb = 0;
            foreach ($header as $i => $col) {
                $nb = max($nb, $this->NbLines($colWidths[$i], isset($row[$col]) ? $row[$col] : ''));
            }
            $h = 6 * $nb; // Altura de la fila

            // Verificar salto de página
            if ($this->GetY() + $h > $this->PageBreakTrigger) {
                $this->AddPage();
                // Reimprimir encabezado en nueva página
                $this->SetXY($startX, $this->GetY());
                foreach ($header as $i => $col) {
                    $colName = ($col == 'fecha modificacion') ? 'modificacion' : $col;
                    $this->Cell($colWidths[$i], 7, $this->convertToIso(strtoupper($colName)), 1, 0, 'C', true); // Convertir a mayúsculas
                }
                $this->Ln();
            }

            // Guardar posición actual
            $x = $startX;
            $y = $this->GetY();

            // Dibujar cada celda de la fila
            foreach ($header as $i => $col) {
                $cellText = isset($row[$col]) ? $row[$col] : '';
                // Dibujar borde de la celda
                $this->Rect($x, $y, $colWidths[$i], $h);
                // Imprimir texto dentro de la celda, pero sin centrar verticalmente si hay múltiples líneas
                $this->SetXY($x, $y);  // No ajustar la posición Y para centrar verticalmente, ya que podría causar desbordamiento
                $this->MultiCell($colWidths[$i], 6, $this->convertToIso($cellText), 0, 'C'); // 'C' para alinear horizontalmente
                // Actualizar posición X para la siguiente celda
                $x += $colWidths[$i];
            }

            // Mover cursor a la siguiente línea
            $this->SetXY($startX, $y + $h);
        }
    }

    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if($nb > 0 && $s[$nb-1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while($i < $nb)
        {
            $c = $s[$i];
            if($c == "\n")
            {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if($c == ' ')
                $sep = $i;
            if(!isset($cw[$c]))
                $cw[$c] = $cw[' '] ?? 250; // Valor predeterminado si el carácter no está definido
            $l += $cw[$c];
            if($l > $wmax)
            {
                if($sep == -1)
                {
                    if($i == $j)
                        $i++;
                }
                else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    /**
     * Convierte texto de UTF-8 a ISO-8859-1
     *
     * @param string $text Texto en UTF-8
     * @return string Texto en ISO-8859-1
     */
    public function convertToIso($text) {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
    }
}

class ReporteView {
    public function generarPDF($data, $seccionReporte) {
        $pdf = new PDF();
        $pdf->setSeccionReporte($seccionReporte);
        $pdf->AddFont('Arial','','Arial.php');
        $pdf->AddFont('Arial','B','Arial-Bold.php');
        $pdf->AddFont('Arial','I','Arial-Italic.php');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        if ($seccionReporte == 'todoRegistros') {
            foreach ($data as $section => $sectionData) {
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, $pdf->convertToIso(ucfirst($section)), 0, 1, 'L');
                $pdf->Ln(5);

                if (!empty($sectionData)) {
                    $header = array_keys($sectionData[0]);

                    // Asegurar que todas las filas tengan las mismas claves
                    $sectionData = array_map(function($row) use ($header) {
                        return array_merge(array_fill_keys($header, ''), $row);
                    }, $sectionData);

                    $pdf->Table($header, $sectionData);
                } else {
                    $pdf->Cell(0, 10, $pdf->convertToIso('No hay datos disponibles para esta sección.'), 0, 1, 'C');
                }

                $pdf->AddPage();
            }
        } else {
            if (!empty($data)) {
                $header = array_keys($data[0]);

                // Asegurar que todas las filas tengan las mismas claves
                $data = array_map(function($row) use ($header) {
                    return array_merge(array_fill_keys($header, ''), $row);
                }, $data);

                $pdf->Table($header, $data);
            } else {
                $pdf->Cell(0, 10, $pdf->convertToIso('No hay datos disponibles para esta sección.'), 0, 1, 'C');
            }
        }

        $pdf->Output('I', 'reporte_' . $seccionReporte . '.pdf');
    }

    public function generarExcel($data, $seccionReporte) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $row = 1;
        if ($seccionReporte == 'todoRegistros') {
            foreach ($data as $section => $sectionData) {
                $sheet->setCellValue('A' . $row, strtoupper($section));
                $row++;
                if (!empty($sectionData)) {
                    $header = array_keys($sectionData[0]);
                    $col = 'A';
                    foreach ($header as $head) {
                        $headName = ($head == 'fecha modificacion') ? 'modificacion' : $head;
                        $sheet->setCellValue($col . $row, strtoupper($headName)); // Convertir a mayúsculas
                        $col++;
                    }
                    $row++;
                    foreach ($sectionData as $item) {
                        $col = 'A';
                        foreach ($item as $value) {
                            $sheet->setCellValue($col . $row, $value);
                            $col++;
                        }
                        $row++;
                    }
                } else {
                    $sheet->setCellValue('A' . $row, 'No hay datos disponibles para esta sección.');
                    $row++;
                }
                $row++;
            }
        } else {
            if (!empty($data)) {
                $header = array_keys($data[0]);
                $col = 'A';
                foreach ($header as $head) {
                    $headName = ($head == 'fecha modificacion') ? 'modificacion' : $head;
                    $sheet->setCellValue($col . $row, strtoupper($headName)); // Convertir a mayúsculas
                    $col++;
                }
                $row++;
                foreach ($data as $item) {
                    $col = 'A';
                    foreach ($item as $value) {
                        $sheet->setCellValue($col . $row, $value);
                        $col++;
                    }
                    $row++;
                }
            } else {
                $sheet->setCellValue('A' . $row, 'No hay datos disponibles para esta sección.');
            }
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_' . $seccionReporte . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
?>