<?php

date_default_timezone_set("America/Mexico_City");
include_once("../modelos/dao/GenericDAO.Class.php");
include_once("../tribunal/pdf/_tcpdf_5.0.002/code128.php");
ini_set('memory_limit','-1');
ini_set('max_execution_time', 0);

class PDF extends PDF_Code128 {

    function Header() {
        
    }

}

$pdf = new PDF('L', 'mm', 'ETIQUETA');

$pdf->SetTitle("ETIQUETAS", false);
$pdf->SetAuthor("Poder Judicial del Estado de México", false);
$pdf->SetSubject("Etiquetas Para Inventario", false);

$pdf->AliasNbPages();

$idInventarios = $_GET["data"];
$posicion = $_GET["posicion"];

$genericoDao = new GenericDAO();
$sql = array(
    "campos" => "   
                tblinventarios.*
            ",
    "tablas" => "
                tblinventarios tblinventarios
            ",
    "where" => " 
                tblinventarios.idInventario IN (" . $idInventarios . ")
            "
);
$sqlSelect = array("tabla" => "", "d" => array(), "tmpSql" => $sql, "proveedor" => null);
$rs = $genericoDao->select($sqlSelect);
//var_dump($rs);
if ($rs["totalCount"] > 0) {
    $cont = 1;
    foreach ($rs["data"] as $key => $inventario) {
//        var_dump($key);
//        var_dump($inventario);
        if ($inventario["cveClasificadorBien"] == "1") {

            $sql1 = array(
                "campos" => "   
                        tblcbm.*,
                        tblinventarios.*
                    ",
                "tablas" => "
                        tblcbm tblcbm
                        INNER JOIN tblinventarios tblinventarios
                        ON tblinventarios.idReferencia  = tblcbm.idCbm
                    ",
                "where" => " 
                        tblcbm.idCbm = " . $inventario["idReferencia"] . " AND tblinventarios.idInventario =   " . $inventario["idInventario"] . "
                    "
            );
            $sqlSelect1 = array("tabla" => "", "d" => array(), "tmpSql" => $sql1, "proveedor" => null);
            $rs1 = $genericoDao->select($sqlSelect1);
//            var_dump($rs1);
            //###############################################################
//            var_dump($posicion);

            if ($posicion == 1) {
                if ($cont % 2 == 1) {
                    $pdf->AddPage();
                    $pdf->SetAutoPageBreak(true, 0);
                    // CREAR LAS ETIQUETAS
                    // DOS ETIQUETAS
                    $pdf->SetFillColor(255, 255, 255);
                    //$pdf->Rect(2, 3, 104, 24.9);
                    // ETIQUETA 1
                    $pdf->SetFillColor(255, 255, 255);
                    //$pdf->Rect(2, 3, 50.5, 25.1);

                    @$pdf->Image("../vistas/img/escudos/logo-EM-30.jpg", 4, 1, 5, 5);

                    $pdf->SetXY(17, 1);
                    $pdf->SetFontSize(7);
                    $pdf->MultiCell(45, 1, htmlspecialchars("PODER JUDICIAL "), 0, 'L', false);

                    $pdf->SetXY(40, 0);
                    @$pdf->Image("../vistas/img/escudos/logo-PJ-30.jpg", 40, 1, 5, 5);

                    $pdf->SetFillColor(0, 0, 0);
                    $pdf->Line(2, 6, 52.5, 6);

                    $pdf->SetFillColor(0, 0, 0);
                    $pdf->Line(2, 6.5, 52.5, 6.5);

                    $pdf->SetXY(2, 6);
                    $pdf->SetFontSize(5);
                    $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["denominacion"]), 0, 'L', false);

                    $pdf->SetXY(27, 6);
                    $pdf->SetFontSize(5);
                    $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["marca"]), 0, 'L', false);

                    $pdf->SetXY(2, 8);
                    $pdf->SetFontSize(5);
                    $pdf->MultiCell(25, 3, htmlspecialchars($inventario["numeroSerie"]), 0, 'L', false);

                    $pdf->SetXY(27, 8);
                    $pdf->SetFontSize(5);
                    $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["modelo"]), 0, 'L', false);

                    $numero = $rs1["data"][0]["codigoPropio"];
