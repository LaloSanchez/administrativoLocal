<?php
include_once(dirname(__FILE__) . "/../../aplicacion/configuracion.php");
include_once(dirname(__FILE__) . "/../../tribunal/connect/Proveedor.Class.php");

if (isset($_GET["frm"])) {
    $start = strrpos(strtoupper($_GET["frm"]), "FRM");
    $end = strrpos(strtoupper($_GET["frm"]), "VIEW");
    $tabla = DEFECTO_PREFIJO . strtolower(substr($_GET["frm"], $start + strlen("frm"), (($end - strlen(".ph")) - $start)));
} else {
    $tabla = "";
}

$database = DEFECTO_NAME_BD;

$proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
$proveedor->connect();
$campos = array();
$camposNombreTabla = array();
$contador = 0;
$contadorNombreTable = 0;
$nombreCatalogo = "";
$sql = "select
c.COLUMN_NAME,c.ORDINAL_POSITION,c.IS_NULLABLE,c.DATA_TYPE,c.CHARACTER_MAXIMUM_LENGTH,c.NUMERIC_PRECISION,'',c.COLUMN_TYPE,c.COLUMN_KEY,c.COLUMN_COMMENT,ca.REFERENCED_TABLE_NAME,ca.REFERENCED_COLUMN_NAME
from
information_schema.COLUMNS c LEFT JOIN information_schema.key_column_usage ca on (ca.COLUMN_NAME=c.COLUMN_NAME And ca.table_schema = '" . $database . "' And ca.referenced_table_name is not null And referenced_table_name<>'" . $tabla . "')
where 
c.table_schema = '" . $database . "'
and c.table_name = '" . $tabla . "'  Group By c.COLUMN_NAME order by c.ORDINAL_POSITION";

$sqlNombreTabla = "SELECT
table_comment 
FROM INFORMATION_SCHEMA.TABLES 
    WHERE table_schema='" . $database . "' 
        AND table_name='" . $tabla . "'";

$proveedor->execute($sql);
if (!$proveedor->error()) {
    if ($proveedor->rows($proveedor->stmt) > 0) {
        while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {

            $campos[$contador] = array("field" => $row[0], "position" => $row[1], "nullable" => $row[2], "data_type" => $row[3], "character_max" => $row[4], "numeric_max" => $row[5], "date_max" => $row[6], "column_type" => $row[7], "column_key" => $row[8], "column_comment" => $row[9], "referenced_table_name" => $row[10], "referenced_column_name" => $row[11]);
            $contador++;
        }
    } else {
        $error = true;
    }
} else {
    $error = true;
}
$proveedor->execute($sqlNombreTabla);
if (!$proveedor->error()) {
    if ($proveedor->rows($proveedor->stmt) > 0) {
        while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
        $nombreCatalogo = utf8_encode(($row[0]));
        }
    } else {
        $error = true;
    }
} else {
    $error = true;
}

