<?php

date_default_timezone_set("America/Mexico_City");
include_once("../_tcpdf_5.0.002/code128.php");
class PDF extends PDF_Code128 {
    function Header() {        
    }
}

$pdf = new PDF('L', 'mm', 'ETIQUETA');

$pdf->SetTitle("ETIQUETAS", false);
$pdf->SetAuthor("Poder Judicial del Estado de México", false);
$pdf->SetSubject("Etiquetas Para Inventario", false);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 0);

$pdf->SetXY(0, 0);
@$pdf->Image("../../../vistas/img/escudos/logo-EM-30.jpg", 1, 1, 5, 5);

$pdf->SetXY(15, 0);
$pdf->SetFontSize(7);
$pdf->MultiCell(45, 3, htmlspecialchars("PODER JUDICIAL "), 0, 'L', false);


$pdf->MultiCell(52, 0, htmlspecialchars("___________________________________"), 0, 'L', false, 0, 0, 2.5);

$pdf->MultiCell(52, 0, htmlspecialchars("___________________________________"), 0, 'L', false, 0, 0, 3);
    
$pdf->SetXY(0, 6.7);
$pdf->SetFontSize(5);
$pdf->MultiCell(45, 3, htmlspecialchars("MODULO CUATRO PLAZAS"), 0, 'L', false);

$pdf->SetXY(0, 8.7);
$pdf->SetFontSize(5);
$pdf->MultiCell(45, 3, htmlspecialchars("MOD 4 PERSONAS"), 0, 'L', false);

$pdf->SetXY(30, 6.7);
$pdf->SetFontSize(5);
$pdf->MultiCell(45, 3, htmlspecialchars("RIVIERA"), 0, 'L', false);

$pdf->SetXY(30, 8.7);
$pdf->SetFontSize(5);
$pdf->MultiCell(45, 3, htmlspecialchars("MOD. 4 PERSONAS"), 0, 'L', false);

$pdf->SetXY(44, 0);
@$pdf->Image("../../../vistas/img/escudos/logo-PJ-30.jpg", 44, 1, 5, 5);

$pdf->SetXY(17, 23);

$numero = "138882";
$pdf->Code128(7, 13, $numero, 37, 8);
$pdf->SetFontSize(9);
$pdf->SetXY(10, 21);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(0, 0, $numero, 0, 0, 'C', true);

$pdf->Output();
?>