//                    var_dump($rs1["data"][0]["codigoPropio"]);
                    $pdf->Code128(8, 12, $numero, 37, 8);
                    $pdf->SetFontSize(9);
                    $pdf->SetXY(14, 20);
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Cell(25, 1, $numero, 0, 0, 'C', true);
                } else {
                    // ETIQUETA 2
                    $pdf->SetFillColor(255, 255, 255);
                    //$pdf->Rect(55.5, 3, 50.5, 25.1);

                    @$pdf->Image("../vistas/img/escudos/logo-EM-30.jpg", 58, 1, 5, 5);

                    $pdf->SetXY(70, 1);
                    $pdf->SetFontSize(7);
                    $pdf->MultiCell(45, 1, htmlspecialchars("PODER JUDICIAL "), 0, 'L', false);

                    $pdf->SetXY(40, 0);
                    @$pdf->Image("../vistas/img/escudos/logo-PJ-30.jpg", 95, 1, 5, 5);

                    $pdf->SetFillColor(0, 0, 0);
                    $pdf->Line(55.5, 6, 106, 6);

                    $pdf->SetFillColor(0, 0, 0);
                    $pdf->Line(55.5, 6.5, 106, 6.5);

                    $pdf->SetXY(55.5, 6);
                    $pdf->SetFontSize(5);
                    $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["denominacion"]), 0, 'L', false);

                    $pdf->SetXY(81, 6);
                    $pdf->SetFontSize(5);
                    $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["marca"]), 0, 'L', false);

                    $pdf->SetXY(55.5, 8);
                    $pdf->SetFontSize(5);
                    $pdf->MultiCell(25, 3, htmlspecialchars($inventario["numeroSerie"]), 0, 'L', false);

                    $pdf->SetXY(81, 8);
                    $pdf->SetFontSize(5);
                    $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["modelo"]), 0, 'L', false);

                    $numero = $rs1["data"][0]["codigoPropio"];
                    $pdf->Code128(62, 11.7, $numero, 37, 8);
                    $pdf->SetFontSize(9);
                    $pdf->SetXY(70, 19.5);
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Cell(25, 1, $numero, 0, 0, 'C', true);

                    //CUADRO PARA LOS ENCABEZADOS
                    $pdf->SetFillColor(255, 255, 255);
                    //$pdf->Rect(2, 3, 104, 5);
                    //CUADRO PARA LAS INFORMACION
                    $pdf->SetFillColor(255, 255, 255);
                    //$pdf->Rect(2, 8, 104, 6);
                    //CUADRO PARA EL CODIGO DE BARRAS
                    $pdf->SetFillColor(255, 255, 255);
                    //$pdf->Rect(2, 14, 104, 8.5);
                    // CUADRO PARA EL NUMERO
                    $pdf->SetFillColor(255, 255, 255);
                    //$pdf->Rect(2, 22.5, 104, 5.5);
                }
            } elseif ($posicion == 2) {
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(true, 0);
                // CREAR LAS ETIQUETAS
//                $pdf->AddPage();
//                $pdf->SetAutoPageBreak(true, 0);
                // DOS ETIQUETAS
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 3, 104, 24.9);
                // ETIQUETA 1
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 3, 50.5, 25.1);

                @$pdf->Image("../vistas/img/escudos/logo-EM-30.jpg", 4, 1, 5, 5);

                $pdf->SetXY(17, 1);
                $pdf->SetFontSize(7);
                $pdf->MultiCell(45, 1, htmlspecialchars("PODER JUDICIAL "), 0, 'L', false);

                $pdf->SetXY(40, 0);
                @$pdf->Image("../vistas/img/escudos/logo-PJ-30.jpg", 40, 1, 5, 5);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(2, 6, 52.5, 6);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(2, 6.5, 52.5, 6.5);

                $pdf->SetXY(2, 6);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["denominacion"]), 0, 'L', false);

                $pdf->SetXY(27, 6);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["marca"]), 0, 'L', false);

                $pdf->SetXY(2, 8);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($inventario["numeroSerie"]), 0, 'L', false);

                $pdf->SetXY(27, 8);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["modelo"]), 0, 'L', false);

                $numero = $rs1["data"][0]["codigoPropio"];
                $pdf->Code128(8, 12, $numero, 37, 8);
                $pdf->SetFontSize(9);
                $pdf->SetXY(14, 20);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(25, 1, $numero, 0, 0, 'C', true);
            } elseif ($posicion == 3) {
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(true, 0);
                // ETIQUETA 2
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(55.5, 3, 50.5, 25.1);

                @$pdf->Image("../vistas/img/escudos/logo-EM-30.jpg", 58, 1, 5, 5);

                $pdf->SetXY(70, 1);
                $pdf->SetFontSize(7);
                $pdf->MultiCell(45, 1, htmlspecialchars("PODER JUDICIAL "), 0, 'L', false);

                $pdf->SetXY(40, 0);
                @$pdf->Image("../vistas/img/escudos/logo-PJ-30.jpg", 95, 1, 5, 5);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(55.5, 6, 106, 6);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(55.5, 6.5, 106, 6.5);

                $pdf->SetXY(55.5, 6);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["denominacion"]), 0, 'L', false);

                $pdf->SetXY(81, 6);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["marca"]), 0, 'L', false);

                $pdf->SetXY(55.5, 8);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($inventario["numeroSerie"]), 0, 'L', false);

                $pdf->SetXY(81, 8);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["modelo"]), 0, 'L', false);

                $numero = $rs1["data"][0]["codigoPropio"];
                $pdf->Code128(62, 11, $numero, 37, 8);
                $pdf->SetFontSize(9);
                $pdf->SetXY(70, 19.5);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(25, 1, $numero, 0, 0, 'C', true);

                //CUADRO PARA LOS ENCABEZADOS
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 3, 104, 5);
                //CUADRO PARA LAS INFORMACION
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 8, 104, 6);
                //CUADRO PARA EL CODIGO DE BARRAS
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 14, 104, 8.5);
                // CUADRO PARA EL NUMERO
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 22.5, 104, 5.5);
            }
            //###############################################################
        } elseif ($inventario["cveClasificadorBien"] == "2") {

            $sql2 = array(
                "campos" => "   
                        tblaah.*
                    ",
                "tablas" => "
                        tblaah tblaah
                        INNER JOIN tblinventarios tblinventarios
                        ON tblinventarios.idReferencia  = tblaah.idAah
                    ",
                "where" => " 
                        tblaah.idAah = " . $inventario["idReferencia"] . " AND tblinventarios.idInventario =   " . $inventario["idInventario"] . "
                    "
            );
            $sqlSelect2 = array("tabla" => "", "d" => array(), "tmpSql" => $sql2, "proveedor" => null);
            $rs2 = $genericoDao->select($sqlSelect2);
//            var_dump($rs1);
            //###############################################################
            if ($cont % 2 == 1) {
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(true, 0);
                // CREAR LAS ETIQUETAS
//                $pdf->AddPage();
//                $pdf->SetAutoPageBreak(true, 0);
                // DOS ETIQUETAS
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 3, 104, 24.9);
                // ETIQUETA 1
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 3, 50.5, 25.1);

                @$pdf->Image("../vistas/img/escudos/logo-EM-30.jpg", 4, 1, 5, 5);

                $pdf->SetXY(17, 1);
                $pdf->SetFontSize(7);
                $pdf->MultiCell(45, 1, htmlspecialchars("PODER JUDICIAL "), 0, 'L', false);

                $pdf->SetXY(40, 0);
                @$pdf->Image("../vistas/img/escudos/logo-PJ-30.jpg", 40, 1, 5, 5);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(2, 6, 52.5, 6);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(2, 6.5, 52.5, 6.5);

                $pdf->SetXY(2, 6);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($rs2["data"][0]["denominacion"]), 0, 'L', false);

