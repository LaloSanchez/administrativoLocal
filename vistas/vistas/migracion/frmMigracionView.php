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
            /*.alert{ display: none; }*/
            .modal-content{background-color: #881518 !important;}
            .modal-body{color: #fbfbfb !important;}
            #divHideForm{ display: none; position: absolute; width: 100%; height: 100vh; opacity: .5; z-index: 99999; background: #427468;}
            #divMenssage{ width: 100%; height: 40px; padding-top: 10px; padding-bottom: 10px; text-align: center; margin-top: 40vh; margin-bottom: auto; color: #284740; background: #FFFFFF; text-transform: uppercase; }
            #divImgloading{ background: #FFFFFF url(img/cargando_1.gif) no-repeat; background-position: center; width: 100%; height: 70px; margin-left: auto; margin-right: auto; }
            .panel panel-default{ background: #427468; color: #ebf3f1; }
            .panel-default{ padding: 20px !important; }
            .panel-heading{ background: #427468; color: #ebf3f1; }
            .panel-group .panel-heading{ background: #427468; color: #ebf3f1; }
            .panel-default > .panel-heading{ background: #427468; color: #ebf3f1; }
            .needed:after { color:darkred; content: " (*)"; }
            .textCorrection{ display: block; text-transform: capitalize; }
            .textCorrection:first-letter { text-transform: uppercase; }
            .capital{ text-transform: uppercase; }
            input, textarea{ resize: none; }

            #chosenForm .form-control-feedback {
                /* To make the feedback icon visible */
                z-index: 100;}

            #divImgFotoUsr{
                width: 45px;
                height: 45px;
                border-radius: 35px;
                border: solid 1px;
                background: #FF0000;
            }
            .control-label{
                color: #23473f;
            }

            #divHideForm{
                display: none;
                position: absolute;
                width: 100%;
                height: 100vh;
                opacity: .5;
                z-index: 99999;
                background: #427468;
            }

            #divMenssage{
                width: 100%;
                height: 40px;
                padding-top: 10px;
                padding-bottom: 10px;
                text-align: center;
                margin-top: 40vh;
                margin-bottom: auto;
                color: #284740;
                background: #FFFFFF;
                text-transform: uppercase;

            }

            #btnReIngresar{
                margin: 0px;
            }

            #divImgloading{
                background: #FFFFFF url(img/cargando_1.gif) no-repeat;
                background-position: center;
                width: 100%;
                height: 70px;
                margin-left: auto;
                margin-right: auto;
            }
            .panel panel-default{
                /*background: #427468;*/
                background: #881518  !important;
                color: #ebf3f1  !important;
            }

            .panel-heading{
                /*background: #427468;*/
                background: #881518  !important;
                color: #ebf3f1  !important;
            }

            .panel-group .panel-heading{
                /*background: #427468;*/
                background: #881518  !important;
                color: #ebf3f1  !important;
            }
            .panel-default > .panel-heading{
                /*background: #427468;*/
                background: #881518  !important;
                color: #ebf3f1  !important;
            }
            .divUserData{
                float: left;
                /*margin-top: 5px;*/
                /*margin-bottom: 10px;*/
                margin-right: 15px;
                /*padding: 5px;*/
                /*height: 43px;*/
            }

            .spanLblInfo{
                text-align: center;
                height: 43px;
                font-family: Arial;
                /*font-size: 12px;*/
                font-size: 15px;
                font-weight: bold;
                margin-top: auto;
                margin-bottom: auto;
                vertical-align: central;
                line-height: 35px;
            }


            .modal-footer{
                border: 0px;
                background: #FFFFFF;
            }


            .select2-hidden-accessible  {
                display: none;
            }

            .navbar .navbar-brand small img {
                height: 40px;
                width: 140px;
                margin-left: 15px;
            }


            body::before {

                content: "";
                display: block;
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: -1;
                /*background-color: #fbfbfb;*/
                background-color: rgba(204,204,204,0.41);

            }

            .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus {
                color: #ad1d22;
                text-decoration: none;
                background-color: #f5f5f5;
            }
            #divMenu > ul > li > ul > li > ul, .sm-nowrap{
                background-color:#646469 !important;
            }
            .navbar-default .navbar-toggle {
                border-color: #333;
            }
            .navbar-default .navbar-toggle .icon-bar {
                background-color: #fff;
            }
            .navbar-default .navbar-toggle:hover, .navbar-default .navbar-toggle:focus {
                background-color: #333;
            }

            .navbar-default {
                position: relative;
                /*background-color: #fefefe;*/
                background-color: #941822 ;
                /*border-color: #fff;*/
                min-height: 65px;
                border-radius: 4px;
                display: block;
            }
            /*            @media (min-width: 768px) {
                            .navbar-default {
                                border-radius: 4px;
                            }
                        }*/
            .navbar-default .navbar-nav>li>a {
                color: #fff !important;
            }
            .panel-default > .panel-heading {
                background: #df3338;
                color: #FFFFFF;
            }

            .btn-primary {
                background-color: #881518 !important;
                border-color: #881518;
                color: #fff;
            }

            .btn-primary:hover,
            .btn-primary:active,
            .btn-primary:focus {
                cursor: pointer;
                background-color: #df3338 !important;
            }
            #footerAvisos{
                z-index: 999;
                position: fixed;
                clear: both;
                width: 70%;
                /*height: 53px;*/
                bottom: 0;
                border: 0;
                padding: 13px 0 0 0;
                /*transitions*/
                -webkit-transition: max-height 0.8s;
                -moz-transition: max-height 0.8s;
                transition: max-height 0.8s;
            }
            #buttonAvisosOcultar{
                z-index: 999;
                position: fixed;
                clear: both;
                width: 115px;
                height: 42px;
                border: 1px solid #cecece;
                bottom: 0;
                cursor: pointer;
            }
            #container-principal{
                margin-bottom: 100px !important;
            }
            .botonesAdaptar{
                margin: 1px;
                padding: 0;
                width: auto;
            }
            .btn-adaptar{
                width: 100%;
                margin-bottom: 2px;
            }
            /*<style type="text/css">*/

            .alert{
                display: none;
            }

            #divHideForm{
                display: none;
                position: absolute;
                width: 100%;
                height: 100vh;
                opacity: .5;
                z-index: 99999;
                background: #427468;
            }

            #divMenssage{                
                width: 100%;
                height: 40px;
                padding-top: 10px;
                padding-bottom: 10px;
                text-align: center;
                margin-top: 40vh;
                margin-bottom: auto;
                color: #284740;
                background: #FFFFFF;
                text-transform: uppercase;

            }

            #divImgloading{                  
                background: #FFFFFF url(img/cargando_1.gif) no-repeat;
                background-position: center;
                width: 100%;
                height: 70px;
                margin-left: auto;
                margin-right: auto;
            }

            .panel panel-default{
                background: #427468;
                color: #ebf3f1;
            }

            .panel-heading{
                background: #427468;
                color: #ebf3f1;
            }

            .panel-group .panel-heading{
                background: #427468;
                color: #ebf3f1;
            }
            .panel-default > .panel-heading{
                background: #427468;        
                color: #ebf3f1;
            }
            .optionprom{
                height: 10px;
            }

            .required{
                color: red;
            }
            .needed:after {
                color:darkred;
                content: " (*)";
            }
            #bordeareadetrabajo{
                margin-top: 100px !important;
            }
            @media only screen and (min-width:320px){
                #bordeareadetrabajo{
                    margin:0px;
                }
                #areadetrabajo{
                    margin:0px;
                }
                .panel-body{ padding: 0px !important; }
            }
            @media only screen and (min-width:768px){
                #bordeareadetrabajo{
                    margin:3%;
                }
                #areadetrabajo{
                    /*margin:1%;*/
                }
                .panel-body{ padding: 10px !important; }
            }
            @media only screen and (min-width:1024px){
                #bordeareadetrabajo{
                    margin:4%;
                }
                #areadetrabajo{
                    /*margin:2%;*/
                }
                .panel-body{ padding: 15px !important; }
            }
            @media only screen and (min-width:1900px){
                #bordeareadetrabajo{

                    margin:5%;
                }
                #areadetrabajo{
                    /*margin:3%;*/
                }
                .panel-body{ padding: 20px !important; }
            }

            .panel-box-shadow {
                border-radius:6px !important;
                box-shadow: 0 16px 24px 2px rgba(0,0,0,0.14),0 6px 30px 5px rgba(0,0,0,0.12), 0 8px 10px -5px rgba(0,0,0,0.2);

            }
            #areadetrabajo{
                border-radius:6px;
            }
            .modal-backdrop.fade.in{
                z-index: 0;
            }
            .bold-subtitle{
                font-family: Roboto,sans-serif  !important;
                font-size: 14px;
                color: #646469 ;
                font-weight: bold !important;
            }
            .badge-notify{
                background: red;
                position: absolute;
                top: 16px;
                left: 43px;
            }
            /*.navbar{
                background:#941822;
            }
            .navbar-collapse{
                background:#941822;
                color:#ffffff;
            }*/
            .navbar-collapse{
                background:#941822;
                color:#ffffff;
            }
            /* CSS used here will be applied after bootstrap.css */

            .dropdown {
                display:inline-block;
                margin-left:20px;
                padding:10px;
            }


            .glyphicon-bell {

                font-size:1.5rem;
            }

            .notifications {
                /* min-width: 420px; */
                width: 342%;
                left: -235%;
            }

            .notifications-wrapper {
                overflow:auto;
                max-height: 50vh;
            }

            .menu-title {
                color: #881518;
                font-size:1.5rem;
                display:inline-block;
            }

            .glyphicon-circle-arrow-right {
                margin-left:10px;     
            }


            .notification-heading, .notification-footer  {
                padding:2px 10px;
            }


            .dropdown-menu.divider {
                margin:5px 0;          
            }

            .item-title {
                /*font-weight: bold;*/
                /*font-size:1.3rem;*/
                color:#000;

            }

            .notifications a.content {
                text-decoration:none;
                background:#ccc;

            }

            .notification-item {
                padding: 10px;
                margin: 5px;
                background: rgba(204, 204, 204, 0.27);
                border-radius: 4px;
            }


            .shake {
                -webkit-animation-name: shake;
                animation-name: shake;
                -webkit-animation-duration: 1s;
                animation-duration: 1s;
                -webkit-animation-fill-mode: both;
                animation-fill-mode: both;
                animation-iteration-count: 2    ;
                animation-delay: 15s;
            }
            @-webkit-keyframes shake {
                0%, 100% {
                    -webkit-transform: translate3d(0, 0, 0);
                    transform: translate3d(0, 0, 0);
                }
                10%, 30%, 50%, 70%, 90% {
                    -webkit-transform: translate3d(-10px, 0, 0);
                    transform: translate3d(-10px, 0, 0);
                }
                20%, 40%, 60%, 80% {
                    -webkit-transform: translate3d(10px, 0, 0);
                    transform: translate3d(10px, 0, 0);
                }
            }
            @keyframes shake {
                0%, 100% {
                    -webkit-transform: translate3d(0, 0, 0);
                    transform: translate3d(0, 0, 0);
                }
                10%, 30%, 50%, 70%, 90% {
                    -webkit-transform: translate3d(-10px, 0, 0);
                    transform: translate3d(-10px, 0, 0);
                }
                20%, 40%, 60%, 80% {
                    -webkit-transform: translate3d(10px, 0, 0);
                    transform: translate3d(10px, 0, 0);
                }
            }

            .alert-message
            {
                margin: 20px 0;
                padding: 20px;
                border-left: 3px solid #eee;
            }
            .alert-message h4
            {
                margin-top: 0;
                margin-bottom: 5px;
            }
            .alert-message p:last-child
            {
                margin-bottom: 0;
            }
            .alert-message code
            {
                background-color: #fff;
                border-radius: 3px;
            }
            .alert-message-success
            {
                background-color: #F4FDF0;
                border-color: #3C763D;
            }
            .alert-message-success h4
            {
                color: #3C763D;
            }
            .alert-message-danger
            {
                background-color: #fdf7f7;
                border-color: #d9534f;
            }
            .alert-message-danger h4
            {
                color: #d9534f;
            }
            .alert-message-warning
            {
                background-color: #fcf8f2;
                border-color: #f0ad4e;
            }
            .alert-message-warning h4
            {
                color: #f0ad4e;
            }
            .alert-message-info
            {
                background-color: #f4f8fa;
                border-color: #5bc0de;
            }
            .alert-message-info h4
            {
                color: #5bc0de;
            }
            .alert-message-default
            {
                background-color: #EEE;
                border-color: #B4B4B4;
            }
            .alert-message-default h4
            {
                color: #000;
            }
            .alert-message-notice
            {
                background-color: #FCFCDD;
                border-color: #BDBD89;
            }
            .alert-message-notice h4
            {
                color: #444;
            }
            .pointer{
                cursor: pointer;
            }

        </style>

        <title>Sistema de Gestion Administrativa Contable | Registro Cotizaci&oacute;n</title>
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
        </style>
    </head>
            <div class=" panel panel-default panel-box-shadow">
                <div class="headerForm">
                    <h1>
                        Migraci&oacute;n de datos
                    </h1>
                    <h2>                                                            
                        Sincronizaci&oacute;n de la informaci&oacute;n
                    </h2>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                       <div class="alert-message alert-message-default">
                            <div class="row">
                                <div class='col-md-6'>
                                    <h4>
                                        Instrucciones
                                    </h4>
                                    <p>
                                        - Si deseas descargar la informacion del sistema para poder hacer el levantamiento, selecciona las Adscripciones que necesitas y da click en el Bot&oacute;n Descargar Datos<br>
                                        - Si deseas subir la informaci&oacute;n del levantamiento, solo da click en el boton de Subir Datos.<br>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"> 
                                        <label class=" col-md-12 needed titleCombo">Adscripci&oacute;n:</label>
                                        <div class="col-md-12">
                                            <select id="cveAdscripcionCombo" name="cveAdscripcionCombo" class="form-control mdb-select">
                                                <option value="">Seleccione una opci&oacute;n</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body " style=" margin-top: -15px;">
                    <div class="col-md-12">
                        <div id="tablaAdscripciones" name="tablaAdscripciones"></div>
                    </div>
