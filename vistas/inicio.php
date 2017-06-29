
<!doctype html>
<html>
    <head>
        <meta name="description" content="Dashboard" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
        <!--<meta http-equiv="content-type" content="text/html; charset=UTF-8">-->

        <meta name="application-name" content="Sigeadcon 2.0" />
        <meta name="apple-mobile-web-app-title" content="Sigeadcon 2.0" />

        <!-- icono en la resolusion mas alta-->
        <link rel="apple-touch-icon" href="img/iconos/iconApp4/LogoAppPJ_192.png" />
        <link rel="icon" sizes="228x228" href="img/iconos/iconApp4/LogoAppPJ_192.png" />
        <link href="img/iconos/iconApp4/LogoAppPJ_144.png" rel="icon" sizes="192x192" />
        <link href="img/iconos/iconApp4/LogoAppPJ_144.png" rel="icon" sizes="128x128" />

        <!--         reusa el mismo icono para Safari diversos iconos para IE-->
        <meta name="msapplication-square70x70logo" content="img/iconos/iconApp4/LogoAppPJ_72.png" />
        <meta name="msapplication-square150x150logo" content="img/iconos/iconApp4/LogoAppPJ_72.png" />
        <meta name="msapplication-wide310x150logo" content="img/iconos/iconApp4/LogoAppPJ_72.png" />
        <meta name="msapplication-square310x310logo" content="img/iconos/iconApp4/LogoAppPJ_72.png" />

        <!--ICONOS PARA IOS-->
        <link rel="apple-touch-icon" href="img/iconos/iconApp4/LogoAppPJ_72.png" />
        <link rel="apple-touch-icon" sizes="76x76" href="img/iconos/iconApp4/LogoAppPJ_72.png" />
        <link rel="apple-touch-icon" sizes="120x120" href="img/iconos/iconApp4/LogoAppPJ_72.png" />
        <link rel="apple-touch-icon" sizes="152x152" href="img/iconos/iconApp4/LogoAppPJ_72.png" />

        <title>Sistema de Gesti&oacute;n Administrativa</title>
        
        

        <link type="text/css" href="css/jquery.smartmenus.bootstrap.css" rel="stylesheet" />
        <link type="text/css" href="css/font-awesome.min.css" rel="stylesheet" />
        <link type="text/css" href="css/weather-icons.min.css" rel="stylesheet" />
        <link type="text/css" href="css/beyond.min.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="css/typicons.min.css" rel="stylesheet" />
        <link type="text/css" href="css/animate.min.css" rel="stylesheet" />
        <link type="text/css" href="css/loadercss.css" rel="stylesheet" />
        <link type="text/css" rel="stylesheet" href="css/jquery-ui.css">
        <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" href="css/validation/bootstrapValidator.min.css" rel="stylesheet" />
        <link type="text/css" href="css/iconfont/style.css" rel="stylesheet" />
        <link type="text/css" href="css/iconFontTree/style.css" rel="stylesheet" />
        <link type="text/css" href="js/jstree/dist/themes/default/style.css" rel="stylesheet" />          
        <link type="text/css" rel="stylesheet" href="chat/css/stylemessage.css">
        <link type="text/css" rel="stylesheet" href="js/webui/jquery.webui-popover.min.css">         
        <link type="text/css" rel="stylesheet" href="css/avisos/horizontal.css">
        <link type="text/css" rel="stylesheet" href="css/notify/jquery.notify.css">
        <link type="text/css" rel="stylesheet" href="css/tutos/introjs.css">
        <link type="text/css" rel="stylesheet" href="js/jquery-typeahead-2.6.1/src/jquery.typeahead.css">

        <!--Material Design-->
        <link href="css/MDB/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/MDB/mdb.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/MDB/style.css" rel="stylesheet" type="text/css"/>
        <link href="css/estilos/administrativo.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <!--Material Design-->
        <link href="css/gantt/style.css" rel="stylesheet" type="text/css"/>
        <!--Alert y confirm jquery-->
        <link href="css/jquery-confirm.css" rel="stylesheet" type="text/css"/>
        <!--Menu -->
        <link href="css/menu.css" rel="stylesheet" type="text/css"/>
        <!--Gr&acute;ficas-->
        <script type="text/javascript" src="http://code.highcharts.com/stock/highstock.js"></script>