//                $pdf->SetXY(27, 8);
//                $pdf->SetFontSize(5);
//                $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["marca"]), 0, 'L', false);

                $pdf->SetXY(2, 8);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars("Bien A.A.H"), 0, 'L', false);

//                $pdf->SetXY(27, 10);
//                $pdf->SetFontSize(5);
//                $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["modelo"]), 0, 'L', false);

                $numero = $rs2["data"][0]["aahPropio"];
                $pdf->Code128(8, 12, $numero, 37, 8);
                $pdf->SetFontSize(9);
                $pdf->SetXY(14, 20.5);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(25, 1, $numero, 0, 0, 'C', true);
            } else {
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(true, 0);
                // ETIQUETA 2
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(55.5, 3, 50.5, 25.1);

                @$pdf->Image("../vistas/img/escudos/logo-EM-30.jpg", 58, 1, 5, 5);

                $pdf->SetXY(70, 1);
                $pdf->SetFontSize(7);
                $pdf->MultiCell(45, 1, htmlspecialchars("PODER JUDICIAL "), 0, 'L', false);

                $pdf->SetXY(40, 0);
                @$pdf->Image("../vistas/img/escudos/logo-PJ-30.jpg", 95, 1, 5, 5);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(55.5, 6, 106, 6);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(55.5, 6.5, 106, 6.5);

                $pdf->SetXY(55.5, 6);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($rs2["data"][0]["denominacion"]), 0, 'L', false);

