<?php
$fechahoy = date("YmdHis");
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="ISO-8859-1">
        <meta name="description" content="Dashboard" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <!--Basic Styles-->
        <link href="../../../css/bootstrap.min.css" rel="stylesheet" />
        <link id="bootstrap-rtl-link" href="" rel="stylesheet" />
        <link href="../../../css/font-awesome.min.css" rel="stylesheet" />
        <link href="../../../css/weather-icons.min.css" rel="stylesheet" />
        <link id="" href="../../../css/beyond.min.css" rel="stylesheet" type="text/css" />
        <link href="../../../css/demo.min.css" rel="stylesheet" />
        <link href="../../../css/typicons.min.css" rel="stylesheet" />
        <link href="../../../css/animate.min.css" rel="stylesheet" />
        <link href="../../../css/dataTables.bootstrap.css" rel="stylesheet" />
        <link href="../../../css/bootstrap-datetimepicker.css" rel="stylesheet" />
        <link href="../../../css/jquery-ui.css" rel="stylesheet" />
        <link href="../../../css/loadercss.css" rel="stylesheet" />


        <script src="../../skins.min.js"></script>
        <script src="../../autosize.js"></script>
        <script type="text/javascript" src="../../jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="../../jquery-2.0.3-ui.min.js"></script>
        <script type="text/javascript" src="../../jquery.numeric.js"></script>


        <script type="text/javascript" src="paso1.js?v=<?php echo $fechahoy; ?>"></script>

        <style type="text/css">
            .ui-autocomplete {
                max-height: 100px;
                overflow-y: auto;
                /* prevent horizontal scrollbar */
                overflow-x: hidden;
            }
            /* IE 6 doesn't support max-height
            * we use height instead, but this forces the menu to always be this tall
            */
            * html .ui-autocomplete {
                height: 100px;
            }

            .large_modal    {
                width:60% !important;
            }

            .btn-fileUpload {
                position: relative;
                overflow: hidden;
                margin: 10px;
            }

            .btn-fileUpload input.upload {
                position: absolute;
                top: 0;
                right: 0;
                margin: 0;
                padding: 0;
                font-size: 20px;
                cursor: pointer;
                opacity: 0;
                filter: alpha(opacity=0);
            }

            #loadingDiv {
                position: absolute;
                width: 100%;
                height: 100%;
                left: 0;
                top: 0;
                background: white url('img/cargando.gif') center center no-repeat;
            }

            .select2-hidden-accessible {
                display: none;
            }

            .sinfondo {
                background: #FFF;
            }

            .ui-datepicker-div {
                z-index: 9999999 !important;
            }

            .page-sidebar .sidebar-menu .submenu > li > a {
                padding-left: 25px !important;
            }
        </style>
        <title>Paso 1</title>
    </head>
    <body>
        <div class="tab-pane in active" id="REGISTRAR">
            <div class="form-group">
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="iId_Juzgado">JUZGADOS</label>
                    <div class="col-sm-9">
                        <div style="width:100%; margin: 2px 2px 2px 2px;" id="s2id_iId_Juzgado" class="select2-container select2">
                            <!--<input type="text" id="cmbJuzgado" style=" margin: 2px 2px 2px 2px; width: 100%; height: 30px; padding-right: 25px; background: url('../../../img/sort_both.png')  no-repeat scroll 0 0 transparent; background-position: right;" class="autocomplete" placeholder="JUZGADOS" />-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="iId_TipoCarpeta">TIPO CARPETA</label>
                        <div class="col-sm-9">
                            <div style="width:100%;  margin: 2px 2px 2px 2px;" id="s2id_iId_TipoCarpeta" class="select2-container select2">
                                <!--<input type="text" id="cmbTipocarpeta" style=" margin: 2px 2px 2px 2px; width: 100%; height: 30px; padding-right: 25px; background: url('../../../img/sort_both.png')  no-repeat scroll 0 0 transparent; background-position: right;" class="autocomplete" placeholder="TIPO CARPETA" />-->
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="col-sm-3 control-label no-padding-right" for="iNumero">N&Uacute;MERO</label>
                        <div class="col-sm-3">
                            <input type="text" class="numerico"  id="txtnumero" maxlength="4" style=" margin: 2px 2px 2px 2px; width: 100%; height: 30px;"  placeholder="N&Uacute;MERO" />
                        </div>
                        <label class="col-sm-1 control-label no-padding-right" for="iAnio">A&Ntilde;O</label>
                        <div class="col-sm-3">
                            <div class="input-group" style="margin: 2px 2px 2px 2px; width: 100%; height: 30px;">
                                <input style=" width: 100%" class="numerico"  maxlength="4" placeholder="A&Ntilde;O" name="sAnio" id="txtAnio" type="text">
                            </div>
                        </div>
                    </div>
                    <label class="col-sm-3 control-label no-padding-right" for="iId_Audiencia">TIPO DE AUDIENCIA</label>
                    <div class="col-sm-9">
                        <div style="width:100%;  margin: 2px 2px 2px 2px;" id="s2id_iId_Audiencia" class="select2-container select2">                        
                            <!--<input type="text" id="cmbAudiencia" style=" width: 100%; margin: 2px 2px 2px 2px; height: 30px; padding-right: 25px; background: url('../../../img/sort_both.png')  no-repeat scroll 0 0 transparent; background-position: right;" class="autocomplete" placeholder="TIPO DE AUDIENCIA" />-->
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="col-sm-3 control-label no-padding-right" for="sCarpetaInv">CARPETA INVESTIGACI&Oacute;N</label>
                        <div class="col-sm-9">
                            <div class="input-group" style=" width: 100%; height: 30px;">
                                <input style=" width: 100%"  maxlength="30" placeholder="CARPETA INVESTIGACI&Oacute;N"  name="sCarpetaInv" id="txtCarpetaInv" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="col-sm-3 control-label no-padding-right" for="sNuc">NUC (N&Uacute;MERO UNICO DE CAUSA)</label>
                        <div class="col-sm-9">
                            <div class="input-group " style=" width: 100%; height: 30px;">
                                <input style=" width: 100%" data-bv-field="sNuc" maxlength="30" placeholder="NUC (N&Uacute;MERO UNICO DE CAUSA)"  name="sNuc" id="txtNuc" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="iId_Consignacion">CONSIGNACI&Oacute;N</label>
                        <div class="col-sm-9">
                            <div style="width:100%;  margin: 2px 2px 2px 2px;" id="s2id_iId_Consignacion" class="select2-container select2">
                                <!--<input  type="text" id="cmbConsignacion" style=" margin: 2px 2px 2px 2px; width: 100%; height: 30px; padding-right: 25px; background: url('../../../img/sort_both.png')  no-repeat scroll 0 0 transparent; background-position: right;" class="autocomplete" placeholder="CONSIGNACI&Oacute;N" />-->
                            </div>
                           <!--<input title="CONSIGNACIÓN" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_consignaciones" name="iId_Consignacion" id="iId_Consignacion" class="select2" style="width: 100%; display: none;" type="hidden">-->
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="col-sm-3 control-label no-padding-right" for="iNumImputados">N&Uacute;MERO DE IMPUTADOS</label>
                        <div class="col-sm-9">
                            <input type="text" id="txtNoImputados" style=" margin: 2px 2px 2px 2px; width: 100%; height: 30px;" class="autocomplete" placeholder="N&Uacute;MERO DE IMPUTADOS" />
                        </div>
                    </div>
                    <div class="form-group has-feedback"><label class="col-sm-3 control-label no-padding-right" for="iNumOfendidos">N&Uacute;MERO DE OFENDIDOS</label>
                        <div class="col-sm-9">
                            <input type="text" id="txtNoOfendidos" style=" margin: 2px 2px 2px 2px; width: 100%; height: 30px;" class="autocomplete" placeholder="N&Uacute;MERO DE OFENDIDOS" />
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="col-sm-3 control-label no-padding-right" for="iNumDelitos">N&Uacute;MERO DE DELITOS</label>
                        <div class="col-sm-9">
                            <input type="text" id="txtNoDelitos" style=" margin: 2px 2px 2px 2px; width: 100%; height: 30px;" class="autocomplete" placeholder="N&Uacute;MERO DE DELITOS" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="sObservaciones">OBSERVACIONES</label>
                        <div class="col-sm-9">
                            <textarea id="txtObservaciones" style=" margin: 2px 2px 2px 2px; width: 100%;" class="form-control"  placeholder="OBSERVACIONES" rows="3"></textarea>
                        </div>
                    </div>

                    <br/>
                    <br/>
                </div>
            </div>
        </div>
    </body>
</html>
