<?php
date_default_timezone_set("America/Mexico_City");

require_once(dirname(__FILE__) . '/../../fachadas/suficienciaspresupuestales/suficienciasPresupuestalesFacade.Class.php');
include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../webservice/cliente/personal/PersonalCliente.php");

include_once(dirname(__FILE__) . "/../../controladores/cuadrocomparativo/CuadroComparativoController.Class.php");
include_once(dirname(__FILE__) . "/../../controladores/Imagenes/ImagenesController.Class.php");
$personalCliente = new PersonalCliente();

$idSuficienciaPresupuestal = isset($_GET["idSuficienciaPresupuestal"]) ? $_GET["idSuficienciaPresupuestal"] : 0;

$cveAdscripcion = isset($_GET["cveAdscripcion"]) ? $_GET["idSuficienciaPresupuestal"] : 0;;

$facade = new suficienciasPresupuestales();
$params = array("frm" => "tblsuficienciascog", "idSuficienciaPresupuestal" => $idSuficienciaPresupuestal, "accion" => "consultarMovimientos");
$result = $facade->consultarMovimientos($params);
$json_decode = new Decode_JSON();
$array = $json_decode->decode($result, true);
$imagenes = new ImagenesController();


$cvePrograma = "";
$enero = 0;
$febrero = 0;
$marzo = 0;
$abril = 0;
$mayo = 0;
$junio = 0;
$julio = 0;
$agosto = 0;
$septiembre = 0;
$octubre = 0;
$noviembre = 0;
$diciembre = 0;
if ($array['status'] != "error") {

    $d = array();
    $sql = array("campos" => "claveProyecto,desProyectoPresupuestal",
        "tablas" => " tblunidadesejecutoras U INNER JOIN tblproyectospresupuestales V ON (V.idProyectoPresupuestal= U.idProyectoPresupuestal)",
        "where" => "U.activo='S' AND U.cveAdscripcion =" . $array["data"][0]->cveAdscripcion);
    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
    $genericDao = new GenericDAO();
    $getAdscripcionNombre = new CuadroComparativoController();
  $res = $getAdscripcionNombre->getAdscripcionNombre($cveAdscripcion);
 
    $rs = $genericDao->select($param);

    if ($rs["status"] == "error" || $rs["totalCount"] == 0) {
        echo "NO SE TIENE REGISTRO DEL PROYECTO PRESUPUESTAL PROVENIENTE"; //NOTIFICAR ERROR EN EL PROYECTO PRESUPUESTAL
    } else {
        
        $cvePrograma = $rs["data"][0]["claveProyecto"];
        $desPP = $rs["data"][0]["desProyectoPresupuestal"];
    }
   
    
    $guardarSuficiencia = array(
                        "tabla" => "tbldocumentosimg",
                        "d" => array(
                            "values" => array( 
                                "cveTipoDocumento" => 20,
                                "idReferencia" => $idSuficienciaPresupuestal,
                                "descripcion" => "SUFICIENCIA PRESUPUESTAL",
                                "cveUsuario" => $_SESSION["numEmpleado"],
                                'fojas' => 1,
                                
                                "activo" => "S",
                                "fechaRegistro" => "now()",
                                "fechaActualizacion" => "now()",
                            )
                        ),
                        "proveedor" =>null
                    );
                   
                    $guardarSuficienciaRS = $genericDao->insert($guardarSuficiencia);
                    if ($guardarSuficienciaRS["status"] != "error" || $guardarSuficienciaRS["totalCount"] > 0) {
                        
        


?>
 
    <style type="text/css">
        <!--
        #encabezado{text-align:center;margin: auto; width: 80%;font-family: Arial,Calibri;font-size: 16px;font-weight: bold;}
        #totalPartida{width: 100%;border:solid 0px;font-size: 9px;}
        #detalleTabla2{width: 100%;border:solid 0px;font-size: 9px;}
        .tr-1{background-color: #ccc;font-weight: bold;font-size: 10px;}
        #tabla1{float: left;}
        .td-cal{border-bottom: solid 1px;text-align: right;}
        .moneda{text-align: right;}
        -->
    </style>  
    
    <table style="width: 100%;font-size: 15px;font-weight: bold;"> 
        <tr>
            <td style="width: 15%"></td>
            <td style="width: 70%;text-align: center;">SOLICITUD DE SUFICIENCIA PRESUPUESTAL Y DICTAMEN</td>
            <td style="width: 17%"></td>
            
        </tr>
    </table>
    <table style="width: 100%;font-size: 10px;font-weight: bold;"> 
        <col style="width: 10%;">
        <col style="width: 20%;">
        <col style="width: 20%;">
        <col style="width: 10%;">
        <col style="width: 20%;">
        <col style="width: 20%;">
        <tr>
            <td style="width: 10%;background-color: #851518; color:white;text-align: center;padding: 3px;">FOLIO</td>
            <td style="width: 20%; border-bottom: solid 1px; color: red;text-align: center;"><?php echo $array["data"][0]->folio."/".$array["data"][0]->anioSufuciencia; ?></td>
            <td style="width: 20%;background-color: #851518; color:white;text-align: center;padding: 3px;">ESTATUS:</td>
            <td style="width: 10%; border-bottom: solid 1px; color: red;text-align: center;"><?php echo $array["data"][0]->desEstatusS; ?></td>
            <td style="width: 20%;background-color: #851518; color:white;text-align: center;padding: 3px;">FECHA DE RECEPCI&Oacute;N:</td>
            <td style="width: 20%; border-bottom: solid 1px; color: red;text-align: center;">
                <?php 
            if($array["data"][0]->fechaRecepcion==""){
            echo "AUN NO RECIBIDA";
            }else{
                $originalDate = $array["data"][0]->fechaRecepcion;
                $newDate = date("d-m-Y", strtotime($originalDate));
                 echo $newDate;
            }?></td>
        </tr>
    </table>
    <br>
    <table cellspacing="0" style="width: 100%;border: solid 1px;">
        <col style="width: 82%">
        <col style="width: 18%">
        <tr>
            <td style="vertical-align: top">
                <table class="table" id="totalPartida" border="0">     
                    <col style="width: 15%">
                    <col style="width: 20%">
                    <col style="width: 25%">
                    <col style="width: 5%">
                    <col style="width: 20%">
                    <col style="width: 15%">
                    <tr class="tr-1">             
                        <td>PROYECTO</td> 
                        <td>COG</td>
                        <td>CLASIFICACI&Oacute;N</td>
                        <TD>No.</TD>
                        <td>DESCRIPCI&Oacute;N DEL BIEN O SERVICIO</td>
                        <td>IMPORTE</td> 
                    </tr>   
<?php
for ($i = 0; $i < $array["totalCount"]; $i++) {
    $enero += $array["data"][$i]->montoEnero;
    $febrero += $array["data"][$i]->montoFebrero;
    $marzo += $array["data"][$i]->montoMarzo;
    $abril += $array["data"][$i]->montoAbril;
    $mayo += $array["data"][$i]->montoMayo;
    $junio += $array["data"][$i]->montoJunio;
    $julio += $array["data"][$i]->montoJulio;
    $agosto += $array["data"][$i]->montoAgosto;
    $septiembre += $array["data"][$i]->montoSeptiembre;
    $octubre += $array["data"][$i]->montoOctubre;
    $noviembre += $array["data"][$i]->montoNoviembre;
    $diciembre += $array["data"][$i]->montoDiciembre;
    ?>

                        <tr>            
                            <td><?php echo $array["data"][$i]->claveProyecto . " " . $array["data"][$i]->desProyectoPresupuestal?></td>
                            <td><?PHP echo $array["data"][$i]->cogPropio . " " . $array["data"][$i]->denominacion ?></td>
                             <td><?php echo $array["data"][$i]->NNN ?> </td> 
                              <td><?php echo $array["data"][$i]->cantidad ?> </td> 
                            <td><?php echo $array["data"][$i]->DESS ?> </td> 
                            <td class="moneda">$<?php echo $array["data"][$i]->montoTotal ?></td> 
                        </tr> 
<?php } 
$total = $enero+$febrero+$marzo+$abril+$mayo+$junio+$julio+$agosto+$septiembre+$octubre+$noviembre+$diciembre;?>

                    <tr> 
                        <td></td>
                        <td></td> 
                        <td></td> 
                        <td colspan="2" style="text-align: right;font-weight: bold; ">TOTAL:$</td>
                        <td class="moneda" style="font-weight: bold;border-bottom:solid .5px;"><?php echo number_format($total, 2);?></td>
                        
                    </tr> 
                </table>                
            </td>
            <td style="vertical-align: top">
                <table class="table" id="detalleTabla2" border="0">     
                    <col style="width: 10%">        
                    <col style="width: 80%">        
                    <thead>
                        <tr class="tr-1">             
                            <td colspan="2">CALENDARIZACI&Oacute;N</td>                 
                        </tr> 
                    </thead>
                    <tr>
                        <td>E</td>
                        <td class="td-cal">$<?php echo $enero; ?></td>
                    </tr>
                    <tr>
                        <td>F</td>
                        <td class="td-cal">$<?php echo $febrero; ?></td>
                    </tr>                
                    <tr>
                        <td>M</td>
                        <td class="td-cal">$<?php echo $marzo; ?></td>
                    </tr>                
                    <tr>
                        <td>A</td>
                        <td class="td-cal">$<?php echo $abril; ?></td>
                    </tr>                
                    <tr>
                        <td>M</td>
                        <td class="td-cal">$<?php echo $mayo; ?></td>
                    </tr>                
                    <tr>
                        <td>J</td>
                        <td class="td-cal">$<?php echo $junio; ?></td>
                    </tr>                
                    <tr>
                        <td>J</td>
                        <td class="td-cal">$<?php echo $julio; ?></td>
                    </tr>                
                    <tr>
                        <td>A</td>
                        <td class="td-cal">$<?php echo $agosto; ?></td>
                    </tr>                
                    <tr>
                        <td>S</td>
                        <td class="td-cal">$<?php echo $septiembre; ?></td>
                    </tr>                
                    <tr>
                        <td>O</td>
                        <td class="td-cal">$<?php echo $octubre; ?></td>
                    </tr>                
                    <tr>
                        <td>N</td>
                        <td class="td-cal">$<?php echo $noviembre; ?></td>
                    </tr>                
                    <tr>
                        <td>D</td>
                        <td class="td-cal">$<?php echo $diciembre; ?></td>
                    </tr>                
                </table> 
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom: solid .5px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border: 1px solid">
                <table bordered class="table" style="width: 100%; ">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <tr class="tr-1">
                        <th>Enero</th>
                        <th>Febrero</th>
                        <th>Marzo</th>
                        <th>Abril</th>
                        <th>Mayo</th>
                        <th>Junio</th>
                        <th>Julio</th>
                        <th>Agosto</th>
                        <th>Septiembre</th>
                        <th>Octubre</th>
                       
                        
                    </tr>
                    <a href="../../../../controladores/Imagenes/ImagenesController.Class.php"></a>
                </table>
            </td>
            <td style="border: 1px solid">
                 <table class="table" style="width: 100%;">
                      <col style="width: 50%">
                    <col style="width: 50%">
                    <tr class="tr-1">
                        <TD>Noviembre</TD>
                        <TD>Diciembre</TD>
                        
                        
                        
                    </tr>
                    
                </table>
            </td>
             
        </tr>
        <tr>
            <td colspan="2" style="">
                <table style="width: 95%;margin: auto;" >
                    <col style="width: 24%">        
                    <col style="width: 76%">  
                    <tr>
                        <td style="padding-top: 3px;padding-bottom: 3px;text-align: right;font-size: 10px;background-color: #CCC; font-weight: bold;">JUSTIFICACION DEL GASTO: &nbsp;</td>
                        <td > 
                        </td>
                    </tr>

                    <tr>
                        <td style="border-bottom: solid .5px;font-size: 10px;" colspan="2"> <?php echo $array["data"][0]->justificacion ?></td>
                        <!--<td style="border-bottom: solid .5px;" colspan="2"> &nbsp;</td>-->
                    </tr>




                </table>
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 10px;font-weight: bold;border-top: solid .5px;text-align: center; background-color: #CCC;">DICTAMEN</td>
        </tr>
        <tr>
            <td  style="font-size: 9px;vertical-align: top; text-align: center;">  
                
                <table cellpadding="0" cellspacing="0" style="border: 1px solid; border-collapse: collapse; width: 100%;font-size: 10px;text-align: center;">
        <col style="width: 20%">
        <col style="width: 20%">
        <col style="width: 20%">
        <col style="width: 20%">
        <col style="width: 20%">
        
        <tr>
            <td style="background-color: #851518;color:white;border:solid .5px">Proyecto Presupuestal</td>
            
            <td style="background-color: #851518;color:white;border:solid .5px">DENOMINACION</td>
            
            <td style="background-color: #851518;color:white;border:solid .5px">DICTAMEN</td>
            <td style="background-color: #851518;color:white;border:solid .5px">FECHA </td>
            <td style="background-color: #851518;color:white;border:solid .5px">OBSERVACIONES</td>
            
        </tr>
        <?php for ($x = 0; $x < $array["totalCount"]; $x++){?> 
         <tr>
             <td style="border: .5px solid;"><?php echo $array["data"][$x]->claveProyecto."-".$array["data"][$x]->desProyectoPresupuestal; ?></td>
            
            <td style="border: .5px solid;"><?php echo $array["data"][$x]->denominacion; ?></td>
            
            <td style="border: .5px solid;"><?php echo $array["data"][$x]->desEstatus; ?></td>
            <td style="border: .5px solid;"><?php  $originalDate = $array["data"][0]->fechaDictamen;
                                     $newDate = date("d-m-Y", strtotime($originalDate));
                             echo $newDate;?><br><br><br></td>
            <td style="border: .5px solid;"><?php echo $array["data"][$x]->observaciones; ?></td>
        </tr>
       <?php }?>
    
        
       
    </table>
                    
            </td>
            <td>
                          <table cellpadding="0" cellspacing="0" style="border: 1px solid; border-collapse: collapse; width: 100%;font-size: 10px;text-align: center;">
                          <col style="width: 50%">
                          <col style="width: 50%">
                          <tr>
                          <td style="background-color: #851518;color:white;border:solid .5px">COG</td>
                          <td style="background-color: #851518;color:white;border:solid .5px">IMPORTE</td>
                          </tr>
                           <?php for ($x = 0; $x < $array["totalCount"]; $x++){?> 
                          <tr>
                            <td style="border: .5px solid;"><?php echo $array["data"][$x]->cogPropio; ?><br><br><br></td>
                            <td style="border: .5px solid;">$<?php echo $array["data"][$x]->montoTotal; ?><br><br><br></td>  
                          </tr>
                           <?php }?>
                          </table>
            </td>
        </tr>        
    </table>
    <br>    
    <div style="width: 95%;margin: auto;font-size: 9px;">
        NOTA: El dictamen es de car&aacute;cter informativo sobre la disponibilidad de los recursos presupuestales y no autoriza ni valida adquisici&oacute;n de un bien o servicio.
    </div>        
    <br>
    <br>
    
    <?php
    
            if($array["data"][0]->numEmpleadoElaboro != "" || $array["data"][0]->numEmpleadoElaboro != NULL){
                
                $result = json_decode($personalCliente->getNumEmpleado($array["data"][0]->numEmpleadoElaboro));
                
                if(isset($result->data[0]->TituloTrato )){
                       $elaboro = $result->data[0]->TituloTrato ." ".$result->data[0]->Nombre." ".$result->data[0]->Paterno." ".$result->data[0]->Materno;    
                }else{
                    $elaboro = "Usuario Externo";
                }
            }else{
                $elaboro = "";
            }
            
            if($array["data"][0]->numEmpleadoReviso != "" || $array["data"][0]->numEmpleadoReviso != NULL){
                $result = json_decode($personalCliente->getNumEmpleado($array["data"][0]->numEmpleadoReviso));
                if(isset($result->data[0]->TituloTrato)){
                        $reviso = $result->data[0]->TituloTrato ." ".$result->data[0]->Nombre." ".$result->data[0]->Paterno." ".$result->data[0]->Materno;    
            
                }else{
                    $reviso = "usuario externo";
                }
            }else{
                $reviso = "";
            }
            
            
             if($array["data"][0]->numEmpleadoAprobo != "" || $array["data"][0]->numEmpleadoAprobo != NULL){
                $result = json_decode($personalCliente->getNumEmpleado($array["data"][0]->numEmpleadoAprobo));
                if(isset($result->data[0]->TituloTrato)){
                         $aprobo = $result->data[0]->TituloTrato ." ".$result->data[0]->Nombre." ".$result->data[0]->Paterno." ".$result->data[0]->Materno;    
                }else{
                    $aprobo = "usuario externo";
                }
            }else{
                $aprobo = "";
            }
            
            
            
            ?>
    <table cellpadding="0" cellspacing="0" style="width: 100%;font-size: 10px;text-align: center;">
        <col style="width: 30%">
        <col style="width: 5%">
        <col style="width: 30%">
        <col style="width: 5%">
        <col style="width: 30%">
        <tr>
            <td style="background-color: #CCC;border:solid .5px">SOLICITA</td>
            <td>&nbsp;</td>
            <td style="background-color: #CCC;border:solid .5px">REVIS&Oacute;</td>
            <td>&nbsp;</td>
            <td style="background-color: #CCC;border:solid .5px">VO. BO.</td>
        </tr>
        <tr>
            <td style="border:solid .5px"><?php echo utf8_encode($res);?></td>
            <td>&nbsp;</td>
            <td style="border:solid .5px">SUB. DE CTROL. PRESUPUESTAL</td>
            <td>&nbsp;</td>
            <td style="border:solid .5px">DIRECCI&Oacute;N DE FINANZAS</td>
        </tr>
        <tr>
            
            
            
            <td style="border:solid .5px;height: 60px;">&nbsp;</td>
            <td> &nbsp;</td>
            <td style="border:solid .5px;height: 60px;">&nbsp;</td>
            <td>&nbsp;</td>
            <td style="border:solid .5px;height: 60px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border:solid .5px"><?php echo $elaboro;?></td>
            <td>&nbsp;</td>
            <td style="border:solid .5px"><?php echo $reviso;?></td>
            <td>&nbsp;</td>
            <td style="border:solid .5px"><?php echo $aprobo;?></td>
        </tr>
    </table>
    <?php 
      $content = ob_get_clean();
    $arrayConfig = array(
                "orientation" => "P",
                "format" => "LEGAL",
                "langue" => "es",
                "unicode" => true,
                "encoding" => 'UTF-8',
                "marges" => array(0, 0, 0, 0));
    
    $d = array();
    $sql = array("campos" => "*",
        "tablas" => " tbldocumentosimg I inner join tbltiposdocumentos T ON(I.cveTipoDocumento = T.cveTipoDocumento)",
        "where" => "I.idDocumentoImg =" . $guardarSuficienciaRS["data"][0]["idDocumentoImg"]);
    $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
      $consultaDoc = $genericDao->select($param);
    
    $imagenes->crearImagenesByHTML($arrayConfig, $content, $consultaDoc, null);
    
    
    $encode = new Encode_JSON;
    return array("status" => "success", "totalCount" => 1, "msg" => "Correcto");
                }
    
   
}
    ?>