//                $pdf->SetXY(81, 8);
//                $pdf->SetFontSize(5);
//                $pdf->MultiCell(25, 3, htmlspecialchars("MOD 4 PERSONAS"), 0, 'L', false);

                $pdf->SetXY(55.5, 8);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars("Bien A.A.H"), 0, 'L', false);

//                $pdf->SetXY(81, 10);
//                $pdf->SetFontSize(5);
//                $pdf->MultiCell(25, 3, htmlspecialchars("MOD. 4 PERSONAS"), 0, 'L', false);

                $numero = $rs2["data"][0]["aahPropio"];
                $pdf->Code128(62, 12, $numero, 37, 8);
                $pdf->SetFontSize(9);
                $pdf->SetXY(70, 20.5);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(25, 1, $numero, 0, 0, 'C', true);

                //CUADRO PARA LOS ENCABEZADOS
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 3, 104, 5);
                //CUADRO PARA LAS INFORMACION
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 8, 104, 6);
                //CUADRO PARA EL CODIGO DE BARRAS
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 14, 104, 8.5);
                // CUADRO PARA EL NUMERO
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 22.5, 104, 5.5);
            }
        } elseif ($inventario["cveClasificadorBien"] == "3") {
            $sql2 = array(
                "campos" => "   
                        tblaah.*
                    ",
                "tablas" => "
                        tblaah tblaah
                        INNER JOIN tblinventarios tblinventarios
                        ON tblinventarios.idReferencia  = tblaah.idAah
                    ",
                "where" => " 
                        tblaah.idAah = " . $inventario["idReferencia"] . " AND tblinventarios.idInventario =   " . $inventario["idInventario"] . "
                    "
            );
            $sqlSelect3 = array("tabla" => "", "d" => array(), "tmpSql" => $sql3, "proveedor" => null);
            $rs3 = $genericoDao->select($sqlSelect3);
//            var_dump($rs1);
            //###############################################################
            if ($cont % 2 == 1) {
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(true, 0);
                // CREAR LAS ETIQUETAS
//                $pdf->AddPage();
//                $pdf->SetAutoPageBreak(true, 0);
                // DOS ETIQUETAS
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 3, 104, 24.9);
                // ETIQUETA 1
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 3, 50.5, 25.1);

                @$pdf->Image("../vistas/img/escudos/logo-EM-30.jpg", 4, 1, 5, 5);

                $pdf->SetXY(17, 1);
                $pdf->SetFontSize(7);
                $pdf->MultiCell(45, 1, htmlspecialchars("PODER JUDICIAL "), 0, 'L', false);

                $pdf->SetXY(40, 0);
                @$pdf->Image("../vistas/img/escudos/logo-PJ-30.jpg", 40, 1, 5, 5);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(2, 6, 52.5, 6);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(2, 6.5, 52.5, 6.5);

                $pdf->SetXY(2, 6);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($rs3["data"][0]["denominacion"]), 0, 'L', false);

