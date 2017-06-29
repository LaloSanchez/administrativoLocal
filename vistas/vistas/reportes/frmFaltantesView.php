<?php
$numEmpleado = @($_GET["ne"]);
?>

<!doctype html>
<html>
    <head>
        <meta name="description" content="Dashboard" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
        <style type="text/css">
            .tituloModal {
                color: white !important;
            }
            .modal-header {
                padding: 10px 15px 10px 20px;
                background-color: #881518 !important;
                /* color: white; */
            }
            .encabezado{
                background: #941822;
                position: fixed;
            }
            .logoEncabezado{
                margin: 10px;
                margin-left: 4%;
            }
            .areadetrabajo{
                margin-top: 50px;
            }
            .bold-title{
                font-family: Roboto,sans-serif  !important;
                font-size: 18px;
                color: #646469 ;
                font-weight: bold !important;
            }
            .checkbox label:after, 
        .radio label:after {
            content: '';
            display: table;
            clear: both;
        }

        .checkbox .cr,
        .radio .cr {
            position: relative;
            display: inline-block;
            border: 1px solid #a9a9a9;
            border-radius: .25em;
            width: 1.3em;
            height: 1.3em;
            float: left;
            margin-right: .5em;
            background: #881518;
        }

        .radio .cr {
            border-radius: 50%;
        }

        .checkbox .cr .cr-icon,
        .radio .cr .cr-icon {
            position: absolute;
            font-size: .8em;
            line-height: 0;
            top: 50%;
            left: 20%;
            color:#d6d1c4;
        }
        .radio .cr .cr-icon {
            margin-left: 0.04em;
        }
        .checkbox label input[type="checkbox"],
        .radio label input[type="radio"] {
            display: none;
        }
        .checkbox label input[type="checkbox"] + .cr > .cr-icon,
        .radio label input[type="radio"] + .cr > .cr-icon {
            transform: scale(3) rotateZ(-20deg);
            opacity: 0;
            transition: all .3s ease-in;
        }
        .checkbox label input[type="checkbox"]:checked + .cr > .cr-icon,
        .radio label input[type="radio"]:checked + .cr > .cr-icon {
            transform: scale(1) rotateZ(0deg);
            opacity: 1;
        }
        .checkbox label input[type="checkbox"]:disabled + .cr,
        .radio label input[type="radio"]:disabled + .cr {
            opacity: .5;
        }
        .hola{
            border-radius: 0 !important;
            color: white !important;
            margin-right: -1px;
            line-height: 12px;
            position: relative;
            z-index: 11;
        }
        .hola2{
            border-radius: 0 !important;
            color: black !important;
            margin-right: -1px;
            line-height: 12px;
            position: relative;
            z-index: 11;
        }
        </style>
    </head>
            <div class="registro-cotizacion panel panel-default panel-box-shadow">
                <div class="headerForm">
                    <h1>                                                            
                        Reporte de Bienes Faltantes y Sobrantes
                    </h1>
                    <h2>                                                            
                        Bienes en Resguardo
                    </h2>
                    <hr>
                </div>
                
                <div class="panel-body " style=" margin-top: -15px;">
                    <div id="formulario">
                        <div class="btn-add-new"> 
                            <div class="buttons-preview padding-10">
                                <a class="btn btn-primary btn-labeled" href="#" id="btnRegresar1" onclick="changeDivForm(2)">                    
                                    <i class="btn-label fa fa-arrow-left"></i> Regresar
                                </a>
                            </div>  
                        </div>
                        <div class="col-md-12" id="divDatosEmpleado"></div>
                        <div class="col-md-12">
                            <div class="card">
                                <ul class="nav nav-tabs" role="tablist" id="tabListSeguimiento" style="background: #8e1619;">
                                    <li id="tab-informacion" role="presentation" class='active'><a href="#informacion" aria-controls="informacion" role="tab" data-toggle="tab" class="informacion active"><i class="fa fa-list"></i>Inventariado</a></li>
                                    <li id="tab-faltantes" role="presentation"><a href="#faltantes" aria-controls="faltantes" role="tab" data-toggle="tab" class='faltantes hola' onclick="validarLugar(2)"><i class="fa fa-minus-square"></i>Faltantes</a></li>
                                    <li id="tab-sobrantes" role="presentation"><a href="#sobrantes" aria-controls="sobrantes" role="tab" data-toggle="tab" class='sobrantes hola' onclick="validarLugar(3)"><i class="fa fa-plus-square"></i>Sobrantes</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="informacion">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id='divTabla'></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="faltantes">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id='divTablaFaltante'></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <center>
                                                        <div class="btn-add-new"> 
                                                            <div class="buttons-preview padding-10">
                                                                <button class="btn btn-primary btn-labeled" href="#" id="btnCargarDatos" onclick="excel()">                    
                                                                    <i class="btn-label fa fa-file-excel-o"></i> Excel
                                                                </button>
                                                                <button class="btn btn-primary btn-labeled" href="#" id="btnExpPdfFaltantes">                    
                                                                    <i class="btn-label fa fa-file-pdf-o"></i> Pdf
                                                                </button>
                                                            </div>  
                                                        </div>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="sobrantes">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id='divTablaSobrante'></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <center>
                                                        <div class="btn-add-new"> 
                                                            <div class="buttons-preview padding-10">
                                                                <button class="btn btn-primary btn-labeled" href="#" id="btnCargarDatos" onclick="excel()">                    
                                                                    <i class="btn-label fa fa-file-excel-o"></i> Excel
                                                                </button>
                                                                <button class="btn btn-primary btn-labeled" href="#" id="btnExpPdfSobrantes">                    
                                                                    <i class="btn-label fa fa-file-pdf-o"></i> Pdf
                                                                </button>
                                                            </div>  
                                                        </div>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="info">
                        <div class="col-md-6">
                            <div class="checkbox">
                                <label style="font-size: 1em">Por Adscripci&oacute;n 
                                    <input class="tipoAdscripcion" type="checkbox"  onclick="alertaChecked(1)">
                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="checkbox">
                                <label style="font-size: 1em">Por Empleado 
                                    <input class="tipoNumEmpleado" type="checkbox" onclick="alertaChecked(2)">
                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12" id="divSolicitud" style="display:none;">
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label class=" col-md-12 needed titleCombo">Adscripci&oacute;n:</label>
                                    <div class="col-md-12">
                                        <select id="cveAdscripcion" name="cveAdscripcion" class="form-control mdb-select">
                                            <option value="">Seleccione una opci&oacute;n</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="divComboEmpleado">
                                <div class="form-group"> 
                                    <label class=" col-md-12 needed titleCombo">Empleado:</label>
                                    <div class="col-md-12">
                                        <select id="numEmpleadoCombo" name="numEmpleadoCombo" class="form-control mdb-select">
                                            <option value="">Seleccione una opci&oacute;n</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <div class="btn-add-new"> 
                                            <div class="buttons-preview padding-10">
                                                <button class="btn btn-primary btn-labeled" href="#" id="btnCargarDatos" onclick="validarLugar(1)">                    
                                                    <i class="btn-label fa fa-save"></i> Cargar
                                                </button>
                                            </div>  
                                        </div>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="excel" style="display:none;">
                        <center>
                            <table style='border-collapse:collapse;' id='tblTitulo' class='list-unstyled'>
                                <tr>
                                    <td ALIGN='left' colspan="2">
                                        <img src='http://sigejupe2.pjedomex.gob.mx/sigejupe/vistas/img/logoPj.png' width=100px style="width: 100px;"></td>
                                    <td ALIGN='CENTER' COLSPAN=3><b>
                                        <label style=' font-family: Arial; font-size: 20px; font-weight: 501 !important;'>
                                            Poder Judicial del Estado de M&eacute;xico <br>
                                            <div id="subtituloExcel"></div><br>
                                        </label></b>
                                    </td>
                                    <td colspan=3 align="right"><img src='http://administrativo.pjedomex.gob.mx/administrativo/vistas/img/EdoMexReporte.png' width=200px></td>
                                </tr>
                                <tr><td  colspan="9">&nbsp;</td></tr>
                                <tr><td  colspan="9">&nbsp;</td></tr>
                            </table>
                            <div id="tableExcel"></div>
                        </center>
                    </div>
                </div>
            </div>  