<!--                    <div class="row">
                            <div class="col-md-6">
                                <div id="cveAdscripcion" name="cveAdscripcion"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="numEmpleadoResguardo" name="numEmpleadoResguardo"></div>
                            </div>
                        </div>
                    </div>-->
                    <div class="col-md-12">
                        <div id='divTabla'></div>
                    </div>
                    <div class="col-md-12">
                        <center>
                            <div class="btn-add-new"> 
                                <div class="buttons-preview padding-10">
                                    <a class="btn btn-primary btn-labeled" href="#" id="btnRegresar1" onclick="eliminarTodas()">                    
                                        <i class="btn-label fa fa-eraser"></i> Limpiar
                                    </a>
                                    <a class="btn btn-primary btn-labeled" href="#" id="btnRegresar1" onclick="validarMigracion()">                    
                                        <i class="btn-label fa fa-cloud-download"></i> Descargar Datos
                                    </a>
                                    <a class="btn btn-primary btn-labeled" href="#" id="btnRegresar1" onclick="validarSubir()">                    
                                        <i class="btn-label fa fa-cloud-upload"></i> Subir Datos
                                    </a>
                                </div>  
                            </div>
                        </center>
                    </div>
                    <div id="pruebaInternet"></div>
                </div>
            </div>
    <script>
        function validarMigracion(){
            var infoLocalStorage=validarLocalStorage();
            console.log(infoLocalStorage);
            if(infoLocalStorage != ""){
                var dataArreglo=eval("("+localStorage.getItem('datos')+")");
                var listaAdscripciones= new Array();
                $.each(dataArreglo, function(index,element){
                    listaAdscripciones.push(element.idJuzgado);
                });
                $.confirm({
                    title: '<center style="color:#881518;">Advertencia!</center>',
                    content: ' <center><center>Se Perder&aacute;n los datos guardados, &iquest;Est&aacute;s Seguro de Actualizar la base de Datos?</center>',
                    confirmButton: 'Aceptar',
                    cancelButton: 'Cancelar',
                    animation: 'top',
                    closeAnimation: 'bottom',
                    confirm: function () {
                        migrarDatosDescarga(listaAdscripciones);
                    },
                    cancel: function () {
                                            // Aqui pones que quieres que haga cuando presionen cancelar
                    }
                });
            }else{
                $.alert({
                    title: '<center style="color:#881518;">Error!</center>',
                    content: '<center> No has Registrado Adscripciones </center>',
                    confirmButton: 'Aceptar'
                });
            }
        }
        function validarSubir(){
            $("#pruebaInternet").html("<img src='http://administrativo.pjedomex.gob.mx/administrativo/vistas/img/_logo.png' style='display:none;' onload='alert1(1)' onerror='alert1(2)'>");
        }
        function alert1(tipo){
            if(tipo == 1){
                validarCargarDatos();
            }else{
                $.alert({
                    title: '<center style="color:#881518;">Error!</center>',
                    content: '<center> No tienes conecci\u00f3n a internet </center>',
                    confirmButton: 'Aceptar'
                });
            }
        }
        function validarCargarDatos(){
            $.confirm({
                title: '<center style="color:#881518;">Advertencia!</center>',
                content: ' <center><center>Estas Seguro de registrar estos Art\u00edculos?</center>',
                confirmButton: 'Aceptar',
                cancelButton: 'Cancelar',
                animation: 'top',
                closeAnimation: 'bottom',
                confirm: function () {
                    cargarDatos();
                },
                cancel: function () {
                                        // Aqui pones que quieres que haga cuando presionen cancelar
                }
            });
        }
        function cargarDatos(){
            bootbox.dialog({message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Cargando...</div>',closeButton: false});
            $.ajax({
                type: "POST",
                url: "../fachadas/migracion/MigracionFacade.Class.php",
                async: true,
                dateType: "json",
                data: {
                    accion: "cargarDatos",
                },
                success: function (datos) {
                    try{
                        bootbox.hideAll();
                        datos=eval("("+datos+")");
                        if(datos.status ==  "success"){
                            $.alert({
                                title: '<center style="color:#00C851;;">Correcto!</center>',
                                content: '<center> Migraci&oacute;n Completa </center>',
                                confirmButton: 'Aceptar'
                            });
                        }else if(datos.status ==  "error"){
                            $.alert({
                                title: '<center style="color:#881518;">Error!</center>',
                                content: '<center>Ocurrio un error</center>',
                                confirmButton: 'Aceptar'
                            });
                        }else{
                            $.alert({
                                title: '<center style="color:#881518;">Error!</center>',
                                content: '<center>No hay Datos para Exportar</center>',
                                confirmButton: 'Aceptar'
                            });
                        }
                    }catch(err){
                        bootbox.hideAll();
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center> '+datos.msj+' </center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        function obtenerAdscripciones(){
            $.ajax({
                type: "POST",
                url: "../fachadas/migracion/MigracionFacade.Class.php",
                async: true,
                dateType: "json",
                data: {
                    accion: "obtenerAdscripciones",
                },
                success: function (datos) {
                    try{
                        datos=eval("("+datos+")");
                        if(datos.totalCount >  0){
                            $("#cveAdscripcionCombo").empty();
                            var option = '<option value =""> Seleccione una opci\u00f3n</option>';
                            $.each(datos.data, function (index, elementos) {
                                if(elementos.activo == 'S'){
                                    option += '<option  value="' + elementos.idJuzgado + '"> ' + elementos.desJuz + '</option>';
                                }
                            });
                            $("#cveAdscripcionCombo").append(option);
                            $('#cveAdscripcionCombo').trigger('chosen:updated');
                        }else{
                            $("#cveAdscripcionCombo").empty();
                            var option = '<option value =""> Sin Adscripci\u00f3n</option>';
                            $("#cveAdscripcionCombo").append(option);
                            $('#cveAdscripcionCombo').trigger('chosen:updated');
                            $.alert({
                                title: '<center style="color:#881518;">Error!</center>',
                                content: '<center> No se pueden obtener las Adscripciones </center>',
                                confirmButton: 'Aceptar'
                            });
                        }
                    }catch(err){
                        $("#cveAdscripcionCombo").empty();
                            var option = '<option value =""> Sin Adscripci\u00f3n</option>';
                        $("#cveAdscripcionCombo").append(option);
                        $('#cveAdscripcionCombo').trigger('chosen:updated');
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center> No se pueden obtener las Adscripciones </center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        $("#cveAdscripcionCombo").on("change",function(){
            var desJuz = $("#cveAdscripcionCombo option:selected").text();
            var idJuzgado = $(this).val();
            agregarTabla(idJuzgado,desJuz);
        });
        function agregarTabla(idJuzgado,desJuz){
            var infoLocalStorage=validarLocalStorage();
            if(infoLocalStorage == ""){
                var arreglo= new Array();
                var datos={"idJuzgado":idJuzgado,"desJuz":desJuz};
                arreglo.push(datos);
                localStorage.setItem('datos', JSON.stringify(arreglo));
                var dataArreglo=eval("("+localStorage.getItem('datos')+")");
                contador++;
            }else{
                var dataArreglo=eval("("+localStorage.getItem('datos')+")");
                var pasa=true;
                $.each(dataArreglo,function(index,element){
                    if(element.idJuzgado == idJuzgado){
                        pasa=false;
                    }
                });
                if(pasa){
                    clearLocalStorage();
                    var datos={"idJuzgado":idJuzgado,"desJuz":desJuz};
                    dataArreglo.push(datos);
                    localStorage.setItem('datos', JSON.stringify(dataArreglo));
                }
            }
            tablaAdscripciones();
        }
        function tablaAdscripciones(){
            var infoLocaStorage=validarLocalStorage();
            var listaArchivosEvidencia="";
            if(infoLocaStorage != ""){
                var dataArreglo=eval("("+localStorage.getItem('datos')+")");
                $.each(dataArreglo, function (indexs, elementos) {
                    listaArchivosEvidencia += "<li id='item1' class='list-group-item'>";
                    listaArchivosEvidencia += "    " + elementos.desJuz ;
                    listaArchivosEvidencia += '<a onclick="eliminarAdscripcion('+elementos.idJuzgado+')" class="" href="#"><i class="fa fa-trash-o pull-right" aria-hidden="true" style="color: red"></i></a>';
                    listaArchivosEvidencia += "</li>";
                });
            }else{
                listaArchivosEvidencia += "";
            }
            $("#tablaAdscripciones").html(listaArchivosEvidencia);
        }
        function eliminarAdscripcion(idJuzgado){
            $.confirm({
                title: '<center style="color:#881518;">Advertencia!</center>',
                content: ' <center><center>Estas Seguro de eliminar la Adscripci\u00f3n?</center>',
                confirmButton: 'Aceptar',
                cancelButton: 'Cancelar',
                animation: 'top',
                closeAnimation: 'bottom',
                confirm: function () {
                    var dataArreglo=eval("("+localStorage.getItem('datos')+")");
                    var pasa=true;
                    var nuevoArreglo=new Array();
                    var contador=0;
                    $.each(dataArreglo,function(index,element){
                        if(element.idJuzgado != idJuzgado){
                            console.log("actual: "+element.idJuzgado);
                            console.log("nuevo: "+idJuzgado);
                            contador++;
                            nuevoArreglo.push(element);
                        }
                    });
                    clearLocalStorage();
                    console.log(contador > 0);
                    if(contador > 0){
                        localStorage.setItem('datos', JSON.stringify(nuevoArreglo));
                    }else{
                        localStorage.setItem('datos', "");
                    }
                    console.log(contador);
                    console.log(nuevoArreglo);
                    tablaAdscripciones();
                },
                cancel: function () {
                                        // Aqui pones que quieres que haga cuando presionen cancelar
                }
            });
        }
        function clearLocalStorage(){
            return localStorage = null;
        }
        function validarLocalStorage(){
            try {
                if (localStorage.getItem('datos')) {
                    storage = localStorage.getItem('datos');
                }else{
                    storage = "";
                }
            } catch(e) {
                storage = "";
            }
            return storage;
        }
        function migrarDatosDescarga(listaAdscripciones){
            bootbox.dialog({message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Cargando...</div>',closeButton: false});
            $.ajax({
                type: "POST",
                url: "../fachadas/migracion/MigracionFacade.Class.php",
                async: true,
                dateType: "json",
                data: {
                    accion: "migrarDatosDescarga",
                    listaJuzagdos: listaAdscripciones
                },
                success: function (datos) {
                    try{
                        bootbox.hideAll();
                        datos=eval("("+datos+")");
                        if(datos.status ==  "success"){
                            $.alert({
                                title: '<center style="color:#00C851;;">Correcto!</center>',
                                content: '<center> Migraci&oacute;n Completa </center>',
                                confirmButton: 'Aceptar'
                            });
                        }else{
                            $.alert({
                                title: '<center style="color:#881518;">Error!</center>',
                                content: '<center> '+datos.msj+' </center>',
                                confirmButton: 'Aceptar'
                            });
                        }
                    }catch(err){
                        bootbox.hideAll();
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center> '+datos.msj+' </center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        $(document).ready(function() {
            contador=0;
            $('#cveAdscripcionCombo').chosen({allow_single_deselect: true, placeholder_text_single: "Seleccione una opci\u00F3n"});
            obtenerAdscripciones();
            tablaAdscripciones();
        });
        function eliminarTodas(){
            $.confirm({
                title: '<center style="color:#881518;">Advertencia!</center>',
                content: ' <center><center>Estas Seguro de eliminar las Adscripciones?</center>',
                confirmButton: 'Aceptar',
                cancelButton: 'Cancelar',
                animation: 'top',
                closeAnimation: 'bottom',
                confirm: function () {
                    clearLocalStorage();
                    localStorage.setItem('datos', "");
                    tablaAdscripciones();
                },
                cancel: function () {
                                        // Aqui pones que quieres que haga cuando presionen cancelar
                }
            });
        }
    </script>
</html>

