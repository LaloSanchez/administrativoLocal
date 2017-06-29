<?php
date_default_timezone_set("America/Mexico_City");
include_once(dirname(__FILE__) . "/../../../controladores/resguardos/ResguardoIndividualController.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");
if (!is_null($_GET)) {
    $ResguardoIndividualController = new ResguardoIndividualController();
    if($_GET["tipo"] == 1){
        foreach ($_GET as $key => $value) {
            if($key == "cveAdscripcion"){
                @$extrasPOST[$key] = $value;
            }
        }
        $params["extrasPost"]=$extrasPOST;
        $json=$ResguardoIndividualController->cargarFaltantesAdscripcion($params,2);
    }else{
        foreach ($_GET as $key => $value) {
            if($key == "numEmpleado"){
                @$extrasPOST[$key] = $value;
            }
        }
        $params["extrasPost"]=$extrasPOST;
        $json=$ResguardoIndividualController->cargarFaltantes($params,2);
    }
    $decode=new Decode_JSON();
    $array=$decode->decode($json);
}
ob_start();
?>
<style>
    <!--
    #encabezado {padding:10px 0;width:100%;}
    #encabezado2{margin: auto; width: 100%;font-family: Arial,Calibri;font-size: 13px;font-weight: bold;}
    #encabezado .fila #col_1 {width: 25%}
    #encabezado .fila #col_2 {text-align:center; width: 50%;} 
    #encabezado .fila #col_2 #span1{font-family: Arial,Calibri;font-size: 18px;font-weight: bold;}
    #encabezado .fila #col_2 #span2{font-size: 12px; color: #4d9;}
    #encabezado .fila #col_3 {width: 25%}
    #footer {padding-top:5px 0; border-top:gray 1px solid; width:100%;}
    #footer .fila td {text-align:right; width:100%;}
    #footer .fila td span {font-size: 10px; color: grey;}
    body{font-family: Arial;}
    /*.tr-1{background-color: #7b7878;font-weight: bold;}*/
    .tr-1{background-color: #ccc;font-weight: bold;}
    .tr-2{font-size: 10px;}
    .tabla{width: 100%;border: solid 0px; border-collapse: collapse;}      
    .table{width: 93%;border-collapse: collapse;font-family: Arial,Calibri;font-size: 11px;border:2px;}  
    /*.table tr{border-top:0px;border-bottom: 0px;border-spacing: 0;}*/ 


</style>
<page backtop="25mm" backbottom="10mm" backleft="10mm" backright="10mm" orientation="landscape">
    <page_header>
        <table id="encabezado2" style="width: 100%;padding-bottom: 30px;" >
            <tr class="fila">
                <td style=" width:30%;padding-left: 33px;">
                    <img src="../../img/logoPj.png" style="width:160px;">
                </td>
                <td style="width:40%;font-size: 14px;font-weight: bold;" align="center">
                    <br><br>
                    Poder Judicial del Estado de M&eacute;xico<br>Reporte de Faltantes<br>
                    <?php 
                    if($_GET["tipo"] == 1){
                        echo $array->adscripcion;
                    }else{
                        echo $array->empleado;
                    }
                    ?>
                    <br><br>
                </td>
                <td style=" width:30%; padding-right: 35px;font-size: 12px; text-align: right;" align="right">
                    <img src="../../img/escudoedomex.jpg" style="width:160px;">
                </td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table id="footer" style="width: 100%">
            <tr class="fila">
                <td style=" width:50%; font-size: 9px; text-align: right; border-right:#881518 1px solid;">
                    Av. Independencia Ote. 616. Colonia Santa Clara, Toluca, M&eacute;xico<br>
                    Tel. (722) 167 9200 Ext. 15436<br>
                    direccion.patrimonial@pjedomex.gob.mx
                </td>
                <td style=" width:50%; font-size: 9px; text-align: left;">
                    <strong>DIRECCI&Oacute;N GENERAL DE FINANZAS Y PLANEACI&Oacute;N<br></strong>
                    DIRECCI&Oacute;N DE CONTROL PATRIMONIAL
                </td>
            </tr>
        </table>
    </page_footer>
    <br><br>
    <table class="table" style="width:100%; border: solid 1px;margin-top: 0px;" border="2px">
        <?php
            if($_GET["tipo"] == 1){
                echo '<col style="width:20%">';
                echo '<col style="width:20%">';
                echo '<col style="width:20%">';
                echo '<col style="width:20%">';
                echo '<col style="width:20%">';
            }else{
                echo '<col style="width:25%">';
                echo '<col style="width:25%">';
                echo '<col style="width:25%">';
                echo '<col style="width:25%">';
            }
        ?>
        
        <thead class="bordered-darkorange">
            <tr class="tr-1">
                <th align="center">C&oacute;digo</th>
                <th align="center">Denominaci&oacute;n</th>
                <th align="center">N&uacute;mero de Serie</th>
                <th align="center">Precio Actual</th>
                <?php
                if($_GET["tipo"] == 1){
                    echo '<th align="center">Empleado</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($array->data as $key => $value){
                ?>
                <tr>
                    <td align="center"><?php echo $value->codigoPropio;?></td>
                    <td align="center"><?php echo $value->denominacion;?></td>
                    <td align="center"><?php echo $value->numeroSerie;?></td>
                    <td align="center"><?php echo $value->precioActual;?></td>
            <?php
                if($_GET["tipo"] == 1){
                    echo '<td align="center">'.$value->nombreEmpleado.'</td>';
                }
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</page>
<?php
$content = utf8_encode(ob_get_clean());
require_once(dirname(__FILE__) . '/../../../tribunal/pdf/html2pdf.class.php');
try {
    $html2pdf = new HTML2PDF('L', 'A4', 'es');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Faltantes.pdf');
} catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
}
?>