//var_dump($campos);
if ((sizeof($campos) > 0) && ($tabla != "") && ($database != "")) {
    ?>
    <div class="panel panel-default">
        <div class="headerForm">
            <h1>                                                            
                Catalogo
            </h1>
            <h2>                                                            
                <?php echo ($nombreCatalogo) ?>
            </h2>
            <hr>

        </div>
        <div class="panel-body " style=" margin-top: -15px;">
            <div class="formNew">
                <div class="col-md-12">
                    <div id="divFormGenericoConsulta" class="form-horizontal" data-intro="" data-position='top' style="display: block;">
                        <div class="buttons-preview padding-10">
                            <a href="javascript:cambiaDiv(1)" class="btn btn-primary btn-labeled btn-palegreen abre_horario">
                                <i class="btn-label fa fa-plus"></i>Agregar Nuevo
                            </a>
                        </div>
                        <div class="resultTable">
                            <div class="col-md-12">
                                <table class="table table-hover table-striped table-bordered" id="tablaGeneric" style="width: 100%;" >
                                    <thead class="bordered-darkorange">
                                        <tr role="row">
                                            <?php
                                            for ($index = 0; $index < sizeof($campos); $index++) {
                                                $param = explode("|", $campos[$index]["column_comment"]);
//                        if ($campos[$index]["column_key"] != "PRI") {
                                                ?>

                                                <th><?php
                                                    if ($campos[$index]["column_comment"] == "") {
                                                        echo $campos[$index]["field"];
                                                    } else {
                                                        echo utf8_encode($param[0]);
                                                    }
                                                    ?></th>
                                                <?php
//                        }
                                            }
                                            ?>
            <!--<th></th>-->
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $sql = "Select * FROM " . $tabla . " ";
                                        $proveedor->execute($sql);
                                        if (!$proveedor->error()) {
                                            if ($proveedor->rows($proveedor->stmt) > 0) {
                                                while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
                                                    echo "<tr>";
                                                    for ($index = 0; $index < sizeof($campos); $index++) {
//                                if ($campos[$index]["column_key"] != "PRI") {
                                                        echo "<td>" . utf8_encode($row[$index]) . "</td>";
//                                }
                                                    }
//                            echo "<td><button class=\"btn btn-primary btn-labeled btn-success abre_modal\" data-rel=\"editar\" data-id=\"\"><span class=\"btn-label icon fa fa-pencil\"></span>Editar</button>&nbsp;<button class=\"btn btn-primary btn-labeled btn-danger eliminar_registro\" data-rel=\"eliminar_registro\" data-id=\"\" data-info=\"\"><span class=\"btn-label icon fa fa-trash-o\"></span>Eliminar</button></td>";

                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "Upss algo salio mal y no encontro registros";
                                            }
                                        } else {
                                            echo "Upss algo salio mal " . $proveedor->error();
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="divFormGenericoRegistro" class="form-horizontal" data-intro="" data-position='top' style="display: none;">
                        <div id="divCamposGenericoRegistro">

                            <?php
                            echo "<div class='col-md-12'><form id=\"attributeForm\" method=\"post\" class=\"form-horizontal bv-form\" role=\"form\" novalidate=\"novalidate\"
    data-bv-message=\"This value is not valid\"
    data-bv-feedbackicons-valid=\"glyphicon glyphicon-ok\"
    data-bv-feedbackicons-invalid=\"glyphicon glyphicon-remove\"
    data-bv-feedbackicons-validating=\"glyphicon glyphicon-refresh\">";
                            $editText = array();
                            for ($index = 0; $index < sizeof($campos); $index++) {
                                $param = explode("|", $campos[$index]["column_comment"]);
                                $display = "";
                                if (isset($param[1])) {
                                    if ((string) $param[1] == "N")
                                        $display = "style='display:none'";
                                }
                                if (($index+1) % 2 == 0) {
                                    
                                    $campo ="<div class='row'>";
                                }else{
                                    $campo="";
                                }
                                $campo .= "<div class='col-md-6' " . $display . "> <div id='" . $campos[$index]["field"] . "' class=\"form-group\"";
                                if (isset($param[1])) {
                                    if ((string) $param[1] == "N")
                                        $campo.=" style='display:none'";
                                }
                                $campo.=">";

                                if ((strtoupper($campos[$index]["data_type"]) == "CHAR") && ((int) $campos[$index]["character_max"] == 1)) {//numeric_max
                                    $campo.="<label class=\" col-md-12 titleCombo" . (($campos[$index]["nullable"] == "NO") ? "needed" : "") . " \">";
                                    if ($campos[$index]["column_comment"] == "") {
                                        $campo.= $campos[$index]["field"];
                                    } else {
                                        if ((!is_array($param)) && (sizeof($param <= 0))) {
                                            $campo.= utf8_encode($campos[$index]["column_comment"]);
                                        } else {
                                            $campo.= utf8_encode($param[0]);
                                        }
                                    }
                                    $campo.="</label>";
                                }
                                $campo.= "<div class=\"col-md-12\">";
                                if ($campos[$index]["referenced_table_name"] == "") {

                                    if ((strtoupper($campos[$index]["data_type"]) == "CHAR") && ((int) $campos[$index]["character_max"] == 1)) {//numeric_max
                                        $campo.="<select class='form-control mdb-select ui search dropdown' name=\"cmb" . ucwords($campos[$index]["field"]) . "\" id=\"cmb" . ucwords($campos[$index]["field"]) . "\">";
                                        $campo.="<option value=''>Seleccione una opcion</option>";
                                        $campo.="<option value='S'>SI</option>";
                                        $campo.="<option value='N'>NO</option>";
                                        $campo.="</select>";
                                    } else if ((strtoupper($campos[$index]["data_type"]) == "VARCHAR") || ((strtoupper($campos[$index]["data_type"]) == "CHAR") && ((int) $campos[$index]["character_max"] > 1))) {
                                        $campo.="<div class='md-form'><input type='text'   class='form-control text-uppercase' ";

                                        if ($campos[$index]["nullable"] == "NO") {

                                            $campo.=" required ";
                                            $campo.=" data-bv-notempty=\"true\" ";
                                            $campo.=utf8_encode(" data-bv-notempty-message=\"Este campo es necesario y no puede ir vac�o\" ");
                                        }

                                        if (isset($param[3])) {
                                            if ($param[3] != "") {
                                                $campo.="pattern=\"" . $param[3] . "\"";
                                                $campo.="data-bv-regexp-message=\"El texto ingresado no cumple " . $param[3] . "\" ";
                                                $campo.="data-fv-regexp=\"true\"";
                                                $campo.="data-fv-regexp-regexp=\"" . $param[3] . "\"";
                                                $campo.="data-fv-regexp-message=\"El texto ingresado no cumple " . $param[3] . "\" ";
                                            }
                                        }

                                        $campo.=" name='txt" . ucwords($campos[$index]["field"]) . "' id='txt" . ucwords($campos[$index]["field"]) . "' value=\"\"/>";
                                        $campo.="<label for='txt" . ucwords($campos[$index]["field"]) . "' class=\" col-md-12 " . (($campos[$index]["nullable"] == "NO") ? "needed" : "") . " \">";
                                        if ($campos[$index]["column_comment"] == "") {
                                            $campo.= $campos[$index]["field"];
                                        } else {
                                            if ((!is_array($param)) && (sizeof($param <= 0))) {
                                                $campo.= utf8_encode($campos[$index]["column_comment"]);
                                            } else {
                                                $campo.= utf8_encode($param[0]);
                                            }
                                        }
                                        $campo.="</label>";
                                        $campo .= "</div>";
                                    } else if ((strtoupper($campos[$index]["data_type"]) == "TEXT") || (strtoupper($campos[$index]["data_type"]) == "LONGTEXT") || (strtoupper($campos[$index]["data_type"]) == "MEDIUMTEXT") || (strtoupper($campos[$index]["data_type"]) == "TINYTEXT")) {
//                        $campo.="<input type='text'  class='form-control text-uppercase' ";
//                        $campo.=" name='txt" . ucwords($campos[$index]["field"]) . "' id='txt" . ucwords($campos[$index]["field"]) . "' value=\"\"/>";
                                        $campo.="<script id = 'txt" . ucwords($campos[$index]["field"]) . "' type = \"text/plain\" style = \"width: 98%; height: 300px; margin: 0px auto;\"></script>";
                                        //$campo.="<!--<textarea rows=\"6\" id='txt" . ucwords($campos[$index]["field"]) . "' class=\"form-control\" placeholder=\"Observaciones\">8</textarea>-->";
                                        $editText[] = "'txt" . ucwords($campos[$index]["field"]) . "'";
                                    } elseif ((strtoupper($campos[$index]["data_type"]) == "INT") || (strtoupper($campos[$index]["data_type"]) == "MEDIUMINT") || (strtoupper($campos[$index]["data_type"]) == "FLOOAT") || (strtoupper($campos[$index]["data_type"]) == "DOUBLE")) {
                                        $campo.="<div class='md-form'><input type='text'  class='form-control text-uppercase' ";

                                        if ($campos[$index]["nullable"] == "NO") {
                                            $campo.=" required ";
                                            $campo.=" data-bv-notempty=\"true\" ";
                                            $campo.=utf8_encode(" data-bv-notempty-message=\"Este campo es necesario y no puede ir vac�o\" ");
                                        }

                                        if (isset($param[3])) {
                                            if ($param[3] != "") {
                                                $campo.="pattern=\"" . $param[3] . "\"";
                                                $campo.="data-bv-regexp-message=\"El texto ingresado no cumple " . $param[3] . "\" ";
                                                $campo.="data-fv-regexp=\"true\"";
                                                $campo.="data-fv-regexp-regexp=\"" . $param[3] . "\"";
                                                $campo.="data-fv-regexp-message=\"El texto ingresado no cumple " . $param[3] . "\" ";
                                            }
                                        } else {
                                            $campo.="pattern=\"^[0-9]+$\"";
                                            $campo.=utf8_encode("data-bv-regexp-message=\"Solo se permiten n�meros\" ");
                                            $campo.="data-fv-regexp=\"true\"";
                                            $campo.="data-fv-regexp-regexp=\"^[0-9]+$\"";
                                            $campo.=utf8_encode("data-fv-regexp-message=\"Solo se permiten n�meros\" ");
                                        }

                                        $campo.=" name='txt" . ucwords($campos[$index]["field"]) . "' id='txt" . ucwords($campos[$index]["field"]) . "' value=\"\"/>";
                                        $campo.="<label for='txt" . ucwords($campos[$index]["field"]) . "' class=\" col-md-12 " . (($campos[$index]["nullable"] == "NO") ? "needed" : "") . " \">";
                                        if ($campos[$index]["column_comment"] == "") {
                                            $campo.= $campos[$index]["field"];
                                        } else {
                                            if ((!is_array($param)) && (sizeof($param <= 0))) {
                                                $campo.= utf8_encode($campos[$index]["column_comment"]);
                                            } else {
                                                $campo.= utf8_encode($param[0]);
                                            }
                                        }
                                        $campo.="</label>";
                                        $campo .= "</div>";
                                    } else if ((strtoupper($campos[$index]["data_type"]) == "DATE") || (strtoupper($campos[$index]["data_type"]) == "DATETIME")) {
                                        $campo.="<div class='md-form'><input type='text' class='form-control text-uppercase' ";

                                        if ($campos[$index]["nullable"] == "NO") {

                                            $campo.=" required ";
                                            $campo.=" data-bv-notempty=\"true\" ";
                                            $campo.=utf8_encode(" data-bv-notempty-message=\"Este campo es necesario y no puede ir vac�o\" ");
                                        }

                                        if (isset($param[3])) {
                                            if ($param[3] != "") {
                                                $campo.="pattern=\"" . $param[3] . "\"";
                                                $campo.="data-bv-regexp-message=\"El texto ingresado no cumple " . $param[3] . "\" ";
                                                $campo.="data-fv-regexp=\"true\"";
                                                $campo.="data-fv-regexp-regexp=\"" . $param[3] . "\"";
                                                $campo.="data-fv-regexp-message=\"El texto ingresado no cumple " . $param[3] . "\" ";
                                            }
                                        }

                                        $campo.=" name='txt" . ucwords($campos[$index]["field"]) . "' id='txt" . ucwords($campos[$index]["field"]) . "' value=\"\"/>";
                                        $campo.="<label for='txt" . ucwords($campos[$index]["field"]) . "' class=\" col-md-12 " . (($campos[$index]["nullable"] == "NO") ? "needed" : "") . " \">";
                                        if ($campos[$index]["column_comment"] == "") {
                                            $campo.= $campos[$index]["field"];
                                        } else {
                                            if ((!is_array($param)) && (sizeof($param <= 0))) {
                                                $campo.= utf8_encode($campos[$index]["column_comment"]);
                                            } else {
                                                $campo.= utf8_encode($param[0]);
                                            }
                                        }
                                        $campo.="</label>";

                                        $campo .= "</div>";

                                        $campo.="<script>";
                                        if (strtoupper($campos[$index]["data_type"]) == "DATE") {

                                            $campo.="$(\"#txt" . ucwords($campos[$index]["field"]) . "\").datetimepicker({";
                                            $campo.="sideBySide: false,";
                                            $campo.="locale: 'es',";
                                            $campo.="format: \"DD/MM/YYYY\",";
                                            $campo.="});";
                                        } elseif (strtoupper($campos[$index]["data_type"]) == "DATETIME") {

                                            $campo.="$(\"#txt" . ucwords($campos[$index]["field"]) . "\").datetimepicker({";
                                            $campo.="sideBySide: false,";
                                            $campo.="locale: 'es',";
                                            $campo.="format: \"DD/MM/YYYY HH:mm:ss\",";
                                            $campo.="});";
                                        }
                                        $campo.="</script>";
                                    }
                                } else {
                                    $campo.="<label class=\" col-md-12 titleCombo " . (($campos[$index]["nullable"] == "NO") ? "needed" : "") . " \">";
                                    if ($campos[$index]["column_comment"] == "") {
                                        $campo.= $campos[$index]["field"];
                                    } else {
                                        if ((!is_array($param)) && (sizeof($param <= 0))) {
                                            $campo.= utf8_encode($campos[$index]["column_comment"]);
                                        } else {
                                            $campo.= utf8_encode($param[0]);
                                        }
                                    }
                                    $campo.="</label>";
                                    $campo.="<select class='form-control mdb-select ui search dropdown' name=\"cmb" . ucwords($campos[$index]["field"]) . "\" id=\"cmb" . ucwords($campos[$index]["field"]) . "\">";
                                    $concat = " * ";
                                    if (isset($param[2])) {
                                        $concat = "concat_ws(' ',";
                                        $camposShow = explode(",", $param[2]);
                                        for ($x = 0; $x < sizeof($camposShow); $x++) {
                                            $concat.=$camposShow[$x] . ",";
                                        }
                                        $concat = $campos[$index]["referenced_column_name"] . "," . substr($concat, 0, -1) . ") as campo";
                                    }

                                    $camposaUx = array();
                                    $contador = 0;
                                    $sql = "select
c.COLUMN_NAME,c.ORDINAL_POSITION,c.IS_NULLABLE,c.DATA_TYPE,c.CHARACTER_MAXIMUM_LENGTH,c.NUMERIC_PRECISION,c.DATETIME_PRECISION,c.COLUMN_TYPE,c.COLUMN_KEY,c.COLUMN_COMMENT,ca.REFERENCED_TABLE_NAME,ca.REFERENCED_COLUMN_NAME
from
information_schema.COLUMNS c LEFT JOIN information_schema.key_column_usage ca on (ca.COLUMN_NAME=c.COLUMN_NAME And ca.table_schema = '" . $database . "' And ca.referenced_table_name is not null And referenced_table_name<>'" . $tabla . "')
where 
c.table_schema = '" . $database . "'
and c.table_name = '" . $campos[$index]["referenced_table_name"] . "'  Group By c.COLUMN_NAME  order by c.ORDINAL_POSITION";
                                    $proveedor->execute($sql);
                                    if (!$proveedor->error()) {
                                        if ($proveedor->rows($proveedor->stmt) > 0) {
                                            while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {

                                                $camposaUx[$contador] = array("field" => $row[0], "position" => $row[1], "nullable" => $row[2], "data_type" => $row[3], "character_max" => $row[4], "numeric_max" => $row[5], "date_max" => $row[6], "column_type" => $row[7], "column_key" => $row[8], "column_comment" => $row[9], "referenced_table_name" => $row[10], "referenced_column_name" => $row[11]);
                                                $contador++;
                                            }
                                        } else {
                                            $error = true;
                                        }
                                    } else {
                                        $error = true;
                                    }

                                    $sql = "Select " . $concat . " FROM " . $campos[$index]["referenced_table_name"] . " ";

                                    for ($x = 0; $x < sizeof($camposaUx); $x++) {
                                        if (strtoupper($camposaUx[$x]["field"]) == "ACTIVO") {
                                            $sql.=" Where Activo='S' ";
                                        }
                                    }

                                    $proveedor->execute($sql);
                                    if (!$proveedor->error()) {
                                        if ($proveedor->rows($proveedor->stmt) > 0) {
                                            while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
                                                $campo.="<option value='" . $row[0] . "'>" . utf8_encode($row[1]) . "</option>";
                                                $contador++;
                                            }
                                        } else {
                                            echo "Upss algo salio mal y no encontro registros";
                                        }
                                    } else {
                                        echo "Upss algo salio mal " . $proveedor->error();
                                    }
                                    $campo.="</select>";
                                }
                                $campo .= "</div>";
                                if ($index % 2 == 0) {
                                    $campo .="</div>";
                                }
                                echo $campo .= "</div></div>";
                            }
                            echo "<script>\n$('#attributeForm').bootstrapValidator({\nexcluded: [':disabled']\n}).on('submit', function (event) {\nevent.stopPropagation();\nevent.preventDefault();\nreturn false;\n}); \n</script>";

                            echo "</form></div>";
                            ?>
                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-9">
                                    <div class="col-md-2 botonesAdaptar" >
                                        <input type="submit" class="btn btn-primary btn-adaptar" id="inputGuardar" value="Guardar" onclick="guardar()" >                                    
                                    </div>
                                    <div class="col-md-2 botonesAdaptar" >
                                        <input type="submit" class="btn btn-primary btn-adaptar" id="inputConsultar" value="Consultar" onclick="cambiaDiv(2)" >                                    
                                    </div>
                                    <div class="col-md-2 botonesAdaptar" >
                                        <input type="submit" class="btn btn-primary btn-adaptar" id="inputEliminar" value="Eliminar" onclick="eliminar()" >
                                    </div>
                                    <div class="col-md-2 botonesAdaptar" >
                                        <input type="submit" class="btn btn-primary btn-adaptar" id="inputLimpiar" value="Limpiar" onclick="limpiar()" >
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
                try {
    <?php
    if (sizeof($editText) > 0) {

        for ($index = 0; $index < sizeof($editText); $index++) {
            echo "if (editor" . $index . " !=undefined) {\n";
            echo "editor" . $index . ".destroy();\n";
            echo "var editor" . $index . " = null;\n";
            echo "}else{\n";
            echo "//alert(\"nada\");\n";
            echo "var editor" . $index . " = null;\n";
            echo "}\n";
        }
    }
    ?>

                isDate = function(text){
                if (text != null){
                var reg = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[0-1])$/;
                        var reg2 = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[0-1])\ (0[0-9]|1[0-9]|2[0-4])\:(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])\:(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])$/;
                        if (text.match(reg)){
                text = text.split('-');
                        return  text[2] + "/" + text[1] + "/" + text[0];
                } else if (text.match(reg2)){
                text = text.split('-');
                        var text2 = text[2].split(' ');
                        return  text2[0] + "/" + text[1] + "/" + text[0] + " " + text2[1];
                }
                return text;
                }
                return "";
                }

                cambiaDiv = function (opcion) {
                if (opcion == 1) {
                $("#divFormGenericoRegistro").show('slow');
                        $("#divFormGenericoConsulta").hide('slow');
                } else if (opcion == 2) {
                $("#divFormGenericoRegistro").hide('slow');
                        $("#divFormGenericoConsulta").show('slow');
                }
                }
                validar = function () {
                var error = false;
                        var msg = "Revice:<br>";
    <?php
    for ($index = 0; $index < sizeof($campos); $index++) {
        if ($campos[$index]["column_key"] != "PRI") {
            $campo = "";
            $param = explode("|", $campos[$index]["column_comment"]);
            $tipoCampo = "txt";
            if ($campos[$index]["referenced_table_name"] == "") {

                if ((strtoupper($campos[$index]["data_type"]) == "CHAR") && ((int) $campos[$index]["character_max"] == 1)) {
                    $tipoCampo = "cmb";
                } else {
                    $tipoCampo = "txt";
                }
            } else {
                $tipoCampo = "cmb";
            }

            if ($campos[$index]["nullable"] == "NO") {
                $campo.="if( ($(\"#" . $tipoCampo . ucwords($campos[$index]["field"]) . "\").val()==\"\") ";
                if (isset($param[3])) {
                    if ($param[3] != "")
                        $campo.=" || (!$(\"#" . $tipoCampo . ucwords($campos[$index]["field"]) . "\").val().match(/$param[3]/)) ";
                }elseif (strtoupper($campos[$index]["data_type"]) == "INT") {
                    $campo.=" || (!$(\"#" . $tipoCampo . ucwords($campos[$index]["field"]) . "\").val().match(/^[0-9]+$/)) ";
                }
                $campo.=" ){\n";
                if (isset($param[0])) {
                    if ($param[0] != "") {
                        $campo.=utf8_encode("msg =msg+\"El campo <b>" . $param[0] . "</b> no cumple las caracteristicas necesarias<br>\";\n");
                    } else {
                        $campo.=utf8_encode("msg =msg+\"El campo <b>" . $campos[$index]["field"] . "</b> no cumple las caracteristicas necesarias<br>\";\n");
                    }
                } else {
                    $campo.=utf8_encode("msg =msg+\"El campo <b>" . $campos[$index]["field"] . "</b> no cumple las caracteristicas necesarias<br>\";\n");
                }
                $campo.="error = true;\n";
                $campo.=" }\n";
            } else {
                if ((isset($param[3])) && (@$param[3] != "")) {

                    $campo.="if(";
                    $campo.="$(\"#" . $tipoCampo . ucwords($campos[$index]["field"]) . "\").val().match(/$param[3]/)==false";
                    $campo.=" ){\n";
                    if (isset($param[0])) {
                        if ($param[0] != "") {
                            $campo.=utf8_encode("msg =msg+\"El campo <b>" . $param[0] . "</b> no cumple las caracteristicas necesarias<br>\";\n");
                        } else {
                            $campo.=utf8_encode("msg =msg+\"El campo <b>" . $campos[$index]["field"] . "</b> no cumple las caracteristicas necesarias<br>\";\n");
                        }
                    } else {
                        $campo.=utf8_encode("msg =msg+\"El campo <b>" . $campos[$index]["field"] . "</b> no cumple las caracteristicas necesarias<br>\";\n");
                    }
                    $campo.="error = true;\n";
                    $campo.=" }\n";
                } elseif (strtoupper($campos[$index]["data_type"]) == "INT") {

                    $campo.="var reg" . ucwords($campos[$index]["field"]) . " = /^[0-9]+$/;\n";
                    $campo.="if(";
                    $campo.="!$(\"#" . $tipoCampo . ucwords($campos[$index]["field"]) . "\").val().match(reg" . ucwords($campos[$index]["field"]) . ")"; //
                    $campo.=" ){\n";
                    if (isset($param[0])) {
                        if ($param[0] != "") {
                            $campo.=utf8_encode("msg =msg+\"El campo <b>" . $param[0] . "</b> no cumple las caracteristicas necesarias<br>\";\n");
                        } else {
                            $campo.=utf8_encode("msg =msg+\"El campo <b>" . $campos[$index]["field"] . "</b> no cumple las caracteristicas necesarias<br>\";\n");
                        }
                    } else {
                        $campo.=utf8_encode("msg =msg+\"El campo <b>" . $campos[$index]["field"] . "</b> no cumple las caracteristicas necesarias<br>\";\n");
                    }
                    $campo.="error = true;\n";
                    $campo.=" }\n";
                }
            }

            echo $campo;
        }
    }
    ?>
                if (error){
                bootbox.dialog({
                title: "Error",
                        message: '<img src="img/error.jpg" width="100px"/><br/> ' + msg + ' <b>html</b>'
                });
                }



                return !error;
    //        var bootstrapValidatorx = $('#attributeForm').data('bootstrapValidator');
    //        bootstrapValidatorx.;
                }

                limpiar = function () {
                $(".md-form label").removeClass("active");

    <?php
    $text = 0;
    for ($index = 0; $index < sizeof($campos); $index++) {
        if ($campos[$index]["referenced_table_name"] == "") {

            if ((strtoupper($campos[$index]["data_type"]) == "CHAR") && ((int) $campos[$index]["character_max"] == 1)) {
                $campo = "$(\"#cmb" . ucwords($campos[$index]["field"]) . "\").val(\"\");\n";
            } elseif ((strtoupper($campos[$index]["data_type"]) == "TEXT") || (strtoupper($campos[$index]["data_type"]) == "LONGTEXT") || (strtoupper($campos[$index]["data_type"]) == "MEDIUMTEXT") || (strtoupper($campos[$index]["data_type"]) == "TINYTEXT")) {
                $campo = "editor" . $text . ".setContent(\"\", false);\n";
                $text++;
            } else {
                $campo = "$(\"#txt" . ucwords($campos[$index]["field"]) . "\").val(\"\");\n";
            }
        } else {
            $campo = "$(\"#cmb" . ucwords($campos[$index]["field"]) . "\").val(\"\");\n";
        }

        echo $campo;
    }
    ?>
                var bootstrapValidatorx = $('#attributeForm').data('bootstrapValidator');
                        bootstrapValidatorx.resetForm();
                        $('#inputGuardar').show("slow");
                        $('#inputConsultar').show("slow");
                        $('#inputEliminar').hide("slow");
                        $('#inputLimpiar').show("slow");
                }

                llenareditor = function (object, value) {
                try {
                object.ready(function () {
                setTimeout(function(){  object.setContent(value, true); }, 500);
                });
                } catch (e) {
                //alert(e); 
                }
                }

                guardar = function () {

                var bootstrapValidatorx = $("#attributeForm").data('bootstrapValidator');
                        bootstrapValidatorx.validate();
                        var valido = bootstrapValidatorx.isValid();
    //                alert("Result: " + valido);
    //                alert("Result2: " + validar());
                        if (validar()){
                $.ajax({
                type: "POST",
                        url: "../fachadas/generic/GenericFachada.Class.php",
                        async: false,
                        dateType: "json",
                        data: {
    <?php
    $text = 0;
    for ($index = 0; $index < sizeof($campos); $index++) {
        if ($campos[$index]["referenced_table_name"] == "") {

            if ((strtoupper($campos[$index]["data_type"]) == "CHAR") && ((int) $campos[$index]["character_max"] == 1)) {
                $campo = "" . $campos[$index]["field"] . ": $(\"#cmb" . ucwords($campos[$index]["field"]) . "\").val(),\n";
            } elseif ((strtoupper($campos[$index]["data_type"]) == "TEXT") || (strtoupper($campos[$index]["data_type"]) == "LONGTEXT") || (strtoupper($campos[$index]["data_type"]) == "MEDIUMTEXT") || (strtoupper($campos[$index]["data_type"]) == "TINYTEXT")) {
                $campo = "" . $campos[$index]["field"] . ":editor" . $text . ".getContent(),\n";
                $text++;
//var observaciones = editor.getContent();   
            } else {
                $campo = "" . $campos[$index]["field"] . ":$(\"#txt" . ucwords($campos[$index]["field"]) . "\").val(),\n";
            }
        } else {
            $campo = "" . $campos[$index]["field"] . ":$(\"#cmb" . ucwords($campos[$index]["field"]) . "\").val(),\n";
        }

        echo $campo;
    }
    echo "frm:\"" . $tabla . "\",";
    echo "accion:\"guardar\"";
    ?>
                        },
                        success: function (data) {
                        console.log(data);
                                try{
                                var obj = jQuery.parseJSON(data);
                                        if (obj.status == "success"){
                                limpiar();
                                        bootbox.alert("Se guardo el registro de forma correcta", function() {});
    <?php
    $text = 0;
    for ($index = 0; $index < sizeof($campos); $index++) {
        if ($campos[$index]["referenced_table_name"] == "") {

            if ((strtoupper($campos[$index]["data_type"]) == "CHAR") && ((int) $campos[$index]["character_max"] == 1)) {
                $campo = "$(\"#cmb" . ucwords($campos[$index]["field"]) . "\").val(obj.data[0]." . $campos[$index]["field"] . ");\n";
            } elseif ((strtoupper($campos[$index]["data_type"]) == "TEXT") || (strtoupper($campos[$index]["data_type"]) == "LONGTEXT") || (strtoupper($campos[$index]["data_type"]) == "MEDIUMTEXT") || (strtoupper($campos[$index]["data_type"]) == "TINYTEXT")) {
                $campo = "llenareditor(editor" . $text . ",obj.data[0]." . $campos[$index]["field"] . ");\n";
                $text++;
            } else {
                $campo = "$(\"#txt" . ucwords($campos[$index]["field"]) . "\").val(isDate(obj.data[0]." . $campos[$index]["field"] . "));\n";
            }
        } else {
            $campo = "$(\"#cmb" . ucwords($campos[$index]["field"]) . "\").val(obj.data[0]." . $campos[$index]["field"] . ");\n";
        }

        echo $campo;
    }
    ?>
                                var bootstrapValidatorx = $('#attributeForm').data('bootstrapValidator');
                                        bootstrapValidatorx.resetForm();
                                        $('#inputGuardar').show("slow");
                                        $('#inputConsultar').show("slow");
                                        $('#inputEliminar').show("slow");
                                        $('#inputLimpiar').show("slow");
                                } else if (obj.status == "error"){
                                //                alert(obj.msg);
                                bootbox.alert("Ocurrio un error al guardar el registro", function() {});
                                }
                                } catch (e){

                        }
                        }
                });
                } else{
    //                    alert("Estoy aqui");
    //                    alert(validar());
                }
                }


                var tableG = $("#tablaGeneric").DataTable({
                responsive: true,
                        "columnDefs": [
                        {"targets": [0], "visible": false},
                        {responsivePriority: 1, targets: 0}, //Prioridad para mostrar y posici&oacute;n de la columna a tomar, si es positivo o 0 toma de izquierda a derecha
                        {responsivePriority: 2, targets: - 2}//Prioridad para mostrar y posici&oacute;n de la columna a tomar, si es negativo toma de derecha a izquierda
                        ],
                        sDom: configDataTableAlineacion,
                        aaSorting: [[1, 'asc']],
                        aLengthMenu: [
                                [1, 5, 10, 20, 50, 100, 200, 300, 500, 1000],
                                [1, 5, 10, 20, 50, 100, 200, 300, 500, 1000]
                        ],
                        columnDefs: [
    <?php
    $oculta = "";
    for ($index = 0; $index < sizeof($campos); $index++) {
        $param = explode("|", $campos[$index]["column_comment"]);

        if (isset($param[4])) {
            if ((string) $param[4] == "N") {
                $oculta.="{\n";
                $oculta.="targets: [" . $index . "],\n";
                $oculta.="visible: false,\n";
                $oculta.="searchable: false\n";
                $oculta.="},\n";
            }
        }
    }
    $oculta = substr($oculta, 0, -2) . "\n";
    echo $oculta;
    ?>],
                        iDisplayLength: 5,
                        oTableTools: {
                        aButtons: [
                        {
                        sExtends: "collection",
                                sButtonText: "Archivo",
                                aButtons: [
                                {
                                sExtends: "copy",
                                        sButtonText: "Copiar"
                                }, {
                                sExtends: "print",
                                        sButtonText: "Imprimir"
                                }, {
                                sExtends: "collection",
                                        sButtonText: "Guardar como <i class=\"fa fa-angle-down\"></i>",
                                        aButtons: ["csv", "xls",
                                        {
                                        "sExtends": "pdf",
                                                "sPdfOrientation": "landscape",
                                                "sPdfMessage": ""
                                        }
                                        ]
                                }]}],
                                sSwfPath: "../vistas/swf/copy_csv_xls_pdf.swf"},
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
                                        last: "Ultimo",
                                }
                        },
                        processing: false,
                        serverSide: true,
                        bPaginate: true,
                        bSort: true,
                        ajax: {
                        type: "POST",
                                url: "../fachadas/generic/GenericFachada.Class.php",
                                async: false,
                                global:false,
                                data: {
                                accion: "consultar",
                                        frm: "<?php echo $tabla; ?>"
                                }
                        }

                }).on("draw", function(){
                        $("#tablaGeneric").resize();
                });
                
                        $('#tablaGeneric tbody').on('dblclick', 'tr', function () {
                var registro = tableG.row(this).data();
                        $.ajax({
                        type: "POST",
                                url: "../fachadas/generic/GenericFachada.Class.php",
                                async: false,
                                dateType: "html",
                                data: {
    <?php
    for ($index = 0; $index < sizeof($campos); $index++) {
        if ($campos[$index]["column_key"] == "PRI") {
            $campo = "" . $campos[$index]["field"] . ":registro[0],\n";
            echo $campo;
        }
    }
    echo "frm:\"" . $tabla . "\",";
    echo "accion:\"consultar\"";
    ?>
                                },
                                success: function (datos) {
                                    
                                console.log(datos);
                                        try{
                                        json = eval("(" + datos + ")"); //Parsear JSON
                                                limpiar();
    <?php
    $text = 0;
    for ($index = 0; $index < sizeof($campos); $index++) {
        if ($campos[$index]["referenced_table_name"] == "") {

            if ((strtoupper($campos[$index]["data_type"]) == "CHAR") && ((int) $campos[$index]["character_max"] == 1)) {
                $campo = "$(\"#cmb" . ucwords($campos[$index]["field"]) . "\").val(json.data[0]." . $campos[$index]["field"] . ");\n";
            } elseif ((strtoupper($campos[$index]["data_type"]) == "TEXT") || (strtoupper($campos[$index]["data_type"]) == "LONGTEXT") || (strtoupper($campos[$index]["data_type"]) == "MEDIUMTEXT") || (strtoupper($campos[$index]["data_type"]) == "TINYTEXT")) {
                $campo = "llenareditor(editor" . $text . ",json.data[0]." . $campos[$index]["field"] . ");\n";
                $text++;
            } else {
                $campo = "$(\"#txt" . ucwords($campos[$index]["field"]) . "\").val(isDate(json.data[0]." . $campos[$index]["field"] . "));\n";
            }
        } else {
            $campo = "$(\"#cmb" . ucwords($campos[$index]["field"]) . "\").val(json.data[0]." . $campos[$index]["field"] . ");\n";
        }

        echo $campo;
    }
    ?>
                                        cambiaDiv(1);
                                                //                                $('#attributeForm').bootstrapValidator({excluded: [':disabled']});
                                                var bootstrapValidatorx = $('#attributeForm').data('bootstrapValidator');
                                                bootstrapValidatorx.resetForm();
                                                $('#inputGuardar').show("slow");
                                                $('#inputConsultar').show("slow");
                                                $('#inputEliminar').show("slow");
                                                $('#inputLimpiar').show("slow");
                                        } catch (e){
                                //                        bootbox.dialog({
                                //  title: "That html",
                                //  message: '<img src="img/bootstrap_logo.png" width="100px"/><br/> You can also use <b>html</b>'
                                //});
    //                        alert(e);
                                bootbox.alert("Ocurrio un error al realizar una consulta", function() {});
                                }
                                $(".md-form label").addClass("active");
                                }
                        });
                });
                        eliminar = function(){
    <?php
    for ($index = 0; $index < sizeof($campos); $index++) {
        if ($campos[$index]["column_key"] == "PRI") {
//            echo "alert($('#txt" . $campos[$index]["field"] . "').val());\n";
            echo "if($('#txt" . ucwords($campos[$index]["field"]) . "').val()!=\"\"){\n";
        }
    }
    ?>

                        $.ajax({
                        type: "POST",
                                url: "../fachadas/generic/GenericFachada.Class.php",
                                async: false,
                                dateType: "html",
                                data: {
    <?php
    for ($index = 0; $index < sizeof($campos); $index++) {
        if ($campos[$index]["column_key"] == "PRI") {
            $campo = "" . $campos[$index]["field"] . ":$(\"#txt" . ucwords($campos[$index]["field"]) . "\").val(),\n";
            echo $campo;
        }
    }
    echo "frm:\"" . $tabla . "\",";
    echo "accion:\"baja\"";
    ?>
                                },
                                success: function (datos) {
                                console.log(datos);
                                        try{
                                        json = eval("(" + datos + ")"); //Parsear JSON
                                                if (json.status == "success"){
                                        limpiar();
                                                bootbox.alert("Registro dado de baja de forma correcta", function() {});
                                                var bootstrapValidatorx = $('#attributeForm').data('bootstrapValidator');
                                                bootstrapValidatorx.resetForm();
                                                $('#inputGuardar').show("slow");
                                                $('#inputConsultar').show("slow");
                                                $('#inputEliminar').hide("slow");
                                                $('#inputLimpiar').show("slow");
                                        } else if (json.status == "error"){
                                        bootbox.alert("Ocurrio un error al eliminar el registro", function() {});
                                        }
                                        } catch (e){
                                bootbox.alert("Ocurrio un error al realizar una consulta", function() {});
                                }
                                }
                        });
    <?php
    for ($index = 0; $index < sizeof($campos); $index++) {
        if ($campos[$index]["column_key"] == "PRI") {
            echo "} else {\n";
            echo "bootbox.alert(\"No selecciono un registro para dicha accion\", function() {});\n}\n";
        }
    }
    ?>

                        }

                } catch (e) {
        console.log(e);
                alert(e);
        }

    <?php
    echo "$(function(){\n";
    if (sizeof($editText) > 0) {
        for ($index = 0; $index < sizeof($editText); $index++) {
            echo "editor" . $index . " = UE . getEditor(" . $editText[$index] . ");\n";
            echo "editor" . $index . " . ready(function () {\n";
            echo "editor" . $index . " . setContent();\n";
            echo "});\n";
        }
    }

    for ($index = 0; $index < sizeof($campos); $index++) {
        if ($campos[$index]["column_key"] == "PRI") {
            echo "if($('#" . $campos[$index]["field"] . "').val()==\"\"){\n";
            echo "$('#inputGuardar').show(\"slow\");\n";
            echo "$('#inputConsultar').show(\"slow\");\n";
            echo "$('#inputEliminar').hide(\"slow\");\n";
            echo "$('#inputLimpiar').show(\"slow\");\n";
            echo "}\n";
        }
    }
    echo "});\n";
    ?>

    </script>

    <!-- Modal -->
    <!--    <div class="modal fade" id="modalGeneric" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Registro de <?php //echo ucwords($tabla);                                                                                                       ?></h4>
                    </div>
                    <div class="modal-body">
                        
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-primary" >Limpiar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>-->
    <?php
} else {
    echo "Atenci&oacute;n! debe elegir un perfil. ";
}
// Una vez que el b�fer almacena nuestro contenido utilizamos "ob_end_flush" para usarlo y deshabilitar el b�fer
@ob_end_flush();
?>