//                $pdf->SetXY(27, 8);
//                $pdf->SetFontSize(5);
//                $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["marca"]), 0, 'L', false);

                $pdf->SetXY(2, 8);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars("Bien C.B.I"), 0, 'L', false);

//                $pdf->SetXY(27, 10);
//                $pdf->SetFontSize(5);
//                $pdf->MultiCell(25, 3, htmlspecialchars($rs1["data"][0]["modelo"]), 0, 'L', false);

                $numero = $rs3["data"][0]["cbiPropio"];
                $pdf->Code128(8, 12, $numero, 37, 8);
                $pdf->SetFontSize(9);
                $pdf->SetXY(14, 20.5);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(25, 1, $numero, 0, 0, 'C', true);
            } else {
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(true, 0);
                // ETIQUETA 2
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(55.5, 3, 50.5, 25.1);

                @$pdf->Image("../vistas/img/escudos/logo-EM-30.jpg", 58, 1, 5, 5);

                $pdf->SetXY(70, 1);
                $pdf->SetFontSize(7);
                $pdf->MultiCell(45, 1, htmlspecialchars("PODER JUDICIAL "), 0, 'L', false);

                $pdf->SetXY(40, 0);
                @$pdf->Image("../vistas/img/escudos/logo-PJ-30.jpg", 95, 1, 5, 5);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(55.5, 6, 106, 6);

                $pdf->SetFillColor(0, 0, 0);
                $pdf->Line(55.5, 6.5, 106, 6.5);

                $pdf->SetXY(55.5, 6);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars($rs3["data"][0]["denominacion"]), 0, 'L', false);

//                $pdf->SetXY(81, 8);
//                $pdf->SetFontSize(5);
//                $pdf->MultiCell(25, 3, htmlspecialchars("MOD 4 PERSONAS"), 0, 'L', false);

                $pdf->SetXY(55.5, 8);
                $pdf->SetFontSize(5);
                $pdf->MultiCell(25, 3, htmlspecialchars("Bien C.B.I"), 0, 'L', false);

//                $pdf->SetXY(81, 10);
//                $pdf->SetFontSize(5);
//                $pdf->MultiCell(25, 3, htmlspecialchars("MOD. 4 PERSONAS"), 0, 'L', false);

                $numero = $rs3["data"][0]["cbiPropio"];
                $pdf->Code128(62, 12, $numero, 37, 8);
                $pdf->SetFontSize(9);
                $pdf->SetXY(70, 20.5);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(25, 1, $numero, 0, 0, 'C', true);

                //CUADRO PARA LOS ENCABEZADOS
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 3, 104, 5);
                //CUADRO PARA LAS INFORMACION
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 8, 104, 6);
                //CUADRO PARA EL CODIGO DE BARRAS
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 14, 104, 8.5);
                // CUADRO PARA EL NUMERO
                $pdf->SetFillColor(255, 255, 255);
                //$pdf->Rect(2, 22.5, 104, 5.5);
            }
        }
        $cont = $cont + 1;
    }
} else {
    
}

$pdf->Output();
?>

