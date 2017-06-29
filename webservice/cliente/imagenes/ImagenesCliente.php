<?php
class ImagenesCliente {
    public function getRuta($idCarpetaJudicial=0,$idActuacion=0,$fojas=1) {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $imagenes = new SoapClient("http://localhost/electronico/webservice/servidor/imagenes/ImagenesServer.php?wsdl");
        $imagenes = $imagenes->selectRuta($idCarpetaJudicial, $idActuacion, $fojas, "3lectronic0_Poder2014", "2014Poder_3lectronic0");
        return $imagenes;
    }

    public function setAdjunto($ruta="",$Adjunto="N") {
        ini_set("default_socket_timeout", 200);
        ini_set("soap.wsdl_cache_enabled", "0");
        $imagenes = new SoapClient("http://localhost/electronico/webservice/servidor/imagenes/ImagenesServer.php?wsdl");
        $imagenes = $imagenes->selectAdjunto($Adjunto, $ruta, "3lectronic0_Poder2014", "2014Poder_3lectronic0");
        return $imagenes;
    }
}

if (isset($_POST["btnSelectRuta"])) {
    echo "Obteniendo Ruta del WebService <br>";
    $imagenesCliente = new ImagenesCliente();
    $ruta = $imagenesCliente->getRuta($_POST["txtIdCarpetaJudicial"],$_POST["txtIdActuacion"],$_POST["txtFojas"]);
    echo $ruta;
    exit;
}

if (isset($_POST["btnSelectAdjunto"])) {
    echo "Actualizado Ruta en el WebService <br>";
    $imagenesCliente = new ImagenesCliente();
    $ruta = $imagenesCliente->setAdjunto($_POST["txtRuta"],$_POST["txtAdjunto"]);
    echo $ruta;
    exit;
}

?>
<html>
    <head>
        <title>Ejemplo de uso web Service Cliente</title>
        <link rel="stylesheet" href="../../../vistas/css/estilos.css" type="text/css" />
    </head>
    <body>
        <table width="60%" style='border:1px solid #000' align='center'>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" align='center'><b>Ejemplo del uso del webservice Cliente</b></td                
            </tr>
            <tr>
                <td colspan="2" valign='top'>
                    <form name="frmSelectRuta" id="frmSelectRuta" action="ImagenesCliente.php" target="salida" method="POST">
                        <table border='0' style='border:1px solid #000'>
                            <tr>
                                <td colspan="4" align='center'><b>SelectRuta</b></td>
                            </tr>
                            <tr>
                                <td><b>IdCarpetaJudicial:</b></td>
                                <td><input type="text" class="frmCaja"  name="txtIdCarpetaJudicial" id="txtIdCarpetaJudicial"/></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>IdActuacion:</b></td>
                                <td><input type="text" class="frmCaja"  name="txtIdActuacion" id="txtIdActuacion"/></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>Fojas:</b></td>
                                <td><input type="text" class="frmCaja"  name="txtFojas" id="txtFojas"/></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="4"  align='center'>
                                    <input type="submit" class="frmBoton"  name="btnSelectRuta" value="GetRuta">
                                </td>  
                            </tr>
                        </table>
                    </form>
                </td>
                <td colspan="2" valign='top'>
                    <form name="frmSelectAdjunto" id="frmSelectAdjunto" action="ImagenesCliente.php" target="salida"  method="POST">
                        <table border='0' style='border:1px solid #000'>
                            <tr>
                                <td colspan="4" align='center'><b>SelectAdjunta</b></td>
                            </tr>
                            <tr>
                                <td><b>Ruta:</b></td>
                                <td><input type="text" class="frmCaja"  name="txtRuta" id="txtRuta"/></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>Adjunto:</b></td>
                                <td><input type="text" class="frmCaja"  name="txtAdjunto" id="txtAdjunto"/></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="4" align='center'>
                                    <input type="submit" class="frmBoton" name="btnSelectAdjunto" value="SetAdjunto">
                                </td>  
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
            
            <tr>
                <td colspan="2" valign='top'>
                    <form name="frmUploadFile" id="frmUploadFile" action="http://dticursos.pjedomex.gob.mx/electronico/imagenes/upload.php" target="salida" method="POST" ENCTYPE="multipart/form-data">
                        <table border='0' style='border:1px solid #000'>
                            <tr>
                                <td colspan="4" align='center'><b>Adjuntar archivo</b></td>
                            </tr>
                            <tr>
                                <td><b>Archivo:</b></td>
                                <td><input type="file" class="frmCaja"  name="archivo" id="archivo"/></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>ruta:</b></td>
                                <td><input type="text" class="frmCaja"  name="ruta" id="ruta"/></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>usuario:</b></td>
                                <td><input type="text" class="frmCaja"  name="usuario" id="usuario" value="3lectronic0_Poder2014"/></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>password:</b></td>
                                <td><input type="text" class="frmCaja"  name="password" id="password" value="2014Poder_3lectronic0"/></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="4"  align='center'>
                                    <input type="submit" class="frmBoton"  name="btnUpload" value="Upload">
                                </td>  
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
            
            <tr>
                <td colspan="4" align='center'><b>Respuesta</b></td                
            </tr>
            <tr>
                <td colspan="4">
                    <iframe name="salida" width="100%" height="300px"></iframe>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="4" class="Arial9" style="text-align: right">
                    <footer>
                        <?php date_default_timezone_set("America/Mexico_City"); ?>
                        <b><p>&copy; 2013 - <?php echo date('Y') ?> Poder Judicial del Estado de M&eacute;xico. Todos los derechos reservados</p></b>
                    </footer>
                </td>
            </tr>
        </table>
    </body>
</html>