<!--  <script type="text/javascript" src="../graficas/highcharts-4.2.4/lib/highstock.js"></script>-->
        <script src="js/graficas/highcharts-4.2.4/highcharts.js"></script>
        <script src="js/graficas/highcharts-4.2.4/highcharts-3d.js"></script>
        <script src="js/graficas/highcharts-4.2.4/modules/exporting.js"></script>
        <script src="js/graficas/highcharts-4.2.4/modules/data.js"></script>
        <script src="js/graficas/highcharts-4.2.4/modules/drilldown.js"></script>



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
                    margin:1%;
                }
                .panel-body{ padding: 10px !important; }
            }
            @media only screen and (min-width:1024px){
                #bordeareadetrabajo{
                    margin:4%;
                }
                #areadetrabajo{
                    margin:2%;
                }
                .panel-body{ padding: 15px !important; }
            }
            @media only screen and (min-width:1900px){
                #bordeareadetrabajo{

                    margin:5%;
                }
                #areadetrabajo{
                    margin:3%;
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
        </style>
        <script src="js/firmaElectronica.js" ></script>
        <script type="text/javascript" src="js/Firma.js"></script>
        <script type="text/javascript">
            var fielnetPJ = new fielnet.Firma({
                subDirectory: "js/scriptsFirma",
                ajaxAsync: false,
                controller: "../controladores/firmaelectronicahtml5/FirmaElectronicaController.php"
            });
        </script>
    </head>
    <body style="">
        <div style="width: 100%; border: 1px solid rgb(206, 206, 206); position: absolute; top: 0px; height: 100vh; background: rgba(239, 239, 239, 0.48) none repeat scroll 0% 0%; z-index: 999; display: none;" id="bloquear-internet"></div>
        <!-- Static navbar -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button id="menuPrincipalSigejupe" type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Menu</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-header pull-left">
                        <a href="#" class="navbar-brand">
                            <small>
                                <img src="img/LogoInstitucional-01.png" alt="" id="logo_empresa"/>
                            </small>
                        </a>
                    </div>
                </div>
                <div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav" id="ulMenuPrincipal">
                        <li>
                            <a href="#noir" onclick="loadOpcion('vistas/resguardo/frmResguardoIndividual.php','areadetrabajo')">
                                Registro Individual
                            </a>
                        </li>
                        <li>
                            <a href="#noir" onclick="loadOpcion('vistas/controlpatrimonial/etiquetas/frmEtiquetasView.php','areadetrabajo')">
                                Control de Etiquetado
                            </a>
                        </li>
                        <li>
                            <a href="#noir" onclick="loadOpcion('vistas/reportes/frmFaltantesView.php','areadetrabajo')">
                                Faltantes y Sobrantes
                            </a>
                        </li>
                        <li>
                            <a href="#noir" onclick="loadOpcion('vistas/migracion/frmMigracionView.php','areadetrabajo')">
                                Migraci&oacute;n
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12"><hr></div>
            </div>
        </div>

        <div class="main-container container-fluid" style="margin-top: 15px;">
            <div id="divHideForm">
                <div class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <h1>Cargando, Por favor espere...</h1>
                    </div>
                    <div class="modal-body">
                        <div class="progress progress-striped active">
                            <div class="bar" style="width: 100%;">                                        
                            </div>

                        </div>

                    </div>                                
                </div>
                <!--                        <div id="divMenssage">
                                            Por favor espere
                                        </div>
                                        <div id="divImgloading"></div>-->
            </div>
            <div id="bordeareadetrabajo" class="clear">
                <div class="page-container" id="areadetrabajo">
                    <!--<div class="page-content" id="areadetrabajo">-->
                    <div class="panel panel-default panel-box-shadow">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                Bienvenido
                            </h5>
                        </div>
                        <div class="panel-body" id="divPanelBody" >
                        </div>
                    </div>
                    <!--</div>-->
                </div>
            </div>
        </div>

        <input type="hidden" id="hddjuzgadoSesion" value="" />
        <input type="hidden" id="hddcveUsuarioSesion" value="" />
        <input type="hidden" id="hddcvePerfilSesion" value="" />
        <input type="hidden" id="hddcveSistema" value="" />

        <script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.11.14.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.4.custom.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery.smartmenus.js"></script>
        <script type="text/javascript" src="js/jquery.smartmenus.bootstrap.js"></script>
