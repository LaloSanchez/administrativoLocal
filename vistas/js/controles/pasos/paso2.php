<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>
    <body>

        <!--INICIO-->
        <div class="step-pane active" id="simplewizardstep2">
            <input value="" id="iId_ImputadoSolicitud" name="iId_ImputadoSolicitud" type="hidden">
            <input value="" id="TxtEstado" name="TxtEstado" type="hidden">
            <input value="" id="TxtMunicipio" name="TxtMunicipio" type="hidden">
            <div class="widget-body">
                <div class="widget-main ">
                    <div class="tabbable">
                        <ul id="myTab11" class="nav nav-tabs tabs-flat">
                            <li class="active">
                                <a href="#AGREGARNUEVOIMPUTADO" data-toggle="tab" aria-expanded="true"> AGREGAR NUEVO IMPUTADO </a>
                            </li>
                            <li class="">
                                <a href="#LISTAIMPUTADOS" data-toggle="tab" aria-expanded="false" class="imputados_tabla"> LISTA DE IMPUTADOS </a>
                            </li>
                        </ul>
                        <div class="tab-content tabs-flat">
                            <div id="AGREGARNUEVOIMPUTADO" class="tab-pane active">  
                                <div class="row container-fluid" whith="100%">
                                    <div class="col-lg-12 col-sm-12 col-xs-12">
                                        <div class="tabbable ">
                                            <div class="row">
                                                <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2">
                                                    <ul id="myTab3" class="nav nav-tabs ">
                                                        <li class="tab-green lIImpGeneral " id="LIImpGenerales">
                                                            <a href="#ImpGenerales" data-toggle="tab" aria-expanded="false"> 2.1 GENERAL </a>
                                                        </li>
                                                        <li class="tab-green lIImpDomicilios  " id="LIImpDomicilios">
                                                            <a href="#ImpDomicilios" data-toggle="tab" aria-expanded="true" class="imputados_tabla"> 2.2 DOMICILIOS </a>
                                                        </li>
                                                        <li class="tab-green lIImpTelefonos" id="LIImpTelefonos">
                                                            <a href="#ImpTelefonos" data-toggle="tab" aria-expanded="true" class="imputados_tabla"> 2.3 TEL&EacuteFONOS </a>
                                                        </li>
                                                        <li class="tab-green lIImpDefensores" id="LIImpDefensores">
                                                            <a href="#ImpDefensores" data-toggle="tab" aria-expanded="true" class="imputados_tabla"> 2.4 DEFENSOR </a>
                                                        </li>
                                                        <li class="tab-green lIImpDrogas" id="LIImpDrogas">
                                                            <a href="#ImpDrogas" data-toggle="tab" aria-expanded="true" class="imputados_tabla"> 2.5 DROGAS &nbsp;&nbsp; </a>
                                                        </li>
                                                        <li class="tab-green lIImpTutores" id="LIImpTutores">
                                                            <a href="#ImpTutores" data-toggle="tab" aria-expanded="true" class="imputados_tabla"> 2.6 TUTORES </a>
                                                        </li>
                                                        <li class="tab-green lIImpNacionalidades" id="LIImpNacionalidades">
                                                            <a href="#ImpNacionalidades" data-toggle="tab" aria-expanded="true" class="imputados_tabla"> 2.7NACIONALIDADES </a>
                                                        </li>

                                                    </ul>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="tab-content" id="tabPaso2">
                                                        <div id="ImpGenerales" class="tab-pane active">
                                                            <div id="divPaso2" data-rel="cat_imputadossolicitudes">
                                                                <div class="tabbable">
                                                                    <div id="div_respuesta">
                                                                    </div>
                                                                    <ul id="myTab" class="nav nav-tabs">
                                                                        <li class="active tab-red ">
                                                                            <a aria-expanded="false" data-toggle="tab" href="#INGRESE LA INFORMACI”N DEL IMPUTADO">INGRESE LA INFORMACION DEL IMPUTADO</a>
                                                                        </li>
                                                                    </ul>
                                                                    <form data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-message="This value is not valid" novalidate="novalidate" role="form" class="form-horizontal bv-form" id="cat_imputadossolicitudes">
                                                                        <button style="display: none; width: 0px; height: 0px;" class="bv-hidden-submit" type="submit"></button>
                                                                        <div class="tab-content"><div class="tab-pane in active" id="INGRESE LA INFORMACI”N DEL IMPUTADO">
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_TipoPersona">* TIPO PERSONA</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option>PERSONA FISICA</option>
                                                                                            <option>PERSONA MORAL</option>
                                                                                            <option>OTRA</option>
                                                                                        </select>

                                                                                        <input title="* TIPO PERSONA" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_tipospersonas" name="iId_TipoPersona" id="iId_TipoPersona" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label style="display: none;" class="col-sm-3 control-label no-padding-right" for="sNombreMoral">* NOMBRE PERSONA MORAL</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input style="display: none;" data-bv-field="sNombreMoral" data-bv-regexp-message="El campo * nombre persona moral debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="500" placeholder="* NOMBRE PERSONA MORAL" class="form-control" name="sNombreMoral" id="sNombreMoral" type="text">
                                                                                        <i data-bv-icon-for="sNombreMoral" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombreMoral" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * nombre persona moral debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombreMoral" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="sNombre">* NOMBRE</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input data-bv-field="sNombre" data-bv-regexp-message="El campo * nombre debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="50" placeholder="* NOMBRE" class="form-control" name="sNombre" id="sNombre" type="text">
                                                                                        <i data-bv-icon-for="sNombre" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombre" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * nombre debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombre" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="sPaterno">* PATERNO</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input data-bv-field="sPaterno" data-bv-regexp-message="El campo * paterno debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="50" placeholder="* PATERNO" class="form-control" name="sPaterno" id="sPaterno" type="text">
                                                                                        <i data-bv-icon-for="sPaterno" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sPaterno" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * paterno debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sPaterno" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="sMaterno">MATERNO</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input data-bv-field="sMaterno" data-bv-regexp-message="El campo materno debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="50" placeholder="MATERNO" class="form-control" name="sMaterno" id="sMaterno" type="text">
                                                                                        <i data-bv-icon-for="sMaterno" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sMaterno" data-bv-validator="regexp" class="help-block" style="display: none;">El campo materno debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sMaterno" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="sAlias">ALIAS</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input data-bv-field="sAlias" maxlength="500" placeholder="ALIAS" class="form-control" name="sAlias" id="sAlias" type="text">
                                                                                        <i data-bv-icon-for="sAlias" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sAlias" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="sRfc">* RFC</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input data-bv-field="sRfc" data-bv-regexp-message="El campo * rfc debe tener esta estructura:" data-bv-regexp-regexp=""data-bv-regexp="true" maxlength="13" placeholder="* RFC" class="form-control" name="sRfc" id="sRfc" type="text"><i data-bv-icon-for="sRfc" class="form-control-feedback" style="display: none;"></i><small data-bv-result="NOT_VALIDATED" data-bv-for="sRfc" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * rfc debe tener esta estructura: 

                                                                                        </small>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sRfc" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="sCurp">* CURP</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input data-bv-field="sCurp" maxlength="20" placeholder="* CURP" class="form-control" name="sCurp" id="sCurp" type="text">
                                                                                        <i data-bv-icon-for="sCurp" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sCurp" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_TipoPersona">* G&EacuteNERO</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option>HOMBRE</option>
                                                                                            <option>MUJER</option>
                                                                                            <option>NO IDENTIFICADO</option>
                                                                                        </select>

                                                                                        <input title="* TIPO PERSONA" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_tipospersonas" name="iId_TipoPersona" id="iId_TipoPersona" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="dFechaNacimiento">* FECHA NACIMIENTO</label>
                                                                                    <div class="col-sm-8">
                                                                                        <input maxlength="" placeholder="* FECHA NACIMIENTO" class="form-control" name="dFechaNacimiento" id="dFechaNacimiento" type="text">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iEdad">* EDAD</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input data-bv-field="iEdad" data-bv-regexp-message="Solo se permiten n˙meros" data-bv-regexp-regexp="[0-9]+$" data-bv-regexp="true" maxlength="3" placeholder="* EDAD" class="form-control" name="iEdad" id="iEdad" type="text">
                                                                                        <i data-bv-icon-for="iEdad" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="iEdad" data-bv-validator="regexp" class="help-block" style="display: none;">Solo se permiten n˙meros</small>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="iEdad" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_PaisNacimiento">* PAIS NACIMIENTO</label>
                                                                                    <div class="col-sm-8">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option> AUSTRIA</option>
                                                                                            <option> MEXICO</option>
                                                                                            <option>FRANCIA</option>
                                                                                        </select>
                                                                                        <input title="* PAIS NACIMIENTO" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_paises" name="iId_PaisNacimiento" id="iId_PaisNacimiento" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iEstadoNacimiento">* ESTADO NACIMIENTO</label>
                                                                                    <div class="col-sm-8">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option> AUSTRIA</option>
                                                                                            <option> MEXICO</option>
                                                                                            <option>FRANCIA</option>
                                                                                        </select>
                                                                                        <input title="* PAIS NACIMIENTO" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_paises" name="iId_PaisNacimiento" id="iId_PaisNacimiento" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group" id="nuevoMunicipio">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="municipiosnuevos">* MUNICIPIO NACIMIENTO</label>
                                                                                    <div class="col-sm-8">
                                                                                        <select id="municipiosnuevos" style="width:100%;" class="form-control">
                                                                                            <option> </option>
                                                                                            <option> AUSTRIA</option>
                                                                                            <option> MEXICO</option>
                                                                                            <option>FRANCIA</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label style="display: none;" class="col-sm-3 control-label no-padding-right" for="sEstadoNacimiento">* ESTADO NACIMIENTO</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input style="display: none;" data-bv-field="sEstadoNacimiento" data-bv-regexp-message="El campo * estado nacimiento debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="200" placeholder="* ESTADO NACIMIENTO" class="form-control" name="sEstadoNacimiento" id="sEstadoNacimiento" type="text">
                                                                                        <i data-bv-icon-for="sEstadoNacimiento" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sEstadoNacimiento" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * estado nacimiento debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sEstadoNacimiento" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback">
                                                                                    <label style="display: none;" class="col-sm-3 control-label no-padding-right" for="sMunicipioNacimiento">* MUNICIPIO NACIMIENTO</label>
                                                                                    <div class="col-sm-9"><input style="display: none;" data-bv-field="sMunicipioNacimiento" data-bv-regexp-message="El campo * municipio nacimiento debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="200" placeholder="* MUNICIPIO NACIMIENTO" class="form-control" name="sMunicipioNacimiento" id="sMunicipioNacimiento" type="text">
                                                                                        <i data-bv-icon-for="sMunicipioNacimiento" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sMunicipioNacimiento" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * municipio nacimiento debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sMunicipioNacimiento" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_Nivel">* NIVEL INSTRUCCI&OacuteN</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option> PREPARATORIA</option>
                                                                                            <option> CARRERA TECNICA </option>
                                                                                            <option>CARRERA COMERCIAL</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_Estadocivil">* ESTADO CIVIL</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option> PREPARATORIA</option>
                                                                                            <option> CARRERA TECNICA </option>
                                                                                            <option>CARRERA COMERCIAL</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_Ocupacion">*  OCUPACION</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option> PREPARATORIA</option>
                                                                                            <option> CARRERA TECNICA </option>
                                                                                            <option>CARRERA COMERCIAL</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_Espanol">*  ESPA&#327OL</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option> PREPARATORIA</option>
                                                                                            <option> CARRERA TECNICA </option>
                                                                                            <option>CARRERA COMERCIAL</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_Alfabetismo">*  ALFABETISMO</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option> PREPARATORIA</option>
                                                                                            <option> CARRERA TECNICA </option>
                                                                                            <option>CARRERA COMERCIAL</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_Dialecto">*   DIALECTO INDIGENA</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option> PREPARATORIA</option>
                                                                                            <option> CARRERA TECNICA </option>
                                                                                            <option>CARRERA COMERCIAL</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_Interprete">*  INTERPRETE</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select class="form-control col-sm-9">
                                                                                            <option></option>
                                                                                            <option> PREPARATORIA</option>
                                                                                            <option> CARRERA TECNICA </option>
                                                                                            <option>CARRERA COMERCIAL</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_Psicofico">*  PSICOFISICO</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select class="form-control">
                                                                                            <option></option>
                                                                                            <option> PREPARATORIA</option>
                                                                                            <option> CARRERA TECNICA </option>
                                                                                            <option>CARRERA COMERCIAL</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="irelacionMoral">* RELACI&OacuteN PERSONA MORAL</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input name="irelacionMoral" id="irelacionMoral" value="0" type="hidden">
                                                                                        <input class="checkbox-slider colored-blue" name="irelacionMoral" id="irelacionMoral" value="1" type="checkbox">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group" id="divRelacionpersonamoral">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="nirelacionpersonamoral">* PERSONA MORAL</label>
                                                                                    <input id="s2id_autogen21" aria-labelledby="select2-chosen-21" class="select2-focusser select2-offscreen" aria-haspopup="true" role="button" type="hidden">
                                                                                    <div class="col-sm-9">
                                                                                        <select title="RELACI”N PERSONA MORAL"  id="nirelacionpersonamoral" class="form-control" >
                                                                                            <option value=""></option>
                                                                                            <option value="1">SI</option>
                                                                                            <option value="0">NO</option>
                                                                                        </select>
                                                                                    </div>

                                                                                </div>

                                                                                <div class="form-group has-feedback">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="singresoMensual">* INGRESO MENSUAL</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input data-bv-field="singresoMensual" maxlength="50" placeholder="* INGRESO MENSUAL" class="form-control" name="singresoMensual" id="singresoMensual" type="text">
                                                                                        <i data-bv-icon-for="singresoMensual" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="singresoMensual" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label style="display: none;" class="col-sm-3 control-label no-padding-right" for="idetenido">DETENIDO</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input style="display: none;" name="idetenido" id="idetenido" value="" type="hidden">
                                                                                        <input class="checkbox-slider colored-blue" name="idetenido" id="idetenido" value="1" type="hidden">
                                                                                        <span class="text"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group" id="divP2Detenido">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="niP2Detenido">DETENIDO</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select title="DETENIDO" tabindex="-1" id="niP2Detenido" class="form-control">
                                                                                            <option></option>
                                                                                            <option value="1">SI</option>
                                                                                            <option value="0">NO</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="dfechaImputacion">FECHA IMPUTACI&OacuteN</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input disabled="disabled" maxlength="" placeholder="FECHA IMPUTACI&Oacute;N" class="form-control" name="dfechaImputacion" id="dfechaImputacion" type="text">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="dfechaControlDet">FECHA DETENCI&OacuteN</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input disabled="disabled" maxlength="" placeholder="FECHA DETENCI&Oacute;N" class="form-control" name="dfechaControlDet" id="dfechaControlDet" type="text">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="dfechaDeclaracion">FECHA DECLARACI&OacuteN</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input disabled="disabled" maxlength="" placeholder="FECHA DECLARACI&Oacute;N" class="form-control" name="dfechaDeclaracion" id="dfechaDeclaracion" type="text">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="iId_TipoDetencion">TIPO DETENCI&OacuteN</label>
                                                                                    <div class="col-sm-9">
                                                                                        <select  class="form-control">
                                                                                            <option></option>
                                                                                            <option value="1">SI</option>
                                                                                            <option value="0">NO</option>
                                                                                        </select>
                                                                                        <input disabled="" title="TIPO DETENCI”N" tabindex="-1" data-llave="iId" data-campo="sdesTipoDetencion" data-tabla="cat_tiposdetenciones" name="iId_TipoDetencion" id="iId_TipoDetencion" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                    <div class="modal-footer">
                                                                        <button data-bb-handler="success" type="button" class="btn guardar-registro btn-success">Guardar</button>
                                                                        <button data-bb-handler="Limpiar" type="button" class="btn btn-blue">Limpiar</button>
                                                                        <button data-bb-handler="Cerrar" type="button" class="btn btn-danger">Cerrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>

                                                        <div id="ImpDomicilios" class="tab-pane " data-rel="cat_domiciliosimputadossolicitudes" data-columns="sNombre,sColonia,iNumExterior,iId">
                                                            <div class="buttons-preview padding-10">
                                                                <div class="modal-body">
                                                                    <div class="bootbox-body">
                                                                        <div class="tabbable">
                                                                            <div class="tab-content">
                                                                                <div class="tab-pane in active" id="REGISTRAR">
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-4 control-label no-padding-right" for="iId_Pais">* PAIS DOMICILIO</label>
                                                                                        <div class="col-sm-8">
                                                                                            <select class="form-control col-sm-9">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>
                                                                                            <input title="* PAIS DOMICILIO" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_paises" name="iId_Pais" id="iId_Pais" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-4 control-label no-padding-right" for="iId_Estado">* ESTADO DOMICILIO</label>
                                                                                        <div class="col-sm-8">

                                                                                            <select class="form-control col-sm-12">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>
                                                                                        </div><input title="* ESTADO DOMICILIO" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_estados" name="iId_Estado" id="iId_Estado" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-4 control-label no-padding-right" for="iId_Municipio">* MUNICIPIO DOMICILIO</label>
                                                                                        <div class="col-sm-8">
                                                                                            <select class="form-control col-sm-9">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <input title="* MUNICIPIO DOMICILIO" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_municipios" name="iId_Municipio" id="iId_Municipio" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-4 control-label no-padding-right" for="sEstado">* ESTADO DOMICILIO</label>
                                                                                        <div class="col-sm-8">
                                                                                            <select class="form-control col-sm-9">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-4 control-label no-padding-right" for="sMunicipio">* MUNICIPIO DOMICILIO</label>
                                                                                        <div class="col-sm-8">
                                                                                            <input data-bv-field="sMunicipio" data-bv-regexp-message="El campo * municipio domicilio debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="201" placeholder="* MUNICIPIO DOMICILIO" class="form-control" name="sMunicipio" id="sMunicipio" type="hidden">
                                                                                            <select class="form-control col-sm-9">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="iId_Convivencia">* CONVIVENCIA</label>
                                                                                        <div class="col-sm-9">
                                                                                            <select class="form-control col-sm-9">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>
                                                                                            <input title="* CONVIVENCIA" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_convivencias" name="iId_Convivencia" id="iId_Convivencia" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="sNombre">* DIRECCI&OacuteN</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input data-bv-field="sNombre" maxlength="500" placeholder="* DIRECCI&Oacute;N" class="form-control" name="sNombre" id="sNombre" type="text">
                                                                                            <i data-bv-icon-for="sNombre" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombre" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="sColonia">* COLONIA</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input data-bv-field="sColonia" data-bv-regexp-message="El campo * colonia debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="200" placeholder="* COLONIA" class="form-control" name="sColonia" id="sColonia" type="text">
                                                                                            <i data-bv-icon-for="sColonia" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sColonia" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * colonia debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sColonia" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-4 control-label no-padding-right" for="iNumInterior">* N&Uacute;MERO INTERIOR</label>
                                                                                        <div class="col-sm-8">
                                                                                            <input data-bv-field="iNumInterior" maxlength="10" placeholder="* N&Uacute;MERO INTERIOR" class="form-control" name="iNumInterior" id="iNumInterior" type="text">
                                                                                            <i data-bv-icon-for="iNumInterior" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="iNumInterior" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback"><label class="col-sm-4 control-label no-padding-right" for="iNumExterior">* N&Uacute;MERO EXTERIOR</label>
                                                                                        <div class="col-sm-8"><input data-bv-field="iNumExterior" maxlength="10" placeholder="* N&Uacute;MERO EXTERIOR" class="form-control" name="iNumExterior" id="iNumExterior" type="text">
                                                                                            <i data-bv-icon-for="iNumExterior" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="iNumExterior" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="iCp">* CP</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input data-bv-field="iCp" data-bv-regexp-message="El campo * cp debe tener esta estructura: ([0-9]{5})$" data-bv-regexp-regexp="([0-9]{5})$" data-bv-regexp="true" maxlength="5" placeholder="* CP" class="form-control" name="iCp" id="iCp" type="text">
                                                                                            <i data-bv-icon-for="iCp" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="iCp" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * cp debe tener esta estructura: ([0-9]{5})$</small>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="iCp" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-4 control-label no-padding-right" for="iRecidenciaHabitual">* RESIDENCIA HABITUAL</label>
                                                                                        <div class="col-sm-8">
                                                                                            <input name="iRecidenciaHabitual" id="iRecidenciaHabitual" value="0" type="hidden">
                                                                                            <input class="checkbox-slider colored-blue" name="iRecidenciaHabitual" id="iRecidenciaHabitual" value="1" type="checkbox">
                                                                                            <span class="text"> </span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="iId_TipoDeVivienda">* TIPO VIVIENDA</label>
                                                                                        <div class="col-sm-9">
                                                                                            <select class="form-control col-sm-9">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>
                                                                                            <input title="* TIPO VIVIENDA" tabindex="-1" data-llave="iId" data-campo="sdesTipoDeVivienda" data-tabla="cat_tiposdeviviendas" name="iId_TipoDeVivienda" id="iId_TipoDeVivienda" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <table style="width: 100%" aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-bordered table-hover table-striped dynamic_grid dataTable no-footer">
                                                                                    <thead class="bordered-darkorange">
                                                                                        <tr role="row">
                                                                                            <th aria-label="DIRECCION: activate to sort column ascending" style="width: 185px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting">DIRECCION</th>
                                                                                            <th aria-label="COLONIA: activate to sort column ascending" aria-sort="ascending" style="width: 159px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting_asc">COLONIA</th>
                                                                                            <th aria-label="NUM EXTERIOR: activate to sort column ascending" style="width: 247px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting">NUM EXTERIOR</th><th aria-label="OPCIONES: activate to sort column ascending" style="width: 175px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting">OPCIONES</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr class="odd">
                                                                                            <td class="dataTables_empty" colspan="4" valign="top"></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <div class="modal-footer">
                                                                                    <button data-bb-handler="success" type="button" class="btn guardar-registro btn-success">Guardar</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                        <div id="ImpTelefonos" class="tab-pane" data-rel="cat_telefonosimputadossolicitudes" data-columns="sNombre,sCelular,sEmail,iId">
                                                            <div class="buttons-preview padding-10">
                                                                <div class="bootbox-body">
                                                                    <div class="tabbable">
                                                                        <div class="tab-content">
                                                                            <div class="tab-pane in active" id="REGISTRAR">
                                                                                <div class="form-group has-feedback">
                                                                                    <label class="col-sm-3 control-label no-padding-right" for="sNombre">TEL&EacuteFONO</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input data-bv-field="sNombre" data-bv-regexp-message="El campo telÈfono debe tener esta estructura: ([0-9]{10})$" data-bv-regexp-regexp="([0-9]{10})$" data-bv-regexp="true" maxlength="45" placeholder="TEL&Eacute;FONO" class="form-control" name="sNombre" id="sNombre" type="text">
                                                                                        <i data-bv-icon-for="sNombre" class="form-control-feedback" style="display: none;"></i>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombre" data-bv-validator="regexp" class="help-block" style="display: none;">El campo telÈfono debe tener esta estructura: ([0-9]{10})$</small>
                                                                                        <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombre" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group has-feedback"><label class="col-sm-3 control-label no-padding-right" for="sCelular">CELULAR</label><div class="col-sm-9"><input data-bv-field="sCelular" data-bv-regexp-message="El campo celular debe tener esta estructura: ([0-9]{10})$" data-bv-regexp-regexp="([0-9]{10})$" data-bv-regexp="true" maxlength="45" placeholder="CELULAR" class="form-control" name="sCelular" id="sCelular" type="text"><i data-bv-icon-for="sCelular" class="form-control-feedback" style="display: none;"></i><small data-bv-result="NOT_VALIDATED" data-bv-for="sCelular" data-bv-validator="regexp" class="help-block" style="display: none;">El campo celular debe tener esta estructura: ([0-9]{10})$</small><small data-bv-result="NOT_VALIDATED" data-bv-for="sCelular" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small></div></div><div class="form-group has-feedback"><label class="col-sm-3 control-label no-padding-right" for="sEmail">EMAIL</label><div class="col-sm-9"><input data-bv-field="sEmail" data-bv-regexp-message="El campo email debe tener esta estructura: [_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$" data-bv-regexp-regexp="[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$" data-bv-regexp="true" maxlength="100" placeholder="EMAIL" class="form-control" name="sEmail" id="sEmail" type="text"><i data-bv-icon-for="sEmail" class="form-control-feedback" style="display: none;"></i><small data-bv-result="NOT_VALIDATED" data-bv-for="sEmail" data-bv-validator="regexp" class="help-block" style="display: none;">El campo email debe tener esta estructura: [_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$</small><small data-bv-result="NOT_VALIDATED" data-bv-for="sEmail" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div style="text-align: right">
                                                                <button data-bb-handler="success" type="button" class="btn guardar-registro btn-success">Agregar</button>
                                                            </div>
                                                            <br>
                                                            <table class="table table-bordered table-hover table-striped dynamic_grid">
                                                                <thead class="bordered-darkorange">
                                                                    <tr role="row">
                                                                        <th>TELEFONO</th>
                                                                        <th>CELULAR</th>
                                                                        <th>EMAIL</th>
                                                                        <th>OPCIONES</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                        <div id="ImpDefensores" class="tab-pane" data-rel="cat_defensoresimputadossolicitudes" data-columns="sNombre,sTelefono,sCelular,sEmail,iId">
                                                            <div class="buttons-preview padding-10">
                                                                <div class="modal-body">
                                                                    <div class="bootbox-body">
                                                                        <div class="tabbable">
                                                                            <div class="tab-content">
                                                                                <div class="tab-pane in active" id="REGISTRAR">
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="iId_TipoDefensor">* TIPO DEFENSOR</label>
                                                                                        <div class="col-sm-9">
                                                                                            <select class="form-control col-sm-9">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>
                                                                                            <input title="* TIPO DEFENSOR" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_tiposdefensores" name="iId_TipoDefensor" id="iId_TipoDefensor" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="sNombre">* NOMBRE COMPLETO</label>
                                                                                        <div class="col-sm-9"><input data-bv-field="sNombre" data-bv-regexp-message="El campo * nombre completo debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="500" placeholder="* NOMBRE COMPLETO" class="form-control" name="sNombre" id="sNombre" type="text">
                                                                                            <i data-bv-icon-for="sNombre" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombre" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * nombre completo debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombre" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="sTelefono">TEL&EacuteFONO</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input data-bv-field="sTelefono" data-bv-regexp-message="El campo telÈfono debe tener esta estructura: ([0-9]{10})$" data-bv-regexp-regexp="([0-9]{10})$" data-bv-regexp="true" maxlength="100" placeholder="TEL&Eacute;FONO" class="form-control" name="sTelefono" id="sTelefono" type="text">
                                                                                            <i data-bv-icon-for="sTelefono" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sTelefono" data-bv-validator="regexp" class="help-block" style="display: none;">El campo telÈfono debe tener esta estructura: ([0-9]{10})$</small>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sTelefono" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="sCelular">CELULAR</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input data-bv-field="sCelular" data-bv-regexp-message="El campo celular debe tener esta estructura: ([0-9]{10})$" data-bv-regexp-regexp="([0-9]{10})$" data-bv-regexp="true" maxlength="100" placeholder="CELULAR" class="form-control" name="sCelular" id="sCelular" type="text">
                                                                                            <i data-bv-icon-for="sCelular" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sCelular" data-bv-validator="regexp" class="help-block" style="display: none;">El campo celular debe tener esta estructura: ([0-9]{10})$</small>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sCelular" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback"><label class="col-sm-3 control-label no-padding-right" for="sEmail">EMAIL</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input data-bv-field="sEmail" data-bv-regexp-message="El campo email debe tener esta estructura: [_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$" data-bv-regexp-regexp="[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$" data-bv-regexp="true" maxlength="100" placeholder="EMAIL" class="form-control" name="sEmail" id="sEmail" type="text">
                                                                                            <i data-bv-icon-for="sEmail" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sEmail" data-bv-validator="regexp" class="help-block" style="display: none;">El campo email debe tener esta estructura: [_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$</small>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sEmail" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div style="text-align: right">
                                                                                        <button data-bb-handler="success" type="button" class="btn guardar-registro btn-success">Agregar</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <table class="table table-bordered table-hover table-striped dynamic_grid">
                                                                <thead class="bordered-darkorange">
                                                                    <tr role="row">
                                                                        <th>NOMBRE</th>
                                                                        <th>TELEFONO</th>
                                                                        <th>CELULAR</th>
                                                                        <th>EMAIL</th>
                                                                        <th>OPCIONES</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div> 
                                                        <div id="ImpDrogas" class="tab-pane" data-rel="cat_imputadosdrogas" data-columns="iId_Droga,iId">
                                                            <div class="buttons-preview padding-10">
                                                                <div class="modal-body">
                                                                    <div class="bootbox-body">
                                                                        <div class="tabbable">
                                                                            <div class="tab-content">
                                                                                <div class="tab-pane in active" id="REGISTRAR">
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="iId_Droga">* DROGA</label>
                                                                                        <div class="col-sm-9">
                                                                                            <select class="form-control col-sm-9">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>
                                                                                            <input title="* DROGA" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_drogas" name="iId_Droga" id="iId_Droga" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div style="text-align: right">
                                                                <button data-bb-handler="success" type="button" class="btn guardar-registro btn-success">Guardar</button>
                                                            </div>
                                                            <table class="table table-bordered table-hover table-striped dynamic_grid">
                                                                <thead class="bordered-darkorange">
                                                                    <tr role="row">
                                                                        <th>NOMBRE</th>
                                                                        <th>OPCIONES</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div> 
                                                        <div id="ImpTutores" class="tab-pane" data-rel="cat_tutoresimputados" data-columns="sNombre,sPaterno,sMaterno,iId">
                                                            <div class="buttons-preview padding-10">
                                                                <div class="modal-body">
                                                                    <div class="bootbox-body">
                                                                        <div class="tabbable">
                                                                            <div class="tab-content">
                                                                                <div class="tab-pane in active" id="REGISTRAR">
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="iId_TipoTutor">* Tipo Tutor</label>
                                                                                        <div class="col-sm-9">
                                                                                            <select class="form-control ">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>
                                                                                            <input title="* Tipo Tutor" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_tipostutores" name="iId_TipoTutor" id="iId_TipoTutor" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="iId_Genero">* Genero</label>
                                                                                        <div class="col-sm-9">
                                                                                            <select class="form-control">
                                                                                                <option></option>
                                                                                                <option> PREPARATORIA</option>
                                                                                                <option> CARRERA TECNICA </option>
                                                                                                <option>CARRERA COMERCIAL</option>
                                                                                            </select>
                                                                                            <input title="* G…NERO* Genero" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_generos" name="iId_Genero" id="iId_Genero" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="sNombre">* NOMBRE</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input data-bv-field="sNombre" data-bv-regexp-message="El campo * nombre debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="50" placeholder="* NOMBRE" class="form-control" name="sNombre" id="sNombre" type="text">
                                                                                            <i data-bv-icon-for="sNombre" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombre" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * nombre debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sNombre" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="sPaterno">* PATERNO</label><div class="col-sm-9">
                                                                                            <input data-bv-field="sPaterno" data-bv-regexp-message="El campo * paterno debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="50" placeholder="* PATERNO" class="form-control" name="sPaterno" id="sPaterno" type="text">
                                                                                            <i data-bv-icon-for="sPaterno" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sPaterno" data-bv-validator="regexp" class="help-block" style="display: none;">El campo * paterno debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sPaterno" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="sMaterno">MATERNO</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input data-bv-field="sMaterno" data-bv-regexp-message="El campo materno debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp-regexp="^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$" data-bv-regexp="true" maxlength="50" placeholder="MATERNO" class="form-control" name="sMaterno" id="sMaterno" type="text">
                                                                                            <i data-bv-icon-for="sMaterno" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sMaterno" data-bv-validator="regexp" class="help-block" style="display: none;">El campo materno debe tener esta estructura: ^([a-zA-Z·ÈÌÛ˙Ò¡…Õ”⁄— ]{3,})*$</small>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="sMaterno" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="dFechaNacimientoTutor">* FECHA NACIMIENTO</label>
                                                                                        <div class="col-sm-8">
                                                                                            <input maxlength="" placeholder="* FECHA NACIMIENTO" class="form-control" name="dFechaNacimientoTutor" id="dFechaNacimientoTutor" type="text">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group has-feedback">
                                                                                        <label class="col-sm-3 control-label no-padding-right" for="iEdadTutor">* EDAD</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input data-bv-field="iEdadTutor" data-bv-regexp-message="Solo se permiten n˙meros" data-bv-regexp-regexp="[0-9]+$" data-bv-regexp="true" maxlength="2" placeholder="* EDAD" class="form-control" name="iEdadTutor" id="iEdadTutor" type="text">
                                                                                            <i data-bv-icon-for="iEdadTutor" class="form-control-feedback" style="display: none;"></i>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="iEdadTutor" data-bv-validator="regexp" class="help-block" style="display: none;">Solo se permiten n˙meros</small>
                                                                                            <small data-bv-result="NOT_VALIDATED" data-bv-for="iEdadTutor" data-bv-validator="stringLength" class="help-block" style="display: none;">Please enter a value with valid length</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div style="text-align: right">
                                                                <button data-bb-handler="success" type="button" class="btn guardar-registro btn-success">Agregar</button>
                                                            </div>
                                                            <table class="table table-bordered table-hover table-striped dynamic_grid">
                                                                <thead class="bordered-darkorange">
                                                                    <tr role="row">
                                                                        <th>NOMBRE</th>
                                                                        <th>PATERNO</th>
                                                                        <th>MATERNO</th>
                                                                        <th>OPCIONES</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>                                   
                                                        <div id="ImpNacionalidades" class="tab-pane" data-rel="cat_nacionalidadesimputadossolicitudes" data-columns="iId_Pais,iId">
                                                            <div class="buttons-preview padding-10">
                                                                <div class="modal-body">
                                                                    <div class="bootbox-body">
                                                                        <div class="tabbable">
                                                                            <ul id="myTab" class="nav nav-tabs">
                                                                                <li class="active">
                                                                                    <a data-toggle="tab" href="#REGISTRAR">REGISTRAR</a>
                                                                                </li>
                                                                            </ul>
                                                                            <form data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-message="This value is not valid" novalidate="novalidate" role="form" class="form-horizontal bv-form" id="cat_nacionalidadesimputadossolicitudes">
                                                                                <button style="display: none; width: 0px; height: 0px;" class="bv-hidden-submit" type="submit"></button>
                                                                                <div class="tab-content">
                                                                                    <div class="tab-pane in active" id="REGISTRAR">
                                                                                        <div class="form-group">
                                                                                            <label class="col-sm-3 control-label no-padding-right" for="iId_Pais">* NACIONALIDAD</label>
                                                                                            <div class="col-sm-9">
                                                                                                <select class="form-control">
                                                                                                    <option></option>
                                                                                                    <option> PREPARATORIA</option>
                                                                                                    <option> CARRERA TECNICA </option>
                                                                                                    <option>CARRERA COMERCIAL</option>
                                                                                                </select>
                                                                                                <input title="* DROGA" tabindex="-1" data-llave="iId" data-campo="sNombre" data-tabla="cat_drogas" name="iId_Droga" id="iId_Droga" class="select2" style="width: 100%; display: none;" type="hidden">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <table class="table table-bordered table-hover table-striped dynamic_grid">
                                                                <thead class="bordered-darkorange">
                                                                    <tr role="row">
                                                                        <th>NOMBRE</th>
                                                                        <th>OPCIONES</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                            <div class="modal-footer">
                                                                <button data-bb-handler="success" type="button" class="btn guardar-registro btn-success">Guardar</button>
                                                                <button data-bb-handler="Limpiar" type="button" class="btn btn-blue">Limpiar</button>
                                                                <button data-bb-handler="Cerrar" type="button" class="btn btn-danger">Cerrar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="LISTAIMPUTADOS" class="tab-pane" data-rel="cat_imputadossolicitudes" data-columns="sNombre,sPaterno,sMaterno,sNombreMoral,iId">

                                <div class="dataTables_wrapper form-inline no-footer" id="DataTables_Table_1_wrapper" role="grid">
                                    <table style="width: 1017px;" aria-describedby="DataTables_Table_1_info" id="DataTables_Table_1" class="table table-bordered table-hover table-striped dynamic_grid dataTable no-footer">
                                        <thead class="bordered-darkorange">
                                            <tr role="row"><th aria-label="NOMBRE: activate to sort column ascending" style="width: 141px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_1" tabindex="0" class="sorting">NOMBRE</th>
                                                <th aria-label="PATERNO: activate to sort column ascending" aria-sort="ascending" style="width: 150px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_1" tabindex="0" class="sorting_asc">PATERNO</th>
                                                <th aria-label="MATERNO: activate to sort column ascending" style="width: 159px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_1" tabindex="0" class="sorting">MATERNO</th>
                                                <th aria-label="NOMBRE MORAL: activate to sort column ascending" style="width: 319px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_1" tabindex="0" class="sorting">NOMBRE MORAL</th>
                                                <th aria-label="OPCIONES: activate to sort column ascending" style="width: 161px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_1" tabindex="0" class="sorting">OPCIONES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td class=" "> </td>
                                                ¥<td class="sorting_1"></td>
                                                <td class=" "></td>
                                                <td class=" "></td>
                                                <td class=" ">
                                                    <a class="btn btn-default btn-xs shiny icon-only success abre_modal" href="javascript:void(0);" data-rel="editar" data-id="7" alt="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a class="btn btn-default btn-xs shiny icon-only danger eliminar_registro" href="javascript:void(0);" data-rel="eliminar_registro" data-id="7" data-info="" alt="Eliminar">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="odd">
                                                <td class=" "> </td>
                                                ¥<td class="sorting_1"></td>
                                                <td class=" "></td>
                                                <td class=" "></td>
                                                <td class=" ">
                                                    <a class="btn btn-default btn-xs shiny icon-only success abre_modal" href="javascript:void(0);" data-rel="editar" data-id="7" alt="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a class="btn btn-default btn-xs shiny icon-only danger eliminar_registro" href="javascript:void(0);" data-rel="eliminar_registro" data-id="7" data-info="" alt="Eliminar">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="odd">
                                                <td class=" "> </td>
                                                ¥<td class="sorting_1"></td>
                                                <td class=" "></td>
                                                <td class=" "></td>
                                                <td class=" ">
                                                    <a class="btn btn-default btn-xs shiny icon-only success abre_modal" href="javascript:void(0);" data-rel="editar" data-id="7" alt="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a class="btn btn-default btn-xs shiny icon-only danger eliminar_registro" href="javascript:void(0);" data-rel="eliminar_registro" data-id="7" data-info="" alt="Eliminar">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="odd">
                                                <td class=" "> </td>
                                                ¥<td class="sorting_1"></td>
                                                <td class=" "></td>
                                                <td class=" "></td>
                                                <td class=" ">
                                                    <a class="btn btn-default btn-xs shiny icon-only success abre_modal" href="javascript:void(0);" data-rel="editar" data-id="7" alt="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a class="btn btn-default btn-xs shiny icon-only danger eliminar_registro" href="javascript:void(0);" data-rel="eliminar_registro" data-id="7" data-info="" alt="Eliminar">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="odd">
                                                <td class=" "> </td>
                                                ¥<td class="sorting_1"></td>
                                                <td class=" "></td>
                                                <td class=" "></td>
                                                <td class=" ">
                                                    <a class="btn btn-default btn-xs shiny icon-only success abre_modal" href="javascript:void(0);" data-rel="editar" data-id="7" alt="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>&nbsp;
                                                    <a class="btn btn-default btn-xs shiny icon-only danger eliminar_registro" href="javascript:void(0);" data-rel="eliminar_registro" data-id="7" data-info="" alt="Eliminar">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>&nbsp;
                                                </td>
                                            </tr>
                                            <tr class="odd">
                                                <td class=" "> </td>
                                                ¥<td class="sorting_1"></td>
                                                <td class=" "></td>
                                                <td class=" "></td>
                                                <td class=" ">
                                                    <a class="btn btn-default btn-xs shiny icon-only success abre_modal" href="javascript:void(0);" data-rel="editar" data-id="7" alt="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>&nbsp;
                                                    <a class="btn btn-default btn-xs shiny icon-only danger eliminar_registro" href="javascript:void(0);" data-rel="eliminar_registro" data-id="7" data-info="" alt="Eliminar">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>&nbsp;
                                                </td>
                                            </tr>
                                            <tr class="odd">
                                                <td class=" "> </td>
                                                ¥<td class="sorting_1"></td>
                                                <td class=" "></td>
                                                <td class=" "></td>
                                                <td class=" ">
                                                    <a class="btn btn-default btn-xs shiny icon-only success abre_modal" href="javascript:void(0);" data-rel="editar" data-id="7" alt="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>&nbsp;
                                                    <a class="btn btn-default btn-xs shiny icon-only danger eliminar_registro" href="javascript:void(0);" data-rel="eliminar_registro" data-id="7" data-info="" alt="Eliminar">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>&nbsp;
                                                </td>
                                            </tr>
                                            <tr class="odd">
                                                <td class=" "> </td>
                                                ¥<td class="sorting_1"></td>
                                                <td class=" "></td>
                                                <td class=" "></td>
                                                <td class=" ">
                                                    <a class="btn btn-default btn-xs shiny icon-only success abre_modal" href="javascript:void(0);" data-rel="editar" data-id="7" alt="Editar">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>&nbsp;
                                                    <a class="btn btn-default btn-xs shiny icon-only danger eliminar_registro" href="javascript:void(0);" data-rel="eliminar_registro" data-id="7" data-info="" alt="Eliminar">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>&nbsp;
                                                </td>
                                            </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <!--FIN-->

    </body>
</html>