</html>
    <script>
        $(function (){
            $('.mdb-select').chosen({allow_single_deselect: true, placeholder_text_single: "Seleccione una opci\u00F3n"});
        });
        $(document).ready(function() {
            changeDivForm(2);
            cargarAdscripciones();
//            getEmpleado();
//            cargarDatosEmpleado();
        });
        function cargarAdscripciones() {
            var archivo = "../archivos/informacionEmpleados.json";
            $.getJSON(archivo, function (datos) {
                $("#cveAdscripcion").empty();
                var option = '<option value =""> Seleccione una opci\u00f3n</option>';
                $.each(datos.data, function (index, elementos) {
                    option += '<option  value="' + elementos.idJuzgado + '">' + elementos.desJuz + '</option>';
                });
                $("#cveAdscripcion").append(option);
                $('#cveAdscripcion').trigger('chosen:updated');
            });
        }
        $("#cveAdscripcion").on("change",function(){
            cargarEmpleadosAdscripcion();
        });
        function cargarEmpleadosAdscripcion(){
            var archivo = "../archivos/informacionEmpleados.json";
            $.getJSON(archivo, function (datos) {
                $("#numEmpleadoCombo").empty();
                var option = '<option value =""> Seleccione una opci\u00f3n</option>';
                $.each(datos.data, function (index, elementos) {
                    if(elementos.idJuzgado == $("#cveAdscripcion").val()){
                        $.each(elementos.personal, function (index2, elementos2) {
                            if(elementos2.CveStatus == 1){
                                option += '<option  value="' + elementos2.NumEmpleado + '">' + elementos2.Nombre + ' ' + elementos2.Paterno + ' ' + elementos2.Materno + '</option>';
                            }
                        });
                    }
                });
                $("#numEmpleadoCombo").append(option);
                $('#numEmpleadoCombo').trigger('chosen:updated');
            });
        }
        function validarLugar(tipo){
            console.log(tipo);
            if(tipo == 1){
                $("#tab-informacion").addClass("active");
                $("#sobrantes").removeClass("active");
                $("#faltantes").removeClass("active");
                $("#informacion").addClass("active");
                $("#tab-sobrantes").removeClass("active");
                $("#tab-faltantes").removeClass("active");
                $(".informacion").removeClass("hola");
                $(".sobrantes").addClass("hola");
                $(".faltantes").addClass("hola");
                if($(".tipoAdscripcion").is(":checked")){
                    cargarDatosAdscripcion();
                }else{
                    cargarDatosEmpleado();
                }
            }else if(tipo == 2){
                if($(".tipoAdscripcion").is(":checked")){
                    cargarFaltantesAdscripcion();
                }else{
                    cargarFaltantes();
                }
            }else{
                if($(".tipoAdscripcion").is(":checked")){
                    cargarSobrantesAdscripcion();
                }else{
                    cargarSobrantes();
                }
            }
        }
        function alertaChecked(tipo){
            if(tipo == 1){
                $(".tipoNumEmpleado").attr('checked', false);
                if($(".tipoAdscripcion").is(":checked")){
                    $("#divSolicitud").show();
                }else{
                    $("#divSolicitud").hide();
                }
                $("#divComboEmpleado").hide();
            }else{
                $(".tipoAdscripcion").attr('checked', false);
                if($(".tipoNumEmpleado").is(":checked")){
                    $("#divSolicitud").show();
                }else{
                    $("#divSolicitud").hide();
                }
                $("#divComboEmpleado").show();
            }
        }
        function cargarDatosEmpleado(){
            getEmpleado($("#numEmpleadoCombo").val());
            $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "cargarDatosEmpleado",
                    numEmpleado: $("#numEmpleadoCombo").val()
                },
                success: function (datos) {
                    try{
                    datos=eval("("+datos+")");
                        var table="";
                        table +='<table class="table table-striped table-bordered " id="tableRegistro"  style="width:100%;">';
                        table +='    <thead class="bordered-darkorange">';
                        table +='        <tr role="row">';
                        table +='            <th>Codigo</th>';
                        table +='            <th>Denominaci&oacute;n</th>';
                        table +='            <th>N&uacute;mero de Serie</th>';
                        table +='            <th>Precio Actual</th>';
                        table +='            <th>Estatus</th>';
                        table +='        </tr>';
                        table +='    </thead>';
                        table +='    <tbody>';
                        var contador=0;
                        $.each(datos.data, function(index,element){
                            var checked = "";
                            if(element.cveEstatus == 122){
                                contador++;
                                checked="checked"
                            }
                            table +='<tr>';
                            table +='    <td>'+element.codigoPropio+'</td>';
                            table +='    <td>'+element.denominacion+'</td>';
                            if(element.numeroSerie != "" && element.numeroSerie != null){
                                table +='    <td>'+element.numeroSerie+'</td>';
                            }else{
                                table +='    <td>N/A</td>';
                            }
                            table +='    <td>'+element.precioActual+'</td>';
                            table +='    <td>'+element.desEstatus+'</td>';
                            table +='</tr>';
                        });
                        table +='    </tbody>';
                        table +='</table>';
                        $("#divTabla").html(table);
                        cargarDatatable('tableRegistro');
                        if(contador == datos.totalCount){
                            $("#btnFinalizarRegistro").attr("disabled",true);
                        }else{
                            $("#btnFinalizarRegistro").attr("disabled",false);
                        }
                        $("#")
                    }catch(err){
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center>Error</center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        function cargarDatosAdscripcion(){
            getAdscripcion($("#cveAdscripcion").val());
            $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "cargarDatosAdscripcion",
                    cveAdscripcion: $("#cveAdscripcion").val()
                },
                success: function (datos) {
                    try{
                    datos=eval("("+datos+")");
                        var table="";
                        table +='<table class="table table-striped table-bordered " id="tableRegistro"  style="width:100%;">';
                        table +='    <thead class="bordered-darkorange">';
                        table +='        <tr role="row">';
                        table +='            <th>Codigo</th>';
                        table +='            <th>Denominaci&oacute;n</th>';
                        table +='            <th>N&uacute;mero de Serie</th>';
                        table +='            <th>Precio Actual</th>';
                        table +='            <th>Estatus</th>';
                        table +='            <th>Empleado</th>';
                        table +='        </tr>';
                        table +='    </thead>';
                        table +='    <tbody>';
                        var contador=0;
                        $.each(datos.data, function(index,element){
                            var checked = "";
                            if(element.cveEstatus == 122){
                                contador++;
                                checked="checked"
                            }
                            table +='<tr>';
                            table +='    <td>'+element.codigoPropio+'</td>';
                            table +='    <td>'+element.denominacion+'</td>';
                            if(element.numeroSerie != "" && element.numeroSerie != null){
                                table +='    <td>'+element.numeroSerie+'</td>';
                            }else{
                                table +='    <td>N/A</td>';
                            }
                            table +='    <td>'+element.precioActual+'</td>';
                            table +='    <td>'+element.desEstatus+'</td>';
                            table +='    <td>'+element.nombreEmpleado+'</td>';
                            table +='</tr>';
                        });
                        table +='    </tbody>';
                        table +='</table>';
                        $("#divTabla").html(table);
                        cargarDatatable('tableRegistro');
                        if(contador == datos.totalCount){
                            $("#btnFinalizarRegistro").attr("disabled",true);
                        }else{
                            $("#btnFinalizarRegistro").attr("disabled",false);
                        }
                    }catch(err){
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center>Error</center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        function cargarFaltantes(){
            $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "cargarFaltantes",
                    numEmpleado: $("#numEmpleadoCombo").val()
                },
                success: function (datos) {
                    try{
                        datos=eval("("+datos+")");
                            var table="";
                            table +='<table class="table table-striped table-bordered " id="tableFaltante"  style="width:100%;">';
                            table +='    <thead class="bordered-darkorange">';
                            table +='        <tr role="row">';
                            table +='            <th>Codigo</th>';
                            table +='            <th>Denominaci&oacute;n</th>';
                            table +='            <th>N&uacute;mero de Serie</th>';
                            table +='            <th>Precio Actual</th>';
                            table +='        </tr>';
                            table +='    </thead>';
                            table +='    <tbody>';
                            var contador=0;
                            $.each(datos.data, function(index,element){
                                table +='<tr>';
                                table +='    <td>'+element.codigoPropio+'</td>';
                                table +='    <td>'+element.denominacion+'</td>';
                                if(element.numeroSerie != "" && element.numeroSerie != null){
                                    table +='    <td>'+element.numeroSerie+'</td>';
                                }else{
                                    table +='    <td>N/A</td>';
                                }
                                table +='    <td>'+element.precioActual+'</td>';
                                table +='</tr>';
                            });
                            table +='    </tbody>';
                            table +='</table>';
                            $("#divTablaFaltante").html(table);
                            $("#tableExcel").html(table);
                            $("#subtituloExcel").text("Reporte de Bienes Faltantes");
                            $("#btnExpPdfFaltantes").attr("onclick","generarPdfEmpleado(1)");
                            cargarDatatable('tableFaltante');
                    }catch(err){
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center>Error</center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        function cargarFaltantesAdscripcion(){
            $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "cargarFaltantesAdscripcion",
                    cveAdscripcion: $("#cveAdscripcion").val()
                },
                success: function (datos) {
                    try{
                        datos=eval("("+datos+")");
                            var table="";
                            table +='<table class="table table-striped table-bordered " id="tableFaltante"  style="width:100%;">';
                            table +='    <thead class="bordered-darkorange">';
                            table +='        <tr role="row">';
                            table +='            <th>Codigo</th>';
                            table +='            <th>Denominaci&oacute;n</th>';
                            table +='            <th>N&uacute;mero de Serie</th>';
                            table +='            <th>Precio Actual</th>';
                            table +='            <th>Empleado</th>';
                            table +='        </tr>';
                            table +='    </thead>';
                            table +='    <tbody>';
                            var contador=0;
                            $.each(datos.data, function(index,element){
                                table +='<tr>';
                                table +='    <td>'+element.codigoPropio+'</td>';
                                table +='    <td>'+element.denominacion+'</td>';
                                if(element.numeroSerie != "" && element.numeroSerie != null){
                                    table +='    <td>'+element.numeroSerie+'</td>';
                                }else{
                                    table +='    <td>N/A</td>';
                                }
                                table +='    <td>'+element.precioActual+'</td>';
                                table +='    <td>'+element.nombreEmpleado+'</td>';
                                table +='</tr>';
                            });
                            table +='    </tbody>';
                            table +='</table>';
                            $("#divTablaFaltante").html(table);
                            $("#tableExcel").html(table);
                            $("#tableExcel").html(table);
                            $("#btnExpPdfFaltantes").attr("onclick","generarPdfAdscripcion(1)");
                            $("#subtituloExcel").text("Reporte de Bienes Faltantes");
                            cargarDatatable('tableFaltante');
                    }catch(err){
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center>Error</center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        function getAdscripcion(ads){
             var archivo = "../archivos/informacionEmpleados.json";
            $.getJSON(archivo, function (datos) {
                var html ="";
                var cont = 0;
                $.each(datos.data, function (index, elementos) {
                    if(elementos.idJuzgado == ads){
                        cont=1;
                        html +='<table class="table table-striped table-bordered " id="tablaGeneric" style="width:100%;">';
                        html +='    <tbody><tr role="row">';
                        html +='        <td style="width: 100%;text-align: center; font-size: 16px; font-weight: bold;">'+elementos.desJuz+'</td>';
                        html +='    </tr>';
                        html +='    </tbody>';
                        html +='</table>';
                        $("#divDatosEmpleado").html(html);
                    }
                });
                if(cont == 0){
                    $.alert({
                        title: '<center style="color:#881518;">Error!</center>',
                        content: '<center> No se encontrar\u00f3n datos para esta Adscripci\u00f3n </center>',
                        confirmButton: 'Aceptar'
                    });
                }else{
                    changeDivForm(1);
                }
            });
        }
        function getEmpleado(numEmpleado){
           var archivo = "../archivos/informacionEmpleados.json";
            $.getJSON(archivo, function (datos) {
                var html ="";
                var cont = 0;
                $.each(datos.data, function (index, elementos) {
                    $.each(elementos.personal, function (index2, elementos2) {
                        if(elementos2.NumEmpleado == numEmpleado){
                            cont=1;
                            html +='<table class="table table-striped table-bordered " id="tablaGeneric" style="width:100%;">';
                            html +='    <tbody><tr role="row">';
                            html +='        <td style=" width: 50%;text-align: center; font-size: 16px; font-weight: bold;">'+elementos2.Nombre+' '+elementos2.Paterno+' '+elementos2.Materno+'</td>';
                            html +='        <td style="width: 50%;text-align: center; font-size: 16px; font-weight: bold;">'+elementos.desJuz+'</td>';
                            html +='    </tr>';
                            html +='    </tbody>';
                            html +='</table>';
                            $("#divDatosEmpleado").html(html);
                        }
                    });
                });
                if(cont == 0){
                    $.alert({
                        title: '<center style="color:#881518;">Error!</center>',
                        content: '<center> No se encontrar\u00f3n datos para este Numero de Empleado </center>',
                        confirmButton: 'Aceptar'
                    });
                }else{
                    changeDivForm(1);
                }
            });
        }
        function cargarSobrantes(){
            $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "cargarSobrantes",
                    numEmpleado: $("#numEmpleadoCombo").val()
                },
                success: function (datos) {
                    try{
                        datos=eval("("+datos+")");
                            var table="";
                            table +='<table class="table table-striped table-bordered " id="tableSobrante"  style="width:100%;">';
                            table +='    <thead class="bordered-darkorange">';
                            table +='        <tr role="row">';
                            table +='            <th>Codigo</th>';
                            table +='            <th>Denominaci&oacute;n</th>';
                            table +='            <th>N&uacute;mero de Serie</th>';
                            table +='            <th>Precio Actual</th>';
                            table +='        </tr>';
                            table +='    </thead>';
                            table +='    <tbody>';
                            var contador=0;
                            $.each(datos.data, function(index,element){
                                table +='<tr>';
                                table +='    <td>'+element.codigoPropio+'</td>';
                                table +='    <td>'+element.denominacion+'</td>';
                                if(element.numeroSerie != "" && element.numeroSerie != null){
                                    table +='    <td>'+element.numeroSerie+'</td>';
                                }else{
                                    table +='    <td>N/A</td>';
                                }
                                table +='    <td>'+element.precioActual+'</td>';
                                table +='</tr>';
                            });
                            table +='    </tbody>';
                            table +='</table>';
                            $("#divTablaSobrante").html(table);
                            $("#tableExcel").html(table);
                            $("#subtituloExcel").text("Reporte de Bienes Sobrantes");
                            $("#btnExpPdfSobrantes").attr("onclick","generarPdfEmpleado(2)");
                            cargarDatatable('tableSobrante');
                    }catch(err){
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center>Error</center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        function cargarSobrantesAdscripcion(){
            $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "cargarSobrantesAdscripcion",
                    cveAdscripcion: $("#cveAdscripcion").val()
                },
                success: function (datos) {
                    try{
                        datos=eval("("+datos+")");
                            var table="";
                            table +='<table class="table table-striped table-bordered " id="tableSobrante"  style="width:100%;">';
                            table +='    <thead class="bordered-darkorange">';
                            table +='        <tr role="row">';
                            table +='            <th>Codigo</th>';
                            table +='            <th>Denominaci&oacute;n</th>';
                            table +='            <th>N&uacute;mero de Serie</th>';
                            table +='            <th>Precio Actual</th>';
                            table +='            <th>Empleado</th>';
                            table +='        </tr>';
                            table +='    </thead>';
                            table +='    <tbody>';
                            var contador=0;
                            $.each(datos.data, function(index,element){
                                table +='<tr>';
                                table +='    <td>'+element.codigoPropio+'</td>';
                                table +='    <td>'+element.denominacion+'</td>';
                                if(element.numeroSerie != "" && element.numeroSerie != null){
                                    table +='    <td>'+element.numeroSerie+'</td>';
                                }else{
                                    table +='    <td>N/A</td>';
                                }
                                table +='    <td>'+element.precioActual+'</td>';
                                table +='    <td>'+element.nombreEmpleado+'</td>';
                                table +='</tr>';
                            });
                            table +='    </tbody>';
                            table +='</table>';
                            $("#divTablaSobrante").html(table);
                            $("#tableExcel").html(table);
                            $("#subtituloExcel").text("Reporte de Bienes Sobrantes");
                            $("#btnExpPdfSobrantes").attr("onclick","generarPdfAdscripcion(2)");
                            cargarDatatable('tableSobrante');
                    }catch(err){
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center>Error</center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        function changeDivForm(aux){
            switch(aux){
                case 1:
                    $("#info").hide();
                    $("#formulario").show();
                    $("#btnAgrgarBien").show();
                    $("#instrucciones").show();
                break;
                case 2:
                    $("#info").show();
                    $("#formulario").hide();
                    $("#btnAgrgarBien").hide();
                    $("#instrucciones").hide();
                break;
            }
        }
        function cargarDatatable(nombre) {
            tableD=$.extend($.fn.dataTable.defaults,{
                language: {
                    search: "Buscar",
                    sLengthMenu: "Registros: _MENU_",
                    sZeroRecords: "No se encontraron registros",
                    sProcessing: "Realizando la busqueda espere....",
                    sLoadingRecords: "Cargando...",
                    sInfo: "Mostrando del _START_ a _END_ (Total: _TOTAL_ resultados)",
                    infoFiltered: "",
                    paginate: {
                        first: "Primer",
                        sPrevious: "Anterior",
                        sNext: "Siguiente",
                        last: "Ultimo"
                    }
                }
            });
            $('#' + nombre).DataTable();
        }
        function excel(){
            var dt = new Date();
            var day = dt.getDate();
            var month = dt.getMonth() + 1;
            var year = dt.getFullYear();
            var hour = dt.getHours();
            var mins = dt.getMinutes();
            var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
            var a = document.createElement('a');
            var data_type = 'data:application/vnd.ms-excel; charset=UTF-8,%EF%BB%BF';
            var table_div = document.getElementById('excel');
            var table_html = table_div.outerHTML.replace(/ /g, '%20');
            a.href = data_type + ' ' + table_html;
            a.download = $("#subtituloExcel").text() + postfix + '.xls';
            // a.click();
            var click_ev = document.createEvent("MouseEvents");
            // initialize the event
            click_ev.initEvent("click", true, true);
            //trigger the evevnt
            a.dispatchEvent(click_ev);
        }
        function generarPdfAdscripcion(tipo){
            var parametros = '?cveAdscripcion=' + $('#cveAdscripcion').val()+'&tipo=1';
            if(tipo ==1){
                showPDF({
                    data: [
                       {cveDocumento: 1, type: 'Acuerdo', ruta: '../vistas/vistas/reportes/PDF_Faltantes.php' + parametros}
                    ]
                }, 'Reporte de Faltantes', false, 1);
            }else{
                showPDF({
                    data: [
                       {cveDocumento: 1, type: 'Acuerdo', ruta: '../vistas/vistas/reportes/PDF_Sobrantes.php' + parametros}
                    ]
                }, 'Reporte de Sobrantes', false, 1);
            }
        }
        function generarPdfEmpleado(tipo){
            var parametros = '?numEmpleado=' + $("#numEmpleadoCombo").val()+'&tipo=2';
            console.log(tipo == 1);
            console.log(tipo == "1");
            if(tipo == 1){
                showPDF({
                    data: [
                       {cveDocumento: 1, type: 'Acuerdo', ruta: '../vistas/vistas/reportes/PDF_Faltantes.php' + parametros}
                    ]
                }, 'Reporte de Faltantes', false, 1);
//                alert("hola");
            }else{
                showPDF({
                    data: [
                       {cveDocumento: 1, type: 'Acuerdo', ruta: '../vistas/vistas/reportes/PDF_Sobrantes.php' + parametros}
                    ]
                }, 'Reporte de Sobrantes', false, 1);
//                alert("hola2");
            }
        }
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            if ((target == '#informacion')) {
                $(".informacion").removeClass("hola");
                $(".sobrantes").addClass("hola");
                $(".faltantes").addClass("hola");
            } else if((target == '#sobrantes')){
                $(".informacion").addClass("hola");
                $(".sobrantes").removeClass("hola");
                $(".faltantes").addClass("hola");
            } else if((target == '#faltantes')){
                $(".informacion").addClass("hola");
                $(".sobrantes").addClass("hola");
                $(".faltantes").removeClass("hola");
            }
        });
    </script>
</html>