<!--            <script type="text/javascript" src="js/datatable/jquery.dataTables.js"></script>
        <script type="text/javascript" src="js/datatable/dataTables.tableTools.js"></script>
        <script type="text/javascript" src="js/datatable/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="js/datatable/dataTables.fixedHeader.min.js"></script>-->
        <script type="text/javascript" src="js/funciones.js"></script>
        <script type="text/javascript" src="js/datetime/moment.js"></script>                
        <script type="text/javascript" src="js/datetimepicker/moment-with-locales.js"></script>                
        <script type="text/javascript" src="js/datetime/bootstrap-datepicker.js"></script>                
        <script type="text/javascript" src="js/datetime/bootstrap-timepicker.js"></script>                
        <script type="text/javascript" src="js/datetimepicker/bootstrap-datetimepicker.js"></script>                
        <script type="text/javascript" src="js/select2/select2.js"></script>                
        <script type="text/javascript" src="js/fullcalendar/fullcalendar.js"></script>
        <script type="text/javascript" src="js/jstree/src/jstree.js"></script>
        <script type="text/javascript" src="js/jstree/src/jstree.search.js"></script>
        <script type="text/javascript" src="js/jstree/src/jstree.wholerow.js"></script>
        <script type="text/javascript" src="js/jstree/src/jstree.contextmenu.js"></script>
        <script type="text/javascript" src="js/jstree/src/jstree.sort.js"></script>
        <script type="text/javascript" src="js/bootbox/bootbox.js"></script>
        <script type="text/javascript" src="js/chat.js"></script>
        <script type="text/javascript" src="chat/js/bootstrap-filestyle.min.js"></script>
        <script type="text/javascript" src="chat/js/windowsevents.js"></script>
        <script type="text/javascript" src="js/jstree/dist/jstree.js"></script>
        <script type="text/javascript" src="js/webui/jquery.webui-popover.min.js"></script>                
        <script type="text/javascript" src="js/webui/bootstrap-toolkit.min.js"></script>                
        <script type="text/javascript" src="js/validation/bootstrapValidator.js"></script>                
        <script type="text/javascript" src="js/validation/bootstrapValidator.min.js"></script>                
        <script type="text/javascript" src="js/avisos/plugins.js"></script>
        <script type="text/javascript" src="js/avisos/sly.js"></script>
        <script type="text/javascript" src="js/avisos/horizontal.js"></script>
        <script type="text/javascript" src="js/notify/jquery.notify.min.js"></script>
        <script type="text/javascript" src="js/tutos/intro.js"></script>
        <script type="text/javascript" src="js/jquery-typeahead-2.6.1/src/jquery.typeahead.js"></script>

        <!--/*Editor de Texto*/-->                
        <script type="text/javascript" charset="utf-8" src="js/jqeditor/ueditor.config.js"></script>
        <script type="text/javascript" charset="utf-8" src="js/jqeditor/ueditor.all.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="js/jqeditor/es.js"></script>         

        <link type="text/css" rel="stylesheet" href="js/bootstrap-chosen-master/bootstrap-chosen.css"/>
        <script type="text/javascript" src="js/bootstrap-chosen-master/chosen.jquery.js"></script> 
        <script type="text/javascript" src="js/bootstrap-chosen-master/chosen.jquery.min.js"></script> 
        <!--datatables-->
        <script type="text/javascript" src="js/DataTables-1.10.13/libs-datatables-pjedomex.js"></script> 
        <!--Buttons-->
        <script type="text/javascript" src="js/DataTables-1.10.13/extensions/Buttons/js/dataTables.buttons.min.js"></script> 
        <script type="text/javascript" src="js/DataTables-1.10.13/dist/jszip.min.js"></script> 
        <script type="text/javascript" src="js/DataTables-1.10.13/extensions/Buttons/js/buttons.html5.min.js"></script> 
        <!------->
        <!--Visor pdf 2.0-->
        <script type="text/javascript" src="js/PDFObject_2.0/pdfobject.js"></script>
        <script type="text/javascript" src="js/PDFObject_2.0/_visor.js"></script>
        <!--MATERIAL DESIGN-->
        <!--<script src="js/MDB/tether.min.js" type="text/javascript"></script>-->
        <!--<script src="js/MDB/bootstrap.min.js" type="text/javascript"></script>-->
        <script src="js/MDB/mdb.min.js" type="text/javascript"></script>
        <!--MATERIAL DESIGN-->
        <script src="js/gantt/jquery.fn.gantt.js" type="text/javascript"></script>
        <!--Alertas y Confirmaciones jquery-->
        <script src="js/jquery-confirm.js" type="text/javascript"></script>



        <link href="css/circle/circle.css" rel="stylesheet" type="text/css"/>

        <script src="js/socket/socket.io.js" type="text/javascript"></script>
        <script type="text/javascript">
            var catEstadosJson = "";
            var catEstadoscivilesJson = "";
            var catMunicipiosJson = "";
            var catPaisesJson = "";
            var catTiposDetencionesJson = "";
            var catTiposPersonasJson = "";
            var catTiposReligionesJson = "";
            var catEspanolJson = "";
            var catGenerosJson = "";
            var catNivelesInstruccionesJson = "";
            var catAlfabetismoJson = "";
            var catConvivenciasJson = "";
            var catDialectoIndigenaJson = "";
            var catDrogasJson = "";
            var catEstadosPsicofisicosJson = "";
            var catGruposEdnicosJson = "";
            var catTiposDefensoresJson = "";
            var catInterpretesJson = "";
            var catOcupacionesJson = "";
            var catTiposdeViviendasJson = "";
            var catCeresosJson = "";
            var catTipoFamiliaLinguisticaJson = "";
            var catTiposReincidenciasJson = "";
            var catTiposTutoresJson = "";
            var catInterpretesJson = "";

            var socket = null;
            
            
            //INICIAMOS LAS VARIABLES EN EL SISTEMA
