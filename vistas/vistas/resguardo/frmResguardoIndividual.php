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
            .registro-cotizacion{
                margin: 35px;
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
                        Registro de Bienes
                    </h1>
                    <h2>                                                            
                        Bienes en Resguardo
                    </h2>
                    <hr>
                </div>
                <div class="row" id="instrucciones">
                    <div class="col-sm-12 col-md-12">
                        <div class="btn-add-new"> 
                            <div class="buttons-preview padding-10">
                                <a class="btn btn-primary btn-labeled" href="#" id="btnRegresar1" onclick="changeDivForm(2)">                    
                                    <i class="btn-label fa fa-arrow-left"></i> Regresar
                                </a>
                            </div>  
                        </div>
                        
                        <div class="alert-message alert-message-default">
                            <div class="row">
                                <div class='col-md-9'>
                                    <h4>
                                        Instrucciones
                                    </h4>
                                    <p>
                                        -Digita el c&oacute;digo del Bien o utiliza el lector de codigos de barras para Registrar un art&iacute;culo<br>
                                        - Cuando termines de verificar los art&iacute;culos, da clik en el bot&oacute;n Guardar ubicado en la parte inferior.<br>
                                    </p>
                                </div>
                                <div class='col-md-3'>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class='md-form'>
                                                <input type="text" id="codigo" name="codigo" class="form-control"/>
                                                <label for='codigo' class=" col-md-12" id='codigoLabel'>C&oacute;digo<span style="color:darkred;">(*)</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body " style=" margin-top: -15px;">
                    <div id="formulario">
                        <div class="col-md-12" id="divDatosEmpleado"></div>
                        <div class="col-md-12">
                            <div class="card">
                                <ul class="nav nav-tabs" role="tablist" id="tabListSeguimiento" style="background: #8e1619;">
                                    <li id="tab-informacion" role="presentation" class='active'><a href="#informacion" aria-controls="informacion" role="tab" data-toggle="tab" class="informacion  active"><i class="fa fa-list"></i>Inventario</a></li>
                                    <li id="tab-faltantes" role="presentation"><a href="#faltantes" aria-controls="faltantes" role="tab" data-toggle="tab" class='faltantes hola' onclick="cargarFaltantes()"><i class="fa fa-minus-square"></i>Faltantes</a></li>
                                    <li id="tab-sobrantes" role="presentation"><a href="#sobrantes" aria-controls="sobrantes" role="tab" data-toggle="tab" class='sobrantes hola' onclick="cargarSobrantes()"><i class="fa fa-plus-square"></i>Sobrantes</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="informacion">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id='divTabla'></div>
                                            </div>
                                            <div class="col-md-12">
                                                <center>
                                                    <div class="btn-add-new"> 
                                                        <div class="buttons-preview padding-10">
                                                            <a class="btn btn-primary btn-labeled" href="#" id="btnFinalizarRegistro" onclick="validarResguardo()">                    
                                                                <i class="btn-label fa fa-check-square"></i> Finalizar Registro
                                                            </a>
                                                        </div>  
                                                    </div>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane active" id="faltantes">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id='divTablaFaltante'></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane active" id="sobrantes">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id='divTablaSobrante'></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="info">
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
                        <div class="col-md-6" id="divComboEmpleado" style="display:none;">
                            <div class="form-group"> 
                                <label class=" col-md-12 needed titleCombo">Empleado:</label>
                                <div class="col-md-12">
                                    <select id="numEmpleadoCombo" name="numEmpleadoCombo" class="form-control mdb-select">
                                        <option value="">Seleccione una opci&oacute;n</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="divEmpleado">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class='md-form'>
                                        <input type="text" id="numeroEmpleado" name="numeroEmpleado" class="form-control"/>
                                        <label for='numeroEmpleado' class=" col-md-12" id='numeroEmpleadoLabel'>Numero de Empleado<span style="color:darkred;">(*)</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <center>
                                    <div class="btn-add-new"> 
                                        <div class="buttons-preview padding-10">
                                            <a class="btn btn-primary btn-labeled" href="#" id="btnRegresar1" onclick="cargarDatosEmpleado()">                    
                                                <i class="btn-label fa fa-save"></i> Cargar
                                            </a>
                                        </div>  
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>  
        <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="registrationFormModal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h1 class="modal-title tituloModal" id="myModalLabel">Bienes Resguardados</h1>
                            <h2 class="tituloModal"><div id="comentarioRecepcion "></div>Registra el bien que deseas agregar a tu lista</h2>
                        </div>
                        <div class="modal-body">
                            <div class="row clear">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class='md-form'>
                                                <input type="hidden" id="idInventario" name="idInventario" class="form-control" />
                                                <input type="text" id="codigoPropio" name="codigoPropio" class="form-control" />
                                                <label for='codigoPropio' class=" col-md-12" id='codigoPropiolabel'>Codigo<span style="color:darkred;">(*)</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class='md-form'>
                                                <input type="text" id="denominacion" name="denominacion" class="form-control" readonly="readonly"/>
                                                <label for='denominacion' class=" col-md-12" id='denominacionlabel'>Denominaci&oacute;n</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    &nbsp;
                                </div>
                                <div class="col-md-8" id="mensaje" style="display:none;">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class='md-form'>
                                                <label for='notificacion' class=" col-md-12" id='notificacion'><span style="color:darkred;">No hay Art&iacute;culos con este c&oacute;digo </span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <div class="btn-add-new"> 
                                            <div class="buttons-preview padding-10">
                                                <a class="btn btn-primary btn-labeled" href="#" id="btnRegresar1" onclick="validarGuardarNuevoBien()">                    
                                                    <i class="btn-label fa fa-save"></i> Guardar
                                                </a>
                                                <a class="btn btn-primary btn-labeled" href="#" id="btnRegresar1" data-dismiss="modal">                    
                                                    <i class="btn-label fa fa-sign-out"></i> cerrar
                                                </a>
                                            </div>  
                                        </div>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</html>
    <script>
        $(function (){
            $('.mdb-select').chosen({allow_single_deselect: true, placeholder_text_single: "Seleccione una opci\u00F3n"});
        });
        function cargarDatosEmpleado(){
            if($("#numEmpleadoCombo").val() == ""){
                var numEmpleado=$("#numeroEmpleado").val();
            }else{
                var numEmpleado=$("#numEmpleadoCombo").val();
            }
			if(numEmpleado != "" && numEmpleado != null){
				$("#tab-informacion").addClass("active");
				$("#sobrantes").removeClass("active");
				$("#faltantes").removeClass("active");
				$("#informacion").addClass("active");
				$("#tab-sobrantes").removeClass("active");
				$("#tab-faltantes").removeClass("active");
				$(".informacion").removeClass("hola");
				$(".sobrantes").addClass("hola");
				$(".faltantes").addClass("hola");
				getEmpleado(numEmpleado);
				$.ajax({
					type: "POST",
					url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
					async: false,
					dateType: "json",
					data: {
						accion: "cargarDatosEmpleado",
						numEmpleado: numEmpleado
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
							table +='            <th>Verificado</th>';
							table +='            <th>Eliminar</th>';
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
								table +='   <td>';
								table +='       <div class="checkbox">';
								table +='           <label style="font-size: 2em">';
								table +='               <input class="guardarResguardo" type="checkbox" value="" data-idresguardoindividual=' + element.idResguardoIndividual + ' disabled="disabled" '+checked+'>';
								table +='               <span class="cr"><i class="cr-icon fa fa-check"></i></span>';
								table +='           </label>';
								table +='       </div>';
								table +='   </td>';
								if(element.cveEstatus == 122){
									table +='   <td><center><button class="btn btn-primary" id="btnEliminarCuenta" onclick="validarEliminarBien('+ element.idResguardoIndividual +','+element.idInventario+')"><i class="fa fa-trash-o"></i></button></center></td>';
								}else{
									table +='   <td><center><button class="btn btn-primary" id="btnEliminarCuenta" onclick="validarEliminarBien('+ element.idResguardoIndividual +','+element.idInventario+')" disabled="disabled"><i class="fa fa-trash-o"></i></button></center></td>';
								}
								
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
			}else{
				$.alert({
					title: '<center style="color:#881518;">Error!</center>',
					content: '<center>Selecciona un empleado</center>',
					confirmButton: 'Aceptar'
				});
			}
        }
        function modalAgrgarBien(){
            $("#codigoPropio").val("");
            $("#denominacion").val("");
            $("#denominacionlabel").removeClass("active");
            $("#codigoPropiolabel").removeClass("active");
            $("#myModal").modal("show");
        }
        $("#codigoPropio").on("keyup",function(){
            if($("#codigoPropio").val().length >= 5){
                console.log($("#codigoPropio").val());
                $.ajax({
                    type: "POST",
                    url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                    async: false,
                    dateType: "json",
                    data: {
                        accion: "getBien",
                        codigoPropio: $("#codigoPropio").val()
                    },
                    success: function (datos) {
                        datos=eval("("+datos+")");
                        if(datos.totalCount > 0){
                            $("#denominacion").val(datos.data[0].denominacion);
                            $("#idInventario").val(datos.data[0].idInventario);
                            $("#denominacionlabel").addClass("active");
                            $("#mensaje").hide();
                        }else{
                            $("#denominacionlabel").removeClass("active");
                            $("#idInventario").val("");
                            $("#denominacion").val("");
                            $("#mensaje").show();
                        }
                    }
                });
            }else{
                $("#denominacionlabel").removeClass("active");
                $("#idInventario").val("");
                $("#denominacion").val("");
            }
        });
        function validarGuardarNuevoBien(){
            if($("#denominacion").val() != ""){
                $.ajax({
                    type: "POST",
                    url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                    async: false,
                    dateType: "json",
                    data: {
                        accion: "validarGuardarNuevoBien",
                        codigoPropio: $("#codigoPropio").val(),
                        numeroEmpleado: $("#numeroEmpleado").val()
                    },
                    success: function (datos) {
                        datos=eval("("+datos+")");
                        if(datos.status == "tuyo"){
                            $.confirm({
                                title: '<center style="color:#881518;">Advertencia!</center>',
                                content: ' <center><center>\u00BFEstas seguro de registrar este Art\u00edculo?</center>',
                                confirmButton: 'Aceptar',
                                cancelButton: 'Cancelar',
                                animation: 'top',
                                closeAnimation: 'bottom',
                                confirm: function () {
                                    guardarNuevoBien();
                                },
                                cancel: function () {
                                                        // Aqui pones que quieres que haga cuando presionen cancelar
                                }
                            });
                        }else if(datos.status == "alguien"){
                            $.confirm({
                                title: '<center style="color:#881518;">Advertencia!</center>',
                                content: ' <center><center>El Art\u00edculo lo tiene asignado algui\u00e9n m\u00e1s<br>\u00BFEstas Seguro de Agregarlo?</center>',
                                confirmButton: 'Aceptar',
                                cancelButton: 'Cancelar',
                                animation: 'top',
                                closeAnimation: 'bottom',
                                confirm: function () {
                                    guardarNuevoBien();
                                },
                                cancel: function () {
                                                        // Aqui pones que quieres que haga cuando presionen cancelar
                                }
                            });
                        }else{
                            $.confirm({
                                title: '<center style="color:#881518;">Advertencia!</center>',
                                content: ' <center><center>\u00BFEstas Seguro de Agregar el art\u00edculo?</center>',
                                confirmButton: 'Aceptar',
                                cancelButton: 'Cancelar',
                                animation: 'top',
                                closeAnimation: 'bottom',
                                confirm: function () {
                                    guardarNuevoBien();
                                },
                                cancel: function () {
                                                        // Aqui pones que quieres que haga cuando presionen cancelar
                                }
                            });
                        }
                    }
                });
            }else{
                $.alert({
                    title: '<center style="color:#881518;">Error!</center>',
                    content: '<center>No existe Este Art\u00edculo</center>',
                    confirmButton: 'Aceptar'
                });
            }
        }
        function guardarNuevoBien(){
            if($("#numEmpleadoCombo").val() == ""){
                var numEmpleado=$("#numeroEmpleado").val();
            }else{
                var numEmpleado=$("#numEmpleadoCombo").val();
            }
            $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "guardarNuevoBien",
                    codigoPropio: $("#codigoPropio").val(),
                    idInventario: $("#idInventario").val(),
                    numeroEmpleado: numEmpleado
                },
                success: function (datos) {
                    try{
                        datos=eval("("+datos+")");
                        if(datos.status=="success"){
                            cargarDatosEmpleado();
                            $("#myModal").modal("show");
                            $.alert({
                                title: '<center style="color:#00C851;">Correcto!</center>',
                                content: '<center> Se registr\u00f3 el Art\u00edculo </center>',
                                confirmButton: 'Aceptar'
                            });
                        }else if(datos.status=="ya"){
                            $.alert({
                                title: '<center style="color:#881518;">Error!</center>',
                                content: '<center> Ya tienes Registrado Este Art\u00edculo </center>',
                                confirmButton: 'Aceptar'
                            });
                        }else{
                            $.alert({
                                title: '<center style="color:#881518;">Error!</center>',
                                content: '<center> No se puede Asignar este art\u00edculo </center>',
                                confirmButton: 'Aceptar'
                            });
                        }   
                    }catch(err){
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center> Error </center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        function finalizarRegistro(){
            if($("#numEmpleadoCombo").val() == ""){
                var numEmpleado=$("#numeroEmpleado").val();
            }else{
                var numEmpleado=$("#numEmpleadoCombo").val();
            }
            var listaResguardoMarcada = new Array();
            var listaResguardoDesmarcada = new Array();
            $.each($(".guardarResguardo"), function (index, element) {
                if ($(element).is(':checked')) {
                    listaResguardoMarcada.push($(element).data("idresguardoindividual"));
                } else {
                    listaResguardoDesmarcada.push($(element).data("idresguardoindividual"));
                }
            });
            if (listaResguardoDesmarcada.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                    async: false,
                    dateType: "json",
                    data: {
                        accion: "finalizarRegistro",
//                        listaResguardoMarcada : listaResguardoMarcada,
                        listaResguardoDesmarcada : listaResguardoDesmarcada,
                        numeroEmpleado: numEmpleado
                    },
                    success: function (datos) {
                        try{
                            datos=eval("("+datos+")");
                            if(datos.status ==  "success"){
                                cargarDatosEmpleado();
                            }else{
                                $.alert({
                                    title: '<center style="color:#881518;">Error!</center>',
                                    content: '<center> Algo Salio Mal </center>',
                                    confirmButton: 'Aceptar'
                                });
                            }
                        }catch(err){
                            $.alert({
                                title: '<center style="color:#881518;">Error!</center>',
                                content: '<center> Algo Salio Mal </center>',
                                confirmButton: 'Aceptar'
                            });
                        }
                    }
                });
            }else{
                $.alert({
                    title: '<center style="color:#881518;">Error!</center>',
                    content: '<center> No Hay nada por Procesar </center>',
                    confirmButton: 'Aceptar'
                });
            }
        }
        function validarResguardo(){
            $.confirm({
                title: '<center style="color:#881518;">Advertencia!</center>',
                content: ' <center><center>Estas Seguro de finalizar el Registro?</center>',
                confirmButton: 'Aceptar',
                cancelButton: 'Cancelar',
                animation: 'top',
                closeAnimation: 'bottom',
                confirm: function () {
                    finalizarRegistro();
                },
                cancel: function () {
                                        // Aqui pones que quieres que haga cuando presionen cancelar
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
        $('#codigo').keypress(function(event){
            if (event.keyCode == '13') {
                registrarBien();
            }
        });
        function registrarBien(){
            if($("#numEmpleadoCombo").val() == ""){
                var numEmpleado=$("#numeroEmpleado").val();
            }else{
                var numEmpleado=$("#numEmpleadoCombo").val();
            }
            $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "registrarBien",
                    codigoPropio: $("#codigo").val(),
                    numEmpleado: numEmpleado
                },
                success: function (datos) {
                    try{
                        datos=eval("("+datos+")");
                        if(datos.status == "success"){
                            $("#codigo").val("");
                            cargarDatosEmpleado();
                        }else{
                            $("#codigo").val("");
                            $.alert({
                                title: '<center style="color:#881518;">Error!</center>',
                                content: '<center>No se encuentran datos</center>',
                                confirmButton: 'Aceptar'
                            });
                        }
                    }catch(err){
                        $("#codigo").val("");
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center> Error </center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
        }
        function cargarAdscripciones() {
            var archivo = "../archivos/informacionEmpleados.json";
            $.getJSON(archivo, function (datos) {
                $("#cveAdscripcion").empty();
                var option = '<option value =""> Seleccione una opci\u00f3n</option>';
                $.each(datos.data, function (index, elementos) {
                    if(elementos.activo == 'S'){
                        option += '<option  value="' + elementos.idJuzgado + '">' + elementos.desJuz + '</option>';
                    }
                });
                $("#cveAdscripcion").append(option);
                $('#cveAdscripcion').trigger('chosen:updated');
            });
        }
        $("#cveAdscripcion").on("change",function(){
            if($("#cveAdscripcion").val() != ""){
                cargarEmpleadosAdscripcion();
                $("#divComboEmpleado").show();
                $("#divEmpleado").hide();
                $("#numeroEmpleado").val("");
            }else{
                $("#divComboEmpleado").hide();
                $("#divEmpleado").show();
                $("#numEmpleadoCombo").val("");
            }
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
        function validarEliminarBien(idResguardoIndividual,idInventario){
            $.confirm({
                title: '<center style="color:#881518;">Advertencia!</center>',
                content: ' <center><center>Estas Seguro de Eliminar el Registro?</center>',
                confirmButton: 'Aceptar',
                cancelButton: 'Cancelar',
                animation: 'top',
                closeAnimation: 'bottom',
                confirm: function () {
                    eliminarBien(idResguardoIndividual,idInventario);
                },
                cancel: function () {
                                        // Aqui pones que quieres que haga cuando presionen cancelar
                }
            });
        }
        function eliminarBien(idResguardoIndividual,idInventario){
            if($("#numEmpleadoCombo").val() == ""){
                var numEmpleado=$("#numeroEmpleado").val();
            }else{
                var numEmpleado=$("#numEmpleadoCombo").val();
            }
             $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "eliminarBien",
                    idResguardoIndividual: idResguardoIndividual,
                    idInventario: idInventario,
                    numEmpleado: numEmpleado
                },
                success: function (datos) {
                    try{
                        datos=eval("("+datos+")");
                        if(datos.status == "success"){
                            $("#codigo").val("");
                            cargarDatosEmpleado();
                        }else{
                            $("#codigo").val("");
                            $.alert({
                                title: '<center style="color:#881518;">Error!</center>',
                                content: '<center>No se pudo Eliminar</center>',
                                confirmButton: 'Aceptar'
                            });
                        }
                    }catch(err){
                        $("#codigo").val("");
                        $.alert({
                            title: '<center style="color:#881518;">Error!</center>',
                            content: '<center> Error llama a DTI</center>',
                            confirmButton: 'Aceptar'
                        });
                    }
                }
            });
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
        function cargarFaltantes(){
            if($("#numEmpleadoCombo").val() == ""){
                var numEmpleado=$("#numeroEmpleado").val();
            }else{
                var numEmpleado=$("#numEmpleadoCombo").val();
            }
            $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "cargarFaltantes",
                    numEmpleado: numEmpleado
                },
                success: function (datos) {
                    try{
                        datos=eval("("+datos+")");
                            $("#divTablaFaltante").html("");
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
        function cargarSobrantes(){
            if($("#numEmpleadoCombo").val() == ""){
                var numEmpleado=$("#numeroEmpleado").val();
            }else{
                var numEmpleado=$("#numEmpleadoCombo").val();
            }
            $.ajax({
                type: "POST",
                url: "../fachadas/resguardos/ResguardoIndividualFacade.Class.php",
                async: false,
                dateType: "json",
                data: {
                    accion: "cargarSobrantes",
                    numEmpleado: numEmpleado
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
        $(document).ready(function() {
            changeDivForm(2);
            cargarAdscripciones();
//            getEmpleado();
//            cargarDatosEmpleado();
        });
    </script>
</html>

