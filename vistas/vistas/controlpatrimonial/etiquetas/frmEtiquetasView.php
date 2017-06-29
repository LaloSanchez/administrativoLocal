    <style type="text/css">
        input[type=range] {
            display: inline-table;
            width: calc(100% - (73px));
        }
        input[type=range] {
            clear: all;
        }
        .huge {
            font-size: 40px;
        }
        .panel-padding{
            padding: 0px !important;
        }
        .bold-title{
            font-family: Roboto,sans-serif  !important;
            font-size: 18px;
            color: #646469 ;
            font-weight: bold !important;
        }
        .bold-subtitle{
            font-family: Roboto,sans-serif  !important;
            font-size: 14px;
            color: #646469 ;
            font-weight: bold !important;
        }
        p{
            font-family: Roboto,sans-serif  !important;
            font-size: 14px;
            color: #646469 ;
            text-align: justify;

            /*margin: 0;*/
        }
        .text-izq{
            text-align: left !important;
        }
        .tab-content {
            background-color: #fff; 
        }
        .progress {
            background: #646469;
        }
        .panel-heading.panel-change-color{
            background: #e3e3e2 !important;
        }
        .panel-heading.panel-change-color:hover{
            background: #f7f7f7 !important;
        }
        .panel-title.panel-change-title a{
            color: #474747 !important;
            text-decoration: none;
        }
        .panel-title.panel-change-title a:hover{
            color: #505055 !important;
            text-decoration: none;
        }
        .panel-title a {
            display: block;
            color: #fff;
            padding: 5px;
            position: relative;
            font-size: 16px;
            font-weight: 400;
        }
        .panel:last-child .panel-body {
            border-radius: 0 0 4px 4px;
        }

        .panel:last-child .panel-heading {
            border-radius: 0 0 4px 4px;
            transition: border-radius 0.3s linear 0.2s;
        }

        .panel:last-child .panel-heading.active {
            border-radius: 0;
            transition: border-radius linear 0s;
        }
        .panel-heading a:before {
            content: '\e146';
            position: absolute;
            font-family: 'Material Icons';
            right: -8px;
            top: 1px;
            font-size: 24px;
            transition: all 0.5s;
            transform: scale(1);
        }

        .panel-heading.active a:before {
            content: ' ';
            transition: all 0.5s;
            transform: scale(0);
        }
        #bs-collapse .panel-heading a:after {
            content: ' ';
            font-size: 24px;
            position: absolute;
            font-family: 'Material Icons';
            right: 5px;
            top: 10px;
            transform: scale(0);
            transition: all 0.5s;
        }
        #bs-collapse .panel-heading.active a:after {
            content: '\e909';
            transform: scale(1);
            transition: all 0.5s;
        }
        #accordion .panel-heading a:before {
            content: '\e316';
            font-size: 24px;
            position: absolute;
            font-family: 'Material Icons';
            right: 5px;
            top: 10px;
            transform: rotate(180deg);
            transition: all 0.5s;
        }
        #accordion .panel-heading.active a:before {
            transform: rotate(0deg);
            transition: all 0.5s;
        }
        .row-centered {
            text-align:center;
        }
        .col-centered {
            display:inline-block;
            float:none;
            /* reset the text-align */
            text-align:left;
            /* inline-block space fix */
            margin-right:-4px;
        }
        /*        .file {
                    visibility: text;
                    position: absolute;
                }*/
        .label-primary {
            background-color: #5988B4 !important;
            cursor: pointer;
        }
        *, *:before, *:after {
            box-sizing: border-box;
        }




        .range-slider {
            margin: 30px 0 0 0%;
        }

        .range-slider {
            width: 100%;
        }

        .range-slider__range {
            -webkit-appearance: none;
            width: calc(100% - (73px));
            height: 10px;
            border-radius: 5px;
            background: #d7dcdf;
            outline: none;
            padding: 0;
            margin: 0;
        }
        .range-slider__range::-webkit-slider-thumb {
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #505055;
            cursor: pointer;
            transition: background .15s ease-in-out;
        }
        .range-slider__range::-webkit-slider-thumb:hover {
            background: #1abc9c;
        }
        .range-slider__range:active::-webkit-slider-thumb {
            background: #1abc9c;
        }
        .range-slider__range::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border: 0;
            border-radius: 50%;
            background: #505055;
            cursor: pointer;
            transition: background .15s ease-in-out;
        }
        .range-slider__range::-moz-range-thumb:hover {
            background: #1abc9c;
        }
        .range-slider__range:active::-moz-range-thumb {
            background: #1abc9c;
        }

        .range-slider__value {
            display: inline-block;
            position: relative;
            width: 60px;
            color: #fff;
            line-height: 20px;
            text-align: center;
            border-radius: 3px;
            background: #505055;
            padding: 5px 10px;
            margin-left: 8px;
        }
        .range-slider__value:after {
            position: absolute;
            top: 8px;
            left: -7px;
            width: 0;
            height: 0;
            border-top: 7px solid transparent;
            border-right: 7px solid #505055;
            border-bottom: 7px solid transparent;
            content: '';
        }

        ::-moz-range-track {
            background: #d7dcdf;
            border: 0;
        }

        input::-moz-focus-inner,
        input::-moz-focus-outer {
            border: 0;
        }
        hr {
            border-top: 1px dotted rgba(0,0,0,.1);
        }
        .fn-gantt .leftPanel {
            border-right: 0px solid #DDD !important;
        }
        .fn-gantt .day, .fn-gantt .date {
            width: 25px;
        }
        .trash { color:rgb(209, 91, 71); cursor: pointer;}
        .flag { color:rgb(248, 148, 6); }
        .panel-body { padding:0px; }
        .panel-footer .pagination { margin: 0; }
        .panel .glyphicon,.list-group-item .glyphicon { margin-right:5px; }
        .panel-body .radio, .checkbox { display:inline-block;margin:0px; width: 90%; cursor: pointer;}
        .panel-body input[type=checkbox]:checked + label { color: rgb(128, 144, 160); }
        .list-group-item:hover, a.list-group-item:focus {text-decoration: none;background-color: rgb(245, 245, 245);}
        .list-group { margin-bottom:0px; }
        .panel-primary {
            border-color: #fff !important; 
        }
        .funkyradio div {
            clear: both;
            overflow: text;
        }

        .funkyradio label {
            width: 100%;
            border-radius: 3px;
            border: 1px solid #D1D3D4;
            font-weight: normal;
        }

        .funkyradio input[type="radio"]:empty,
        .funkyradio input[type="checkbox"]:empty {
            display: none;
        }

        .funkyradio input[type="radio"]:empty ~ label,
        .funkyradio input[type="checkbox"]:empty ~ label {
            position: relative;
            line-height: 2.5em;
            text-indent: 3.25em;
            margin-top: 2em;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .funkyradio input[type="radio"]:empty ~ label:before,
        .funkyradio input[type="checkbox"]:empty ~ label:before {
            position: absolute;
            display: block;
            top: 0;
            bottom: 0;
            left: 0;
            content: '';
            width: 2.5em;
            background: #D1D3D4;
            border-radius: 3px 0 0 3px;
        }

        .funkyradio input[type="radio"]:hover:not(:checked) ~ label,
        .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
            color: #888;
        }

        .funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
        .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
            content: '\2714';
            text-indent: .9em;
            color: #C2C2C2;
        }

        .funkyradio input[type="radio"]:checked ~ label,
        .funkyradio input[type="checkbox"]:checked ~ label {
            color: #777;
        }

        .funkyradio input[type="radio"]:checked ~ label:before,
        .funkyradio input[type="checkbox"]:checked ~ label:before {
            content: '\2714';
            text-indent: .9em;
            color: #fff;
            background-color: #881518;
        }

        .funkyradio input[type="radio"]:focus ~ label:before,
        .funkyradio input[type="checkbox"]:focus ~ label:before {
            box-shadow: 0 0 0 3px #999;
        }

        .funkyradio-default input[type="radio"]:checked ~ label:before,
        .funkyradio-default input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #881518;
        }

        .funkyradio-primary input[type="radio"]:checked ~ label:before,
        .funkyradio-primary input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #337ab7;
        }

        .funkyradio-success input[type="radio"]:checked ~ label:before,
        .funkyradio-success input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #5cb85c;
        }

        .funkyradio-danger input[type="radio"]:checked ~ label:before,
        .funkyradio-danger input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #d9534f;
        }

        .funkyradio-warning input[type="radio"]:checked ~ label:before,
        .funkyradio-warning input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #f0ad4e;
        }

        .funkyradio-info input[type="radio"]:checked ~ label:before,
        .funkyradio-info input[type="checkbox"]:checked ~ label:before {
            color: #fff;
            background-color: #5bc0de;
        }
        .table thead.bordered-darkorange>tr>th{
            text-align: center !important;
        }

        .btn-primary.active {
            background-color: #d6d1c4!important;
            color: #000 !important;
            font-weight: 600;
        }

    </style>
    <div class="panel panel-default panel-box-shadow">
        <div class="headerForm">
            <h1>                                                            
                Control Patrimonial
            </h1>
            <h2>                                                            
                Control de Etiquetado
            </h2>
            <hr>

        </div>
        <div class="panel-body " style=" margin-top: -15px;">
            <input type="hidden" placeholder="idInventarios" id="idInventarios">
            <div class="row">
                <div class="col-md-12" id="filtros-etiquetado">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class=" col-md-12 needed titleCombo">Adscripci&oacute;n: </label>
                                <div class="col-md-12">
                                    <select data-activo="false" id="cveAdscripcion" name="cveAdscripcion" class="form-control mdb-select busqueda">
                                        <option value="">Seleccione una opci&oacute;n</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group"> 
                                    <label class=" col-md-12 needed titleCombo">Empleado Resguardo: </label>
                                    <div class="col-md-12">
                                        <select data-activo="false" id="numEmpleadoResguardo" name="numEmpleadoResguardo" class="form-control mdb-select busqueda" disabled="">
                                            <option value="">Seleccione una opci&oacute;n</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class=" col-md-12 needed titleCombo">Clasificador de Bienes: </label>
                                <div class="col-md-12">
                                    <select data-activo="false" id="cveClasificadorBien" name="cveClasificadorBien" class="form-control mdb-select busqueda">
                                        <option value="">Seleccione una opci&oacute;n</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class=" col-md-12 needed titleCombo">Estado Bien: </label>
                                <div class="col-md-12">
                                    <select data-activo="false" id="cveEstadoBien" name="cveEstadoBien" class="form-control mdb-select busqueda">
                                        <option value="">Seleccione una opci&oacute;n</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class='md-form'>
                                            <input type="text" id="codigoPropioInicio" name="codigoPropioInicio" class="form-control" onkeypress="return validar(event)" >
                                            <label for="codigoPropioInicio" class="col-md-12">C&oacute;digo Propio Inicio</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class='md-form'>
                                            <input type="text" id="codigoPropioFin" name="codigoPropioFin" class="form-control" onkeypress="return validar(event)" >
                                            <label for="codigoPropioFin" class="col-md-12">C&oacute;digo Propio Fin</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="content-cbm">

                            </div>
                            <div class="content-aah">

                            </div>
                            <div class="content-cbi">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="btn-add-buscar"> 
                                <div class="buttons-preview">
                                    <a class="btn btn-primary btn-labeled" href="#" id="btnBuscar">                    
                                        <i class="btn-label fa fa-search"></i> Buscar Bienes
                                    </a>
                                </div>  
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="btn-add-generar-etiquetas" style="display: none;"> 
                                <div class="buttons-preview">
                                    <a class="btn btn-primary btn-labeled" href="#" id="btnGenerarEtiquetas">                    
                                        <i class="btn-label fa fa-tags"></i> Generar Etiquetas
                                    </a>
                                </div>  
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-primary active">
                                    <input type="radio" id="posicionDoble" name="posicion" value="1" checked="" /> <i class="fa fa-columns" aria-hidden="true"></i> Dos Columnas
                                </label> 
                                <label class="btn btn-primary">
                                    <input type="radio" id="posicionIzquierda" name="posicion" value="2" /> <i class="fa fa-align-left" aria-hidden="true"></i> Izquierda
                                </label> 
                                <label class="btn btn-primary">
                                    <input type="radio" id="posicionDerecha" name="posicion" value="3" /> <i class="fa fa-align-right" aria-hidden="true"></i> Derecha
                                </label>                                 
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-12" id="consulta-bienes-etiquetados">
                    <div class="row">
                        <table class="table table-striped table-bordered " id="tablaGenericBienes"  style="width:100%;">
                            <thead class="bordered-darkorange">
                                <tr role="row">
                                    <th class="idInventario">#</th>                                 
                                    <th class="numeroSerie">N&uacute;mero de Serie</th>
                                    <th class="codigoPropio">C&oacute;digo Propio</th>
                                    <th class="cveClasificadorBien">Clasificador Bien</th>
                                    <th class="cveEstadoBien">Estado Bien</th>
                                    <th class="denominacion">Denominaci&oacute;n</th>
                                    <th class="descripcion">Descripci&oacute;n (Marca / Modelo)</th>
                                    <th class="cveAdscripcion">Adscripci&oacute;n</th>
                                    <th class="numEmpleadoResguardo">Empleado Reguardo</th>
                                    <th class="fechaAsignacion">Fecha Asignaci&oacute;n</th>
                                    <th class="detalle">Detalle</th>
                                    <th class="etiqueta">Generar Etiqueta</th>
                                </tr>
                            </thead>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-example-modal-lg" id="myModalAsignacion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title w-100" id="myModalLabel">Bienes</h4>
                </div>
                <div class="modal-body">
                    <div id="divInformacionDetallada">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Inventario</h2>
                                <hr>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Número Serie:</b> Número Serie</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title"><b>Código Propio:</b> Código Propio</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Código Anterior:</b> Código Anterior</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title"><b>Estado del Bien:</b> Estado del Bien</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Precio Compra:</b> Precio Compra</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title"><b>Precio Actual:</b> Precio Actual</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Años de Vida Útil:</b> Años de Vida Útil</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title"><b>Fecha de Compra:</b> Fecha de Compra</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="title"><b>Garantía:</b> Garantía</h5>
                            </div>
                            <div class="col-md-4">
                                <h5 class="title"><b>Fecha Inicio Garantía:</b> Fecha Inicio Garantía</h5>
                            </div>
                            <div class="col-md-4">
                                <h5 class="title"><b>Fecha Fin Garantía:</b> Fecha Fin Garantía</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="title"><b>Adscripción:</b> Adscripción</h5>
                            </div>
                            <div class="col-md-4">
                                <h5 class="title"><b>Empleado Resguardo:</b> Empleado Resguardo</h5>
                            </div>
                            <div class="col-md-4">
                                <h5 class="title"><b>Fecha Asignación:</b> Fecha Asignación</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Color:</b> Color</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title"><b>Material:</b> Material</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Frecuencia de Uso:</b> Diario</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title"><b>Unidad de Medida:</b> Grande</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Denominación:</b> Excelente</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title"><b>Marca:</b> Buena</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Modelo:</b> 85</h5>
                            </div>                            
                            <div class="col-md-6">
                                <h5 class="title"><b>Descripción:</b> 85</h5>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Descripción:</b> Estupenda</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title"><b>Tipo de Propiedad:</b> Mia</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Situación:</b> Muy comprometedora</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title"><b>Clave Catastral:</b> Sin comentario</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="title"><b>Descripción Escritura:</b> No pues si</h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="title"><b>Denominación:</b> Denominacion</h5>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="buttons-preview" align="center">                            
                                <a class="btn btn-primary btn-labeled" data-dismiss="modal">                    
                                    <i class="btn-label fa fa-times-circle"></i> Cerrar
                                </a>                                                                                                                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.mdb-select').chosen({allow_single_deselect: true, placeholder_text_single: "Seleccione una opci\u00F3n"});
            //EVENTOS   
            var numEmpleadoResguardo = $("#numEmpleadoResguardo").chosen().data('chosen');
            var nombreEmpleado = "";
            numEmpleadoResguardo.container.bind('keypress', function (event) {
                console.log(event);
                console.log($("#numEmpleadoResguardo").val());
                nombreEmpleado += event.key;
                console.log(nombreEmpleado);
            });
            $("#cveAdscripcion").on("change", function (e) {
                console.log("change");
                console.log($("#cveAdscripcion").val());
                if ($("#cveAdscripcion").val() != "") {
                    $("#numEmpleadoResguardo").prop("disabled", false);
                    consultarEmpleadosAdscripcion();
                } else {
                    $("#numEmpleadoResguardo").prop("disabled", true);
                }
            });


            $("#btnBuscar").on("click", function () {
                consultarTablaGenericBienes();
            });

            $("#btnGenerarEtiquetas").on("click", function () {
                generarEtiquetas();
            });
        });

        verDetalle = function (e) {
            console.log("verDetalle");
            console.log(e);
            console.log($(e).data("idinventario"));

            $.ajax({
                type: 'POST',
                url: "../fachadas/controlpatrimonial/EtiquetasFacade.Class.php",
                async: false,
                dataType: 'json',
                data: {
                    accion: "verDetalle",
                    idInventario: $(e).data("idinventario")
                },
                beforeSend: function (xhr) {
                },
                success: function (data, textStatus, jqXHR) {
                    if (data.totalCount > 0) {
                        $("#divInformacionDetallada").html("");
                        var html = '';
                        $.each(data.data, function (index, element) {
                            html += '<div class="row">';
                            html += '    <div class="col-md-12">';
                            html += '        <h2>Inventario</h2>';
                            html += '        <hr>';
                            html += '    </div>';
                            html += '</div>  ';
                            html += '<div class="row">';
                            html += '    <div class="col-md-6">';
                            html += '        <h5 class="title"><b>N\u00famero Serie:</b> ' + element.numeroSerie + '</h5>';
                            html += '    </div>';
                            html += '    <div class="col-md-6">';
                            html += '        <h5 class="title"><b>C\u00f3digo Propio:</b> ' + element.codigoPropio + '</h5>';
                            html += '    </div>';
                            html += '</div>';
                            html += '<div class="row">';
                            html += '    <div class="col-md-6">';
                            html += '        <h5 class="title"><b>C\u00f3digo Anterior:</b> ' + element.codigoAnterior + '</h5>';
                            html += '    </div>';
                            html += '    <div class="col-md-6">';
                            html += '        <h5 class="title"><b>Estado del Bien:</b> ' + element.desEstadoBien + '</h5>';
                            html += '    </div>';
                            html += '</div>';
                            html += '<div class="row">';
                            html += '    <div class="col-md-6">';
                            html += '        <h5 class="title"><b>Precio Compra:</b> ' + element.precioCompra + '</h5>';
                            html += '    </div>';
                            html += '    <div class="col-md-6">';
                            html += '        <h5 class="title"><b>Precio Actual:</b> ' + element.precioActual + '</h5>';
                            html += '    </div>';
                            html += '</div>';
                            html += '<div class="row">';
                            html += '    <div class="col-md-6">';
                            html += '        <h5 class="title"><b>A\u00f1os de Vida \u00datil:</b> ' + element.aniosVidaUtil + '</h5>';
                            html += '    </div>';
                            html += '    <div class="col-md-6">';
                            html += '        <h5 class="title"><b>Fecha de Compra:</b> ' + fechaVista(element.fechaCompra) + '</h5>';
                            html += '    </div>';
                            html += '</div>';
                            html += '<div class="row">';
                            html += '    <div class="col-md-4">';
                            html += '        <h5 class="title"><b>Garant\u00eda:</b> ' + (element.garantia == null ? "N" : element.garantia) + '</h5>';
                            html += '    </div>';
                            html += '    <div class="col-md-4">';
                            html += '        <h5 class="title"><b>Fecha Inicio Garant\u00eda:</b> ' + (element.garantia == "S" ? fechaVista(element.fechaInicioGarantia) : "N/A") + '</h5>';
                            html += '    </div>';
                            html += '    <div class="col-md-4">';
                            html += '        <h5 class="title"><b>Fecha Fin Garant\u00eda:</b> ' + (element.garantia == "S" ? fechaVista(element.fechaFinGarantia) : "N/A") + '</h5>';
                            html += '    </div>';
                            html += '</div>';
                            html += '<div class="row">';
                            html += '    <div class="col-md-4">';
                            html += '        <h5 class="title"><b>Adscripci\u00f3n:</b> ' + element.nombreAdscripcion + '</h5>';
                            html += '    </div>';
                            html += '    <div class="col-md-4">';
                            html += '        <h5 class="title"><b>Empleado Resguardo:</b> ' + element.nombreEmpleadoResguardo + '</h5>';
                            html += '    </div>';
                            html += '    <div class="col-md-4">';
                            html += '        <h5 class="title"><b>Fecha Asignaci\u00f3n:</b> ' + fechaVista(element.fechaAsigancion, true) + '</h5>';
                            html += '    </div>';
                            html += '</div>';
                            if (element.cveClasificadorBien == 1) {
                                html += '<div class="row">';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Color:</b> ' + element.desColor + '</h5>';
                                html += '    </div>';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Material:</b> ' + element.desMaterial + '</h5>';
                                html += '    </div>';
                                html += '</div>';
                                html += '<div class="row">';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Frecuencia de Uso:</b> ' + element.desFrecuenciaUso + '</h5>';
                                html += '    </div>';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Unidad de Medida:</b> ' + element.desUnidadMedida + '</h5>';
                                html += '    </div>';
                                html += '</div>';
                                html += '<div class="row">';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Denominaci\u00f3n:</b> ' + element.denominacion + '</h5>';
                                html += '    </div>';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Marca:</b> ' + element.marca + '</h5>';
                                html += '    </div>';
                                html += '</div>';
                                html += '<div class="row">';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Modelo:</b> ' + element.modelo + '</h5>';
                                html += '    </div>                            ';
                                html += '</div>';
                            } else if (element.cveClasificadorBien == 2) {
                                html += '<div class="row">';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Tipo de Propiedad:</b>  ' + element.desTipoPropiedad + '</h5>';
                                html += '    </div>';
                                html += '</div>';
                                html += '<div class="row">';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Situaci\u00f3n:</b>  ' + element.desSituacion + '</h5>';
                                html += '    </div>';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Clave Catastral:</b> ' + element.cveCatastral + '</h5>';
                                html += '    </div>';
                                html += '</div>';
                                html += '<div class="row">';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Descripci\u00f3n Escritura:</b> ' + element.cveCatastral + '</h5>';
                                html += '    </div>';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Denominaci\u00f3n:</b> ' + element.denominacion + '</h5>';
                                html += '    </div>';
                                html += '</div>';
                            } else if (element.cveClasificadorBien == 7) {
                                html += '<div class="row">';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Descripci\u00f3n Escritura:</b>' + element.descripcion + '</h5>';
                                html += '    </div>';
                                html += '    <div class="col-md-6">';
                                html += '        <h5 class="title"><b>Denominaci\u00f3n:</b>' + element.denominacion + '</h5>';
                                html += '    </div>';
                                html += '</div>';
                            }
                        });
                        $("#divInformacionDetallada").html(html);
                        $("#myModalAsignacion").modal({
                            backdrop: 'static',
                            keyboard: false,
                        });
                    } else {
                        $("#divInformacionDetallada").html("");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });


        }

        generarEtiqueta = function (e) {
            console.log("generarEtiqueta");
            var parametros = "data=" + $(e).data("idinventario") + "&posicion=" + $('input[name=posicion]:checked').val();
            showPDF({
                data: [
                    {cveDocumento: 1, type: 'Etiquetas', ruta: '../plantillashtml/qrcode.php?' + parametros}
                ]
            }, "Etiquetas", false, 1);
        }

        generarEtiquetas = function () {
            console.log("generarEtiquetas");
            var parametros = "data=" + $("#idInventarios").val() + "&posicion=" + $('input[name=posicion]:checked').val();
            showPDF({
                data: [
                    {cveDocumento: 1, type: 'Etiquetas', ruta: '../plantillashtml/qrcode.php?' + parametros}
                ]
            }, "Etiquetas", false, 1);
        };


        consultarEmpleadosAdscripcion = function () {
            $.ajax({
                type: 'POST',
                url: "../fachadas/controlpatrimonial/EtiquetasFacade.Class.php",
                async: false,
                dataType: 'json',
                data: {
                    accion: "consultarEmpleadosAdscripcion",
                    cveAdscripcion: $("#cveAdscripcion").val()
                },
                beforeSend: function (xhr) {
                },
                success: function (data, textStatus, jqXHR) {
                    var numEmpleadoResguardo = $("#numEmpleadoResguardo");
                    numEmpleadoResguardo.empty();
                    numEmpleadoResguardo.append('<option value="">Seleccione una opci\u00f3n</option>');
                    numEmpleadoResguardo.trigger("chosen:updated");
                    $.each(data, function (index, element) {
                        if(element.CveStatus == 1){
                            if (element.TituloTrato != null || element.TituloTrato != "NULL" || element.TituloTrato != "") {
                                numEmpleadoResguardo.append('<option value="' + element.NumEmpleado + '">' + element.TituloTrato + " " + element.Nombre + " " + element.Paterno + " " + element.Materno + '</option>');
                                numEmpleadoResguardo.trigger("chosen:updated");
                            } else {
                                numEmpleadoResguardo.append('<option value="' + element.NumEmpleado + '">' + element.Nombre + " " + element.Paterno + " " + element.Materno + '</option>');
                                numEmpleadoResguardo.trigger("chosen:updated");
                            }
                        }
                    });

                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        };

        function validar(e) { // 1
            var tecla = (document.all) ? e.keyCode : e.which; // 2
            if (tecla == 8) {
                return true;
            } else { // 3
                var patron = /[0123456789]/; // 4
                var te = String.fromCharCode(tecla); // 5
                return patron.test(te);

            }// 6
        }

        cargarAdcripciones = function () {
            $.getJSON('../archivos/informacionEmpleados.json', function (data) {
                var cveAdscripcion = $("#cveAdscripcion");
                cveAdscripcion.empty();
                cveAdscripcion.append('<option value="">Seleccione una opci\u00f3n</option>');
                cveAdscripcion.trigger("chosen:updated");
                $.each(data.data, function (index, element) {
                    cveAdscripcion.append('<option data-organigrama="' + element.cveOrganigrama + '" value="' + element.idJuzgado + '">' + element.desJuz + '</option>');
                    cveAdscripcion.trigger("chosen:updated");
                });
            });
        };

        cargarClasificadorBienes = function () {
            $.ajax({
                type: 'POST',
                url: "../fachadas/controlpatrimonial/EtiquetasFacade.Class.php",
                async: false,
                dataType: 'json',
                data: {
                    accion: "cargarClasificadorBienes"
                },
                beforeSend: function (xhr) {
                },
                success: function (data, textStatus, jqXHR) {
                    var cveClasificadorBien = $("#cveClasificadorBien");
                    cveClasificadorBien.empty();
                    if (data.totalCount > 0) {
                        $.each(data.data, function (index, element) {
                            if (element.cveClasificadorBien == "1" || element.cveClasificadorBien == "2" || element.cveClasificadorBien == "7") {
                                cveClasificadorBien.append('<option value="' + element.cveClasificadorBien + '">' + element.desClasificadorBien + '</option>');
                                cveClasificadorBien.trigger("chosen:updated");
                            }
                        });
                    } else {
                        cveClasificadorBien.append('<option value="">Seleccione una opci\u00f3n</option>');
                        cveClasificadorBien.trigger("chosen:updated");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        };
        cargarEstadosBienes = function () {
            $.ajax({
                type: 'POST',
                url: "../fachadas/controlpatrimonial/EtiquetasFacade.Class.php",
                async: false,
                dataType: 'json',
                data: {
                    accion: "cargarEstadosBienes"
                },
                beforeSend: function (xhr) {
                },
                success: function (data, textStatus, jqXHR) {
                    var cveEstadoBien = $("#cveEstadoBien");
                    cveEstadoBien.empty();
                    if (data.totalCount > 0) {
                        cveEstadoBien.append('<option value="">Seleccione una opci\u00f3n</option>');
                        cveEstadoBien.trigger("chosen:updated");
                        $.each(data.data, function (index, element) {
                            cveEstadoBien.append('<option value="' + element.cveEstadoBien + '">' + element.desEstadoBien + '</option>');
                            cveEstadoBien.trigger("chosen:updated");
                        });
                    } else {
                        cveEstadoBien.append('<option value="">Seleccione una opci\u00f3n</option>');
                        cveEstadoBien.trigger("chosen:updated");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        };

        consultarTablaGenericBienes = function () {
            // HTML
            //        0 - idInventario         - #                                
            //        1 - numeroSerie          - N&uacute;mero de Serie
            //        2 - cveClasificadorBien  - Clasificador Bien
            //        3 - cveEstadoBien        - Estado Bien
            //        4 - denominacion         - Denominaci&oacute;n
            //        5 - descripcion          - Descripci&oacute;n (Marca / Modelo)
            //        6 - cveAdscripcion       - Adscripci&oacute;n
            //        7 - numEmpleadoResguardo - Empleado Reguardo
            //        8 - fechaAsignacion      - Fecha Asignaci&oacute;n  

            // JSON
            //        0 tblinventarios.idInventario,
            //        1 tblinventarios.cveClasificadorBien,
            //        2 tblclasificadoresbienes.desClasificadorBien,
            //        3 tblinventarios.cveEstadoBien,
            //        4 tblestadosbienes.desEstadoBien,
            //        5 tblinventarios.numeroSerie,
            //        6 tblresguardos.cveAdscripcion,
            //        7 tblresguardos.descAdscripcion,
            //        8 tblresguardos.NombreEmpleadoResguardo,
            //        9 tblresguardos.numEmpleadoResguardo,
            //       10 tblresguardos.fechaAsigancion

            //1 JSON
            //       12 tblcbm.idCbm,
            //       13 tblcbm.idCogBien,                                
            //       14 tblcbm.denominacion,
            //       15 tblcbm.cbmPropio,
            //       16 tblcbm.marca,
            //       17 tblcbm.modelo

            //2 JSON
            //       12 tblaah.idAah,
            //       13 tblaah.idCogBien,
            //       14 tblaah.denominacion,
            //       15 tblaah.aahPropio 
            //       16 tblcogbienes.descripcion

            //7 JSON
            //      12 tblcbi.idCbi,
            //      13 tblcbi.idCogBien,   
            //      14 tblcbi.denominacion,
            //      15 tblcbi.cbiPropio 
            //      16 tblcogbienes.descripcion

            var columnDefs = [];
            columnDefs[0] = {"targets": [0], "visible": false};
            columnDefs[1] = {responsivePriority: 1, targets: 0};
            columnDefs[2] = {responsivePriority: 2, targets: -2};
            if ($("#cveClasificadorBien").val() == "1") {
                columnDefs[3] = {
                    "targets": "idInventario",
                    "render": function (data, type, row) {
                        return row[0];
                    }
                };
                columnDefs[4] = {
                    "targets": "numeroSerie",
                    "render": function (data, type, row) {
                        return row[5];
                    }
                };
                columnDefs[5] = {
                    "targets": "cveClasificadorBien",
                    "render": function (data, type, row) {
                        return row[2];
                    }
                };
                columnDefs[6] = {
                    "targets": "cveEstadoBien",
                    "render": function (data, type, row) {
                        return row[4];
                    }
                };
                columnDefs[7] = {
                    "targets": "denominacion",
                    "render": function (data, type, row) {
                        return row[13];
                    }
                };
                columnDefs[8] = {
                    "targets": "cveAdscripcion",
                    "render": function (data, type, row) {
                        return row[7];
                    }
                };
                columnDefs[9] = {
                    "targets": "descripcion",
                    "render": function (data, type, row) {
                        return row[15] + " / " + row[16];
                    }
                };
                columnDefs[10] = {
                    "targets": "numEmpleadoResguardo",
                    "render": function (data, type, row) {
                        return row[8];
                    }
                };
                columnDefs[11] = {
                    "targets": "fechaAsignacion",
                    "render": function (data, type, row) {
                        return row[10];
                    }
                };
                columnDefs[12] = {
                    "targets": "detalle",
                    "render": function (data, type, row) {
                        return '<span data-idinventario="' + row[0] + '" onclick="verDetalle(this)" class="label label-primary pointer"><span class="fa fa-plus"></span></span>';
                    }
                };
                columnDefs[13] = {
                    "targets": "etiqueta",
                    "render": function (data, type, row) {
                        return '<span data-idinventario="' + row[0] + '" onclick="generarEtiqueta(this)" class="label label-primary pointer"><span class="fa fa-cog"></span></span>';
                    }
                };
                columnDefs[14] = {
                    "targets": "codigoPropio",
                    "render": function (data, type, row) {
                        return row[14];
                    }
                };

            }
            if ($("#cveClasificadorBien").val() == "2") {
                columnDefs[3] = {
                    "targets": "idInventario",
                    "render": function (data, type, row) {
                        return row[0];
                    }
                };
                columnDefs[4] = {
                    "targets": "numeroSerie",
                    "render": function (data, type, row) {
                        return row[5];
                    }
                };
                columnDefs[5] = {
                    "targets": "cveClasificadorBien",
                    "render": function (data, type, row) {
                        return row[2];
                    }
                };
                columnDefs[6] = {
                    "targets": "cveEstadoBien",
                    "render": function (data, type, row) {
                        return row[4];
                    }
                };
                columnDefs[7] = {
                    "targets": "denominacion",
                    "render": function (data, type, row) {
                        return row[14];
                    }
                };
                columnDefs[8] = {
                    "targets": "cveAdscripcion",
                    "render": function (data, type, row) {
                        return row[7];
                    }
                };
                columnDefs[9] = {
                    "targets": "descripcion",
                    "render": function (data, type, row) {
                        return row[16];
                    }
                };
                columnDefs[10] = {
                    "targets": "numEmpleadoResguardo",
                    "render": function (data, type, row) {
                        return row[8];
                    }
                };
                columnDefs[11] = {
                    "targets": "fechaAsignacion",
                    "render": function (data, type, row) {
                        return row[10];
                    }
                };
                columnDefs[12] = {
                    "targets": "codigoPropio",
                    "render": function (data, type, row) {
                        return row[14];
                    }
                };
            }
            if ($("#cveClasificadorBien").val() == "7") {
                columnDefs[3] = {
                    "targets": "idInventario",
                    "render": function (data, type, row) {
                        return row[0];
                    }
                };
                columnDefs[4] = {
                    "targets": "numeroSerie",
                    "render": function (data, type, row) {
                        return row[5];
                    }
                };
                columnDefs[5] = {
                    "targets": "cveClasificadorBien",
                    "render": function (data, type, row) {
                        return row[2];
                    }
                };
                columnDefs[6] = {
                    "targets": "cveEstadoBien",
                    "render": function (data, type, row) {
                        return row[4];
                    }
                };
                columnDefs[7] = {
                    "targets": "denominacion",
                    "render": function (data, type, row) {
                        return row[14];
                    }
                };
                columnDefs[8] = {
                    "targets": "descripcion",
                    "render": function (data, type, row) {
                        return row[16];
                    }
                };
                columnDefs[9] = {
                    "targets": "cveAdscripcion",
                    "render": function (data, type, row) {
                        return row[7];
                    }
                };
                columnDefs[10] = {
                    "targets": "numEmpleadoResguardo",
                    "render": function (data, type, row) {
                        return row[8];
                    }
                };
                columnDefs[11] = {
                    "targets": "fechaAsignacion",
                    "render": function (data, type, row) {
                        return row[10];
                    }
                };
                columnDefs[12] = {
                    "targets": "codigoPropio",
                    "render": function (data, type, row) {
                        return row[14];
                    }
                };
            }

            var tablaGenericBienes = $("#tablaGenericBienes").DataTable({
                destroy: true,
                responsive: true,
                "columnDefs": columnDefs,
                sDom: configDataTableAlineacion,
                aaSorting: [[0, 'asc']],
                aLengthMenu: [
                    [10, 20, 50, 100, 200, 300, 500, 1000],
                    [10, 20, 50, 100, 200, 300, 500, 1000]
                ],
                iDisplayLength: 10,
                oTableTools: {
                    aButtons: [],
                    sSwfPath: "../swf/copy_csv_xls_pdf.swf"},
                language: configDataIdiomaDataTable,
                processing: false,
                serverSide: true,
                bPaginate: true,
                bSort: true,
                ajax: {
                    "type": "POST",
                    "url": "../fachadas/controlpatrimonial/EtiquetasFacade.Class.php",
                    "asynccveIdeal": true,
                    "data": function (d) {
                        d.accion = "datatableConsultaBienes";
                        d.cveClasificadorBien = $("#cveClasificadorBien").val();
                        d.cveAdscripcion = $("#cveAdscripcion").val();
                        d.numEmpleadoResguardo = $("#numEmpleadoResguardo").val();
                        d.cveEstadoBien = $("#cveEstadoBien").val();
                        d.codigoPropioInicio = $("#codigoPropioInicio").val();
                        d.codigoPropioFin = $("#codigoPropioFin").val();
                    }
                }
            }).on('xhr.dt', function (e, settings, json, xhr) {
                console.log("JSON");
                console.log(json);
                if (json.recordsTotal > 0) {
                    $("#btnGenerarEtiquetas").parent().parent().show();
                    $.each(json.data, function (index, element) {
                        if (index == 0) {
                            $("#idInventarios").val(element[0]);
                        } else {
                            $("#idInventarios").val($("#idInventarios").val() + "," + element[0]);
                        }
                    });
                } else {
                    $("#btnGenerarEtiquetas").parent().parent().hide();
                }
            });
        };

        $(function () {
            cargarAdcripciones();
            cargarClasificadorBienes();
            cargarEstadosBienes();
            //            consultarTablaGenericBienes();
        });
    </script>