//            if (localStorage.getItem('key1')) {
//            }
//            var configDataTableAlineacion = '<"col-md-12 col-lg-12" <"row" <"col-lg-3 col-md-6 col-sm-6 col-xs-12" l> <"col-lg-2 col-md-6 col-sm-6 col-xs-12" f> <"col-lg-5 col-md-12 col-sm-12 col-xs-12 pull-right" p> > <"row" t> <"row" <"col-lg-6 col-md-6 col-sm-6 col-xs-12" <"col-lg-6 col-md-6 col-sm-6 col-xs-12" > > > >';
            var configDataTableAlineacion = '<"col-md-12 col-lg-12" <"row" <"col-lg-3 col-md-6 col-sm-6 col-xs-12" l> <"col-lg-2 col-md-6 col-sm-6 col-xs-12" f> <"col-lg-5 col-md-12 col-sm-12 col-xs-12 pull-right" p> > <"row" t> <"row footerTable" <"col-lg-6 col-md-12 col-sm-12 col-xs-12" i> <"col-lg-6 col-md-12 col-sm-12 col-xs-12" p> > >';
            var configDataIdiomaDataTable = {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ning\u00fan dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "\u00daltimo",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }

            openModalFiles = function () {
                chatApp.refreshIframe("directory", "contenidoArchivosModal");
                $("#choose-file").modal("show");
                chatApp.refreshIframe("directory", "contenidoArchivosModal");
            };

            openModalWhoisOnline = function () {
                chatApp.getUsersOnLine();
                $("#whoisonline").modal("show");
            };

            $(document).ajaxStart(function () {
                ToggleLoading(1);
            });

            $(document).ajaxStop(function () {
                ToggleLoading(2);
            });
            function spinnerLoading(option = false, id = "cargandoDefault") {
                if($("#"+id).is(":visible")){
                    if (!option) {
                    } else {
                        $("#" + id).remove();
                    }
                }else{
                    if (!option) {
                        $('<div id="' + id + '" class="modal-backdrop text-center"> <i class="fa fa-spinner fa-spin fa-3x fa-fw" style="color: #fff;margin-top: 25%;font-size: 150px;"></i></div>').appendTo(document.body).fadeIn().css({"background-color": 'rgba(0,0,0,.7)', 'height': $(document).height(), 'z-index': '999999999'});
                    } else {
                        $("#" + id).remove();
                    }                    
                }
            }
            cargarNotificaciones = function () {
                $.ajax({
                    type: 'POST',
                    url: "../fachadas/notificaciones/NotificacionesFacade.Class.php",
                    async: true,
                    dataType: 'json',
                    data: {
                        accion: "consultarPorAdscripcionNotificaciones",
                        pag: $("#paginacion-notificaciones").val()
                    },
                    beforeSend: function (xhr) {

                    },
                    success: function (data, textStatus, jqXHR) {

                        if (data.totalCount > 0) {
                            $("#paginacion-notificaciones").val(parseInt($("#paginacion-notificaciones").val()) + data.totalCount);
                            $.each(data.data, function (index, element) {
                                agregarNotificacionLista(JSON.stringify(element), true, data.totalCountNotificaciones);
                            });

                        } else {
//                            $("#notificacionesElemnto").off("scroll");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                    }
                });
            };
            loadOpcion = function (url, div) {
                console.log("lalo");
                if (url != "#noir") {
                    $.post(url, function (htmlexterno) {
                        $("#" + div).html(htmlexterno);
                    });
                }
            };
            cargarFormulario = function (formulario, idVisto = null) {
                console.log("cargarFormulario");
                loadOpcion(formulario, 'areadetrabajo');
                var cantidadNotificaciones = parseInt($(".badge-notify").text());
                if (idVisto != null) {
                    if (cantidadNotificaciones != 0) {
                        cantidadNotificaciones--;
                    }
                    $(".badge-notify").text(cantidadNotificaciones);
                    guardarVistoNotificacion(idVisto);
            }
            };
            fechaVista = function (fecha, tipo = false) {
                var fechaVista = fecha.split(" ");
                console.log(fechaVista);
                console.log(fechaVista[0].split("-"));
                if (!tipo)
                    return fechaVista[0].split("-")[2] + "/" + fechaVista[0].split("-")[1] + "/" + fechaVista[0].split("-")[0];
                else
                    return fechaVista[0].split("-")[2] + "/" + fechaVista[0].split("-")[1] + "/" + fechaVista[0].split("-")[0] + " " + fechaVista[1];

            };


            ToggleLoading = function (opc) {
                if (opc === 1) {
                    spinnerLoading()
                } else if (opc === 2) {
                    spinnerLoading(true)
                }
            };

            setTimeAlert = function (div) {
                setTimeout(function () {
                    $("#" + div).hide("slide");
                }, 3500);
            };

            changeDivForm = function (opc) {
                if (opc === 1) {
                    $("#divFormulario").show("slide");
                    $("#divConsulta").hide("fade");
                } else if (opc === 2) {
                    $("#divFormulario").hide("fade");
                    $("#divConsulta").show("slide");
                }
            };

            validarURL = function (url) {
                var myRegExp = /^(?:(?:https?|http):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
                if (!myRegExp.test(url)) {
                    return false;
                } else {
                    return true;
                }
            };

            movermeA = function (idDiv, tipo) {
                if (tipo == "centro") {
                    $('html,body').animate({
                        scrollTop: $("#" + idDiv + "").offset().top - ($(window).height() - $("#" + idDiv + "").outerHeight(true)) / 2
                    }, 2000, 'swing');
                } else if (tipo = "top") {
                    $('html,body').animate({
                        scrollTop: $("#" + idDiv + "").offset().top
                    }, 2000, 'swing');
                }

            };

            notifyMe = function (titulo, msg) {
                var sonido = new Audio('sound/notify.mp3');
                if (!("Notification" in window)) {
                    alert("Internet Explorer no soporta las notificaciones de escritorio para los cateos y ordenes de aprehensi\u00f3n pendientes");
                } else if (Notification.permission === "granted") {
                    var option = {
                        body: msg
                    };
                    var notification = new Notification(titulo, option);
                    sonido.play();
                } else if (Notification.permission !== 'denied') {
                    Notification.requestPermission(function (permission) {
                        if (permission === "granted") {
                            var notification = new Notification("Activadas");
                        }
                    });
                }
            };

            loaded = function () {
                updateOnlineStatus("load");
                document.body.addEventListener("offline", function () {
                    updateOnlineStatus("offline")
                }, false);
                document.body.addEventListener("online", function () {
                    updateOnlineStatus("online")
                }, false);
            };

            reloadSession = function () {

            };
            $(document).ready(function() {
                $('#ulMenuPrincipal').smartmenus();
            });
            
        </script>

        <style type="text/css">
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

            .divPerfil1{
                width: 250px;
                height: 150px;
                float: left;
                margin: 5px;
                padding: 10px;
                background: #f8f8f8;
                color: #505050
            }

            .divPerfil2{
                width: 250px;
                height: 150px;
                float: left;
                margin: 5px;
                padding: 10px;
                background: #eaeaea;
                color: #505050;
            }
        </style>
        <div class="modal fade" id="ModalVisorPDF" tabindex="-1" role="dialog" aria-labelledby="VisorPDF">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background:#881518;">
                        <img src="img/blancapj2.png" class="pull-left" width="50px">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="VisorPDF" style="background:#881518;text-align: center;color:white">Visor de documentos</h4>
                    </div>
                    <div class="modal-body" id="visor" style="max-height: 500px; overflow: scroll;"></div>
                </div>
            </div>
        </div>
        <!-- Modal Firma electronica -->
        <form role="form" id="formModalFirmaElectronica" data-bv-message="Este valor no es valido" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
            <div class="modal fade" id="myModalFirmaElectronica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                <div class="modal-dialog" role="document">
                    <!--Content-->
                    <div class="modal-content">
                        <!--Header-->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title w-100" id="myModalLabel">Proporciona los datos de la firma electronica</h4>
                        </div>
                        <!--Body-->
                        <div class="modal-body">
                            <br/>
                            <div class="row">
                                <div class=" col-md-12">
                                    <h6>Para realizar esta acci&oacute;n debes proporcionar los datos de la firma electronica</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-offset-2 col-md-10">
                                    <br>
                                    <div class="input-group inputs-firma" style="">
                                        <label for="keyModal" class="col-md-12 needed">Archivo .KEY:</label>
                                        <input id="keyModal" name="keyModal" type="file" accept=".key" data-input="false" data-buttonText="&nbsp;Archivo .KEY" data-iconName="fa fa-file-code-o" required data-bv-notempty data-bv-notempty-message="El archivo .KEY es requerido">
                                    </div>
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-offset-2 col-md-10">
                                    <div class="input-group inputs-firma" style="">
                                        <label for="cerModal" class="col-md-12 needed">Archivo .CER:</label>
                                        <input id="cerModal" name="cerModal" type="file" accept=".cer" data-input="false" data-buttonText="&nbsp;Archivo .CER" data-iconName="fa fa-certificate" required data-bv-notempty data-bv-notempty-message="El archivo .CER es requerido">
                                    </div>
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-offset-2 col-md-10">
                                    <div class="input-group inputs-firma" style="max-width: 300px">
                                        <label for="password" class="col-md-4 needed">Password:</label>
                                        <input type="password" class="form-control" id="firmapassmodal" name="firmapassmodal" placeholder="password" style="text-align: center;" autocomplete="off" required data-bv-notempty data-bv-notempty-message="El campo password es requerido"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Footer-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-mdb" data-dismiss="modal">Cancelar</button>
                            <button id="test" type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> Continuar</button>
                        </div>
                    </div>
                    <!--/.Content-->
                </div>
            </div>
        </form>
        <!-- /.Live preview-->

    </body>
</html>

