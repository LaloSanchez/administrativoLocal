<?php

error_reporting(E_ALL);
error_reporting(-1);
include_once(ROOT . DS . "tribunal" . DS . "connect" . DS . "Proveedor.Class.php");
include_once(ROOT . DS . "tribunal" . DS . "logger" . DS . "Logger.Class.php");
include_once(ROOT . DS . "tribunal" . DS . "tagXml" . DS . "TagXml.Class.php");

class View {

    public function __construct() {
        
    }

    public static function run() {
        $tablas = "";
        $contador = 0;
        $error = false;

        $proveedor = new Proveedor(DEFECTO_GESTOR, DEFECTO_BD);
        $proveedor->connect();

        $proveedor->execute("show tables");
        if (!$proveedor->error()) {
            if ($proveedor->rows($proveedor->stmt) > 0) {
                while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
                    $tablas[$contador] = $row[0];
                    $contador++;
                }
            } else {
                $error = true;
                throw new Exception("No se loalizaron tablas de la bd");
            }
        } else {
            $error = true;
            throw new Exception("Ocurrio un error al obtener el listado de tablas de la base");
        }

        if ((!$error) && (count($tablas) > 0)) {
            $logger = new Logger("/../../logs/", "Views");
            $logger->w_onError("**********COMIENZA EL PROGRAMA CON LA CREACION DEL VIEW**********");
            for ($i = 0; $i < count($tablas); $i++) {
//                $constrain = "";
//                $contador = 0;
//                $sql = "SELECT TABLE_SCHEMA from Information_Schema.Tables Where TABLE_NAME='" . $tablas[$i] . "'";
//                $proveedor->execute($sql);
//                if (!$proveedor->error()) {
//                    if ($proveedor->rows($proveedor->stmt) > 0) {
//                        $row = $proveedor->fetch_rows($proveedor->stmt, 0);
//                        $bd = $row[0];
//                    } else {
//                        $error = true;
//                    }
//                } else {
//                    $error = true;
//                }
                $constrain = array();
                $contador = 0;
                $sql = "select "
                        . "    table_name,"
                        . "    column_name,"
                        . "    referenced_table_name,"
                        . "    referenced_column_name "
                        . "from     "
                        . "    information_schema.key_column_usage "
                        . "where     "
                        . "    referenced_table_name is not null     "
                        . "    and table_schema = '" . DEFECTO_NAME_BD . "'      "
                        . "    and table_name = '" . $tablas[$i] . "'";
                $proveedor->execute($sql);
                if (!$proveedor->error()) {
                    if ($proveedor->rows($proveedor->stmt) > 0) {
                        while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
                            $constrain[$contador] = array("table_name" => $row[0], "column_name" => $row[1], "referenced_table_name" => $row[2], "referenced_column_name" => $row[3]);
                            $contador++;
                        }
                    } else {
                        $error = true;
                    }
                } else {
                    $error = true;
                }

                $campos = "";
                $contador = 0;
                $proveedor->execute("desc " . $tablas[$i]);
                if (!$proveedor->error()) {
                    if ($proveedor->rows($proveedor->stmt) > 0) {
                        while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
                            $campos[$contador] = array("field" => $row[0], "primary" => $row[3], "type" => $row[1], "null" => $row[2]);
                            $contador++;
                        }
                    } else {
                        $error = true;
                    }
                } else {
                    $error = true;
                }

                if (count($campos) > 0) {
                    $pref = substr($tablas[$i], 0, 3);
                    $name = "";
                    if ($pref == DEFECTO_PREFIJO) {
                        $name = substr($tablas[$i], 3);
                    } else {
                        $name = $tablas[$i];
                    }

                    $view = new View();
                    $view->creaDirectorio(ROOT . "vistas" . DS . DEFECTO_BD . DS . $name);
                    $logger->w_onError("CREAMOS EL DIRECTORIO: " . ROOT . "vistas" . DS . DEFECTO_BD . DS . $name);
                    if ($view->existeDirectorio(ROOT . "vistas" . DS . DEFECTO_BD . DS . $name)) {
                        if (!$view->existeArchivo(ROOT . "vistas" . DS . DEFECTO_BD . DS . $name . DS . "frm" . ucwords($name) . "View.php")) {
                            $dto = fopen(ROOT . "vistas" . DS . DEFECTO_BD . DS . $name . DS . "frm" . ucwords($name) . "View.xml", "w");
                            fwrite($dto, "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><vistas></vistas>");
                            fclose($dto);
                            $tagXml = new TagXml();
                            $tagXml->createXml(ROOT . "vistas" . DS . DEFECTO_BD . DS . $name . DS . "frm" . ucwords($name) . "View.xml", "frm" . ucwords($name) . "View");

                            for ($x = 0; $x < count($campos); $x++) {
                                if (strtoupper($campos[$x]["type"]) == "CHAR(1)") {
                                    $tagXml->addChild($campos[$x]["field"], "cmb");
                                } else {
                                    $tagXml->addChild($campos[$x]["field"], "txt");
                                }
                            }
                            $tagXml->saveXml(ROOT . "vistas" . DS . DEFECTO_BD . DS . $name . DS . "frm" . ucwords($name) . "View.xml");

                            $dto = fopen(ROOT . "vistas" . DS . DEFECTO_BD . DS . $name . DS . "frm" . ucwords($name) . "View.php", "w");
                            $logger->w_onError("CREAMOS EL ARCHIVO: " . ROOT . "vistas" . DS . DEFECTO_BD . DS . $name . DS . "frm" . ucwords($name) . "View.php");

                            $cuerpo = "<?php\n";

                            $cuerpo.="/*\n";
                            $cuerpo.="*************************************************\n";
                            $cuerpo.="*FRAMEWORK V1.0.0 (http://www.pjedomex.gob.mx)\n";
                            $cuerpo.="*Copyright 2009-2015 VIEWS\n";
                            $cuerpo.="* Licensed under the MIT license \n";
                            $cuerpo.="* Autor: *\n";
                            $cuerpo.="* Departamento de Desarrollo de Software\n";
                            $cuerpo.="* Subdireccion de Ingenieria de Software\n";
                            $cuerpo.="* Direccion de Teclogias de Informacion\n";
                            $cuerpo.="* Poder Judicial del Estado de Mexico\n";
                            $cuerpo.="*************************************************\n";
                            $cuerpo.="*/\n\n";
                            $cuerpo.="session_start();\n";

                            $cuerpo.="include_once(dirname(__FILE__).\"/../../../tribunal/tagXml/TagXml.Class.php\");\n";
                            $cuerpo.="include_once(dirname(__FILE__).\"/../../../tribunal/json/JsonDecod.Class.php\");\n";
//                            $cuerpo.="include_once(dirname(__FILE__).\"/../../../webservice/cliente/permisos/PermisosCliente.php\");\n";
//                            $cuerpo.="\$permisosCliente = new PermisosCliente();\n";
//                            $cuerpo.="\$permisos = \$permisosCliente->getPermisos(\"../../vistas/".DEFECTO_BD.DS."$name/" . "frm" . ucwords($name) . "View.php\",\$_SESSION[\"cveSistema\"],\$_SESSION[\"cvePerfil\"]);\n";
//                            $cuerpo.="if(\$permisos!=\"\"){\n";
//                            $cuerpo.="\$decodeJson = new Decode_JSON();\n";
//                            $cuerpo.="\$permisos = \$decodeJson->decode(\$permisos, true);\n";
//                            $cuerpo.="var_dump(\$permisos[\"data\"]);";
//                            $cuerpo.="var_dump(\$permisos);";

                            $cuerpo.="\$tagXml = new TagXml();\n";
                            $cuerpo.="\$tagXml->cargaXml(dirname(__FILE__) . \"/../../../vistas/" . DEFECTO_BD . DS . "/$name/" . "frm" . ucwords($name) . "View.xml\", \"" . "frm" . ucwords($name) . "View\");\n";
                            $cuerpo.="?>\n";

                            $cuerpo.= "<!DOCTYPE html>\n";
                            $cuerpo.="<html lang = \"es\">\n";
                            $cuerpo.="<head>\n";
                            $cuerpo.="<meta charset = \"ISO-8859-1\">\n";
                            $cuerpo.="<meta http-equiv = \"X-UA-Compatible\" content = \"IE=edge\">\n";
                            $cuerpo.="<meta name = \"viewport\" content = \"width=device-width, initial-scale=1\">\n";
                            $cuerpo.="<!--The above 3 meta tags *must* come first in the head;\n";
                            $cuerpo.="any other head content must come *after* these tags -->\n";
                            $cuerpo.="<title>Formulario de " . ucwords($name) . "</title>\n";
                            //$cuerpo.="<!--Bootstrap -->\n";
                            //$cuerpo.="<link href = \"../../js/bootstrap/css/bootstrap.min.css\" rel = \"stylesheet\">\n";
                            //$cuerpo.="<link href = \"../../css/datetimepicker/bootstrap-datetimepicker.css\" rel = \"stylesheet\">";
                            //$cuerpo.="<link href = \"../../css/datagrid/jsonGrid.css\" rel = \"stylesheet\">\n";
                            //$cuerpo.="<link href = \"../../css/normalize.css\" rel = \"stylesheet\">\n";
//                            $cuerpo.="<link href = \"../css/datagrid/simple.datagrid.css\" rel = \"stylesheet\">\n";
                            $cuerpo.="<!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->\n";
                            $cuerpo.="<!--WARNING: Respond.js doesn't work if you view the page via file:// -->\n";
                            $cuerpo.="<!--[if lt IE 9]>\n";
                            $cuerpo.="<script src=\"../../js/html5shiv/html5shiv.min.js\"></script>\n";
                            $cuerpo.="<script src=\"../../js/respond/respond.min.js\"></script>\n";
                            $cuerpo.="<![endif]-->\n";
                            //$cuerpo.="<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->\n";
                            //$cuerpo.="<script src = \"../../js/jquery/jquery.min.js\"></script>\n";
                            //$cuerpo.="<script src = \"../../js/moment/moment.min.js\"></script>";
                            //$cuerpo.="<!-- Include all compiled plugins (below), or include individual files as needed -->\n";
                            //$cuerpo.="<script src=\"../../js/bootstrap/js/bootstrap.min.js\"></script>\n";
                            //$cuerpo.="<script src=\"../../js/datetimepicker/bootstrap-datetimepicker.min.js\"></script>";
                            //$cuerpo.="<script src=\"../../js/datagrid/jsonMyDatagrid.js\"></script>\n";
//                            $cuerpo.="<script src=\"../../js/datagrid/simple.datagrid.js\"></script>\n";
                            //$cuerpo.="<script src=\"../../js/funciones.js\"></script>\n";
                            $cuerpo.="<script>\n";
                            $cuerpo.="$(document).ready(function(){\n";
                            $cuerpo.="$('[data-toggle=\"tooltip\"]').tooltip();\n";
                            $cuerpo.="});\n";
                            $cuerpo.="guardar" . ucwords($name) . " = function(){\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="var <?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?> = document.getElementById(\"<?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?>\");\n";
                            }
                            $cuerpo.="$.ajax({\n";
                            $cuerpo.="type: \"POST\",\n";
                            $cuerpo.="url: \"../fachadas/" . DEFECTO_BD . DS . "" . $name . "/" . ucwords($name) . "Facade.Class.php\",\n";
                            $cuerpo.="data: {\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.=$campos[$x]["field"] . " : <?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"id\" ); ?>.value,\n";
//                                if (strtoupper($campos[$x]["type"]) == "CHAR(1)") {
//                                    $cuerpo.=$campos[$x]["field"] . " : cmb" . ucwords($campos[$x]["field"]) . ".value,";
//                                } else {
//                                    $cuerpo.=$campos[$x]["field"] . " : txt" . ucwords($campos[$x]["field"]) . ".value,";
//                                }
                            }
                            $cuerpo.="accion : \"guardar\"";
                            $cuerpo.="},\n";
                            $cuerpo.="async: true,\n";
                            $cuerpo.="dataType: \"json\",\n";
                            $cuerpo.="beforeSend: function(objeto) {\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"<img src='/img/cargando.gif'/>\";\n";
                            $cuerpo.="},\n";
                            $cuerpo.="success: function(datos) {\n";
                            $cuerpo.="try {\n";
                            $cuerpo.="if (datos.totalCount > 0) {\n";
                            $cuerpo.="alert(datos.text);\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.= "<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"id\" ); ?>.value=datos.data[0]." . $campos[$x]["field"] . ";\n";
//                                if (strtoupper($campos[$x]["type"]) == "CHAR(1)") {
//                                    $cuerpo.="cmb" . ucwords($campos[$x]["field"]) . ".value=datos.data[0]." . $campos[$x]["field"] . ";\n";
//                                } else {
//                                    $cuerpo.="txt" . ucwords($campos[$x]["field"]) . ".value=datos.data[0]." . $campos[$x]["field"] . ";\n";
//                                }
                            }
//                            $cuerpo.="consulta" . ucwords($name) . "();\n";
                            /* $cuerpo.="<?php\n if(\$permisos[\"data\"][0]->consulta=='S')\n echo \"consulta" . ucwords($name) . "();\\n\";\n?>\n";
                              //                            $cuerpo.="}else{\n"; */
                            $cuerpo.="}\n";
                            //$cuerpo.="alert(datos.text);\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"\";\n";
//                            $cuerpo.="consulta" . ucwords($name) . "();\n";
                            /* $cuerpo.="<?php\n if(\$permisos[\"data\"][0]->consulta=='S')\n echo \"consulta" . ucwords($name) . "();\\n\";\n?>\n";
                              $cuerpo.="}\n"; */
                            $cuerpo.="} catch (e) {\n";
                            $cuerpo.="alert(datos.text);\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"\";\n";
//                            $cuerpo.="consulta" . ucwords($name) . "();\n";
                            /* $cuerpo.="<?php\n if(\$permisos[\"data\"][0]->consulta=='S')\n echo \"consulta" . ucwords($name) . "();\\n\";\n?>\n"; */
                            $cuerpo.="}\n";
                            $cuerpo.="},\n";
                            $cuerpo.="error: function(objeto, quepaso, otroobj) {}\n";
                            $cuerpo.="});\n";
                            $cuerpo.="}\n";


                            $cuerpo.="baja" . ucwords($name) . " = function(){\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="var <?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?> = document.getElementById(\"<?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?>\");\n";
                            }
                            $cuerpo.="if(confirm(\"\¿ ESTAS SEGURO DE ELIMINAR EL REGISTRO ?\")==true){\n";
                            $cuerpo.="$.ajax({\n";
                            $cuerpo.="type: \"POST\",\n";
                            $cuerpo.="url: \"../fachadas/" . DEFECTO_BD . DS . "" . $name . "/" . ucwords($name) . "Facade.Class.php\",\n";
                            $cuerpo.="data: {\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.=$campos[$x]["field"] . " : <?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"id\" ); ?>.value,\n";
//                                if (strtoupper($campos[$x]["type"]) == "CHAR(1)") {
//                                    $cuerpo.=$campos[$x]["field"] . " : cmb" . ucwords($campos[$x]["field"]) . ".value,";
//                                } else {
//                                    $cuerpo.=$campos[$x]["field"] . " : txt" . ucwords($campos[$x]["field"]) . ".value,";
//                                }
                            }
                            $cuerpo.="accion : \"baja\"";
                            $cuerpo.="},\n";
                            $cuerpo.="async: true,\n";
                            $cuerpo.="dataType: \"json\",\n";
                            $cuerpo.="beforeSend: function(objeto) {\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"<img src='/img/cargando.gif'/>\";\n";
                            $cuerpo.="},\n";
                            $cuerpo.="success: function(datos) {\n";

                            $cuerpo.="try {\n";
//                            $cuerpo.="if (datos.totalCount > 0) {\n";
                            $cuerpo.="alert(datos.text);\n";
                            $cuerpo.="limpia" . ucwords($name) . "();\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"\";\n";
//                            $cuerpo.="consulta" . ucwords($name) . "();\n";
                            /* $cuerpo.="<?php\n if(\$permisos[\"data\"][0]->consulta=='S')\n echo \"consulta" . ucwords($name) . "();\\n\";\n?>\n"; */

//                            $cuerpo.="}else{\n";
//                            $cuerpo.="alert(datos.text);\n";
//                            $cuerpo.="}\n";
                            $cuerpo.="} catch (e) {\n";
                            $cuerpo.="alert(datos.text);\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"\";\n";
//                            $cuerpo.="consulta" . ucwords($name) . "();\n";
                            /* $cuerpo.="<?php\n if(\$permisos[\"data\"][0]->consulta=='S')\n echo \"consulta" . ucwords($name) . "();\\n\";\n?>\n"; */
                            $cuerpo.="}\n";

                            $cuerpo.="},\n";
                            $cuerpo.="error: function(objeto, quepaso, otroobj) {}\n";
                            $cuerpo.="});\n";
                            $cuerpo.="}\n";
                            $cuerpo.="}\n";

                            $cuerpo.="consulta" . ucwords($name) . " = function(){\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="var <?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?> = document.getElementById(\"<?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?>\");\n";
                            }
                            $cuerpo.="$.ajax({\n";
                            $cuerpo.="type: \"POST\",\n";
                            $cuerpo.="url: \"../fachadas/" . DEFECTO_BD . DS . "" . $name . "/" . ucwords($name) . "Facade.Class.php\",\n";
                            $cuerpo.="data: {\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.=$campos[$x]["field"] . " : <?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"id\" ); ?>.value,\n";
//                                if (strtoupper($campos[$x]["type"]) == "CHAR(1)") {
//                                    $cuerpo.=$campos[$x]["field"] . " : cmb" . ucwords($campos[$x]["field"]) . ".value,";
//                                } else {
//                                    $cuerpo.=$campos[$x]["field"] . " : txt" . ucwords($campos[$x]["field"]) . ".value,";
//                                }
                            }
                            $cuerpo.="accion : \"consultar\"";
                            $cuerpo.="},\n";
                            $cuerpo.="async: true,\n";
                            $cuerpo.="dataType: \"html\",\n";
                            $cuerpo.="beforeSend: function(objeto) {\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"<img src='/img/cargando.gif'/>\";\n";
                            $cuerpo.="},\n";
                            $cuerpo.="success: function(datos) {\n";
//                            $cuerpo.="$('#div" . ucwords($name) . "').simple_datagrid(\"loadData\",datos.data);";
                            $cuerpo.="var result = eval(\"(\" + datos + \")\");\n";
                            $cuerpo.="if (result.totalCount > 0) {\n";



                            $cuerpo.="var table = \"\";\n";
                            $cuerpo.="table += \"<div class='search-table-outter'>\";\n";
                            $cuerpo.="table += \"<table id='tblResultadosGrid' class='table table-hover table-striped table-bordered'>\";\n";
                            $cuerpo.="table += \"<thead>\";\n";
                            $cuerpo.="table += \"<tr>\";\n";

                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="table += \"<th><?php  echo \$tagXml->getTag(\"" . $campos[$x]["field"] . "\"); ?></th>\"\n";
                            }

                            $cuerpo.="table += \"<th>MODIFICAR</th>\";\n";
                            $cuerpo.="table += \"</tr>\";";
                            $cuerpo.="table += \"</thead>\";";
                            $cuerpo.="table += \"<tbody>\";";
                            $cuerpo.="for (var i = 0; i < result.totalCount; i++) {";
                            $cuerpo.="table += \"<tr>\";";
                            for ($x = 0; $x < count($campos); $x++) {
                                if (strtoupper($campos[$x]["type"]) == "CHAR(1)") {
                                    $cuerpo.="if (result.data[i].<?php  echo \$tagXml->getTag(\"" . $campos[$x]["field"] . "\",\"id\"); ?> == \"S\"){";
                                    $cuerpo.="table += \"<td>SI</td>\";\n";
                                    $cuerpo.="}else{";
                                    $cuerpo.="table += \"<td>NO</td>\";\n";
                                    $cuerpo.="}";
                                } else {
                                    $cuerpo.="table += \"<td>\"+result.data[i].<?php  echo \$tagXml->getTag(\"" . $campos[$x]["field"] . "\",\"id\"); ?>+\"</td>\"\n";
                                }
                            }
                            $cuerpo.="table += \"<td><div ><a href='#noir' onclick='selecciona" . ucwords($name) . "(\" + result.data[i].cveAccion + \")'>Modificar</a></div></td>\";\n";
                            $cuerpo.="table += \"</tr> \";";
                            $cuerpo.="}";
                            $cuerpo.="table += \"</tbody>\";";
                            $cuerpo.="table += \"</table>\";";
                            $cuerpo.="table += \"</div>\";";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = table;\n";
                            $cuerpo.="$(\"#divAccionesws\").show(\"slow\");\n";




                            $cuerpo.="}else{\n";
                            $cuerpo.="alert(result.text);\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"\";\n";
                            $cuerpo.="}\n";
                            $cuerpo.="},\n";
                            $cuerpo.="error: function(objeto, quepaso, otroobj) {}\n";
                            $cuerpo.="});\n";
                            $cuerpo.="}\n";
                            $cuerpo.="limpia" . ucwords($name) . " = function(){\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"id\" ); ?>.value=\"\";\n";
//                                if (strtoupper($campos[$x]["type"]) == "CHAR(1)") {
//                                    $cuerpo.="cmb" . ucwords($campos[$x]["field"]) . ".value=\"S\";";
//                                } else {
//                                    $cuerpo.="txt" . ucwords($campos[$x]["field"]) . ".value=\"\";";
//                                }
                            }
                            $cuerpo.="}\n";


                            $cuerpo.="selecciona" . ucwords($name) . " = function(id" . $primary . "){\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="var <?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?> = document.getElementById(\"<?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?>\");\n";
                            }
                            $cuerpo.="$.ajax({\n";
                            $cuerpo.="type: \"POST\",\n";
                            $cuerpo.="url: \"../fachadas/" . DEFECTO_BD . DS . "" . $name . "/" . ucwords($name) . "Facade.Class.php\",\n";
                            $cuerpo.="data: {\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                if ($campos[$x]["primary"] == "PRI") {
                                    $cuerpo.=$campos[$x]["field"] . " : <?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"id\" ); ?> .value=id" . $primary . ",\n";
                                }
                            }
                            $cuerpo.="accion : \"seleccionar\"";
                            $cuerpo.="},\n";
                            $cuerpo.="async: true,\n";
                            $cuerpo.="dataType: \"json\",\n";
                            $cuerpo.="beforeSend: function(objeto) {\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"<img src='img/cargando.gif'/>\";\n";
                            $cuerpo.="},\n";
                            $cuerpo.="success: function(datos) {\n";
                            $cuerpo.="try {\n";
                            $cuerpo.="if (datos.totalCount > 0) {\n";
//                            $cuerpo.="alert(datos.text);\n";
                            for ($x = 0; $x < count($campos); $x++) {
//                                if (strtoupper($campos[$x]["type"]) == "CHAR(1)") {
//                                    $cuerpo.="cmb" . ucwords($campos[$x]["field"]) . ".value=datos.data[0]." . $campos[$x]["field"] . ";\n";
//                                } else {
//                                    $cuerpo.="txt" . ucwords($campos[$x]["field"]) . ".value=datos.data[0]." . $campos[$x]["field"] . ";\n";
//                                }
                                $cuerpo.="<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"id\" ); ?>.value=datos.data[0]." . $campos[$x]["field"] . "\n";
                            }
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"\";\n";
                            //$cuerpo.="consulta" . ucwords($name) . "();\n";
                            $cuerpo.="}else{\n";
                            $cuerpo.="alert(datos.text);\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"\";\n";
                            $cuerpo.="}\n";
                            $cuerpo.="} catch (e) {\n";
                            $cuerpo.="alert(datos.text);\n";
                            $cuerpo.="document.getElementById('div" . ucwords($name) . "').innerHTML = \"\";\n";
                            $cuerpo.="}\n";
                            $cuerpo.="},\n";
                            $cuerpo.="error: function(objeto, quepaso, otroobj) {}\n";
                            $cuerpo.="});\n";
                            $cuerpo.="}\n";


                            for ($x = 0; $x < count($campos); $x++) {
                                for ($y = 0; $y < count($constrain); $y++) {
                                    if ($constrain[$y]["column_name"] == $campos[$x]["field"]) {
//                                        $cuerpo.=" llave Foranea: " . $campos[$x]["field"] . "de " . $constrain[$y]["referenced_table_name"];
                                        $prefijo = substr($constrain[$y]["referenced_table_name"], 0, 3);
                                        if ($prefijo == DEFECTO_PREFIJO) {
                                            $n = substr($constrain[$y]["referenced_table_name"], 3);
                                        } else {
                                            $n = $constrain[$y]["referenced_table_name"];
                                        }
                                        $cuerpo.="lista" . ucwords($n) . " = function (tabIndex) {\n";
                                        $cuerpo.="$.ajax({\n";
                                        $cuerpo.="type: \"POST\",\n";
                                        $cuerpo.="url: \"../fachadas/" . DEFECTO_BD . DS . "" . $n . "/" . ucwords($n) . "Facade.Class.php\",\n";
                                        $cuerpo.="data: {accion: \"consultar\"},\n";
                                        $cuerpo.="async: true,\n";
                                        $cuerpo.="dataType: \"json\",\n";
                                        $cuerpo.="beforeSend: function (objeto) {\n";
                                        $cuerpo.="document.getElementById('div" . ucwords($n) . "').innerHTML = \"<img src='/img/cargando.gif'/>\";\n";
                                        $cuerpo.="},\n";
                                        $cuerpo.="success: function (datos) {\n";
                                        $cuerpo.="try {\n";
                                        $cuerpo.="var html = \"\";";
                                        $cuerpo.="if (datos.totalCount > 0) {\n";
                                        $cuerpo.="html += '<select name=\"<?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"name\"); ?>\" id=\"<?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?>\" class=\"form-control text-uppercase\" title=\"<?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"tooltip\"); ?>\" data-toggle=\"tooltip\" tabIndex=\"tabIndex\">';\n";
                                        $cuerpo.="for (var index = 0; index < datos.totalCount; index++) {\n";
                                        $cuerpo.="html += \"<option value='\" + datos.data[index]." . $campos[$x]["field"] . " + \"'>\" + datos.data[index]." . $campos[$x]["field"] . " + \"</option>\";\n";
                                        $cuerpo.="}\n";
                                        $cuerpo.="html += \"</select>\";\n";
//                                document.getElementById('divCveAccion').innerHTML = html;
                                        $cuerpo.="} else {\n";
                                        $cuerpo.="html = \"Sin resultados\";\n";
                                        $cuerpo.="}\n";
                                        $cuerpo.="document.getElementById('div" . ucwords($n) . "').innerHTML = html;\n";
                                        $cuerpo.="} catch (e) {\n";
                                        $cuerpo.="alert(e);\n";
                                        $cuerpo.="}\n";
                                        $cuerpo.="},\n";
                                        $cuerpo.="error: function (objeto, quepaso, otroobj) {\n";
                                        $cuerpo.="}\n";
                                        $cuerpo.="});\n";
                                        $cuerpo.="}\n";
                                        break;
                                    }
                                }
                            }

                            $cuerpo.="</script>\n";
                            $cuerpo.="</head>\n";
                            $cuerpo.="<body>\n";
                            $cuerpo.="<div class=\"container\">\n";
                            $cuerpo.="<div class=\"starter-template\">\n";
                            $cuerpo.="<fieldset>\n";
                            $cuerpo.="<legend>Registro de " . ucwords($name) . "</legend>\n";
                            //$cuerpo.="<table class=\"table table-striped table-hover\" width=\"50%\">\n";
                            //$cuerpo.="<tr>\n";
                            //$cuerpo.="<td colspan=\"4\" align=\"center\"><h4>Registro de " . ucwords($name) . "</h4></td>\n";
                            //$cuerpo.="</tr>\n";
                            $cuerpo.="<p style=\"text-align: center;\">";
                            for ($x = 0; $x < count($campos); $x++) {
//                                $cuerpo.="<tr>\n";
                                /* $cuerpo.="<td align=\"right\" class=\"col-lg-3\"><label class=\"caption\" id=\"" . $campos[$x]["field"] . "\"><?php echo \$tagXml->getTag(" . $campos[$x]["field"] . "); ?>" . "</label></td>\n"; */
                                /* $cuerpo.="<td align=\"left\" class=\"col-lg-3\"><div class=\"control-group\">\n"; */
                                $cuerpo.="<div class=\"form-group col-lg-12\" <?php echo (\$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"hidden\")==\"true\") ? \"style=\\\"display:none;\\\"\":\"\"; ?> >\n";
                                $cuerpo.="<label for=\"<?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?>\" class=\"caption\" id=\"" . $campos[$x]["field"] . "\"><?php echo \$tagXml->getTag(\"" . $campos[$x]["field"] . "\"); ?>" . "</label>\n";
                                if (strtoupper($campos[$x]["type"]) == "CHAR(1)") {
                                    $cuerpo.="<select name=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"name\" ); ?>\" id=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"id\" ); ?>\" class=\"form-control text-uppercase\" tabIndex=\"" . ($x + 1) . "\" title=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"tooltip\" ); ?>\" data-toggle=\"tooltip\" >\n";
                                    $cuerpo.="<option value=\"S\">SI</option>\n";
                                    $cuerpo.="<option value=\"N\">NO</option>\n";
                                    $cuerpo.="</select>\n";
                                    $cuerpo.="<script>\n";
                                    $cuerpo.="$(\"#<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"name\" ); ?>\").keydown(posValue);\n";
                                    $cuerpo.="</script>\n";
                                } else {
                                    $fk = false;
                                    $n = "";
                                    for ($y = 0; $y < count($constrain); $y++) {
                                        if ($constrain[$y]["column_name"] == $campos[$x]["field"]) {
                                            $prefijo = substr($constrain[$y]["referenced_table_name"], 0, 3);
                                            if ($prefijo == DEFECTO_PREFIJO) {
                                                $n = substr($constrain[$y]["referenced_table_name"], 3);
                                            } else {
                                                $n = $constrain[$y]["referenced_table_name"];
                                            }
                                            $fk = true;
                                            break;
                                        }
                                    }

                                    if (!$fk) {
                                        $cuerpo.="<input type=\"<?php echo (\$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"hidden\")==\"true\") ? \"hidden\":\"text\" ?>\" name=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"name\" ); ?>\" id=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"id\" ); ?>\" placeholder=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"placeholder\" ); ?>\" title=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"tooltip\" ); ?>\" data-toggle=\"tooltip\" ";
                                        if ((strtoupper($campos[$x]["field"]) == "FECHAREGISTRO") || (strtoupper($campos[$x]["field"]) == "FECHAACTUALIZACION")) {
                                            $cuerpo.=" class=\"form-control text-uppercase\" readonly tabIndex=\"" . ($x + 1) . "\">\n";
                                        } else {
                                            $cuerpo.=" class=\"form-control text-uppercase\" tabIndex=\"" . ($x + 1) . "\">\n";
                                        }
                                    } else {
                                        $cuerpo.="<div name=\"div" . ucwords($n) . "\" id=\"div" . ucwords($n) . "\">\n";
                                        $cuerpo.="<input type=\"<?php echo (\$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"hidden\")==\"true\") ? \"hidden\":\"text\" ?>\" name=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"name\" ); ?>\" id=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"id\" ); ?>\" placeholder=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"placeholder\" ); ?>\" title=\"<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"tooltip\" ); ?>\" data-toggle=\"tooltip\" ";
                                        if ((strtoupper($campos[$x]["field"]) == "FECHAREGISTRO") || (strtoupper($campos[$x]["field"]) == "FECHAACTUALIZACION")) {
                                            $cuerpo.=" class=\"form-control text-uppercase\" readonly tabIndex=\"" . ($x + 1) . "\">\n";
                                        } else {
                                            $cuerpo.=" class=\"form-control text-uppercase\" tabIndex=\"" . ($x + 1) . "\">\n";
                                        }
                                        $cuerpo.="</div>\n";
                                    }

                                    $cuerpo.="<script>\n";
                                    $cuerpo.="$(\"#<?php  echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\",\"name\" ); ?>\").keydown(posValue);\n";

                                    if ((strtoupper($campos[$x]["type"]) == "DATE") || (strtoupper($campos[$x]["type"]) == "DATETIME")) {
                                        $cuerpo.="$('#<?php echo \$tagXml->getAttribut(\"" . $campos[$x]["field"] . "\", \"id\"); ?>').datetimepicker(";
                                        if ((strtoupper($campos[$x]["type"]) == "DATE")) {
                                            $cuerpo.="{\nformat: 'DD/MM/YYYY'\n}";
                                        }
                                        $cuerpo.=");\n";
                                    }

                                    if ($fk) {
                                        $cuerpo.="lista" . ucwords($n) . "(" . ($x + 1) . ");\n";
                                    }
                                    $cuerpo.="</script>\n";
                                }
                                $cuerpo.="</div>\n";
//                                $cuerpo.="</td>\n";
//                                $cuerpo.="<td class=\"col-lg-3\">&nbsp;</td>\n";
//                                $cuerpo.="<td class=\"col-lg-3\">&nbsp;</td>\n";
//                                $cuerpo.="</tr>\n";
                            }
                            $cuerpo.="</p>\n";
//                            $cuerpo.="<tr>\n";
//                            $cuerpo.="<td class=\"\" align=\"center\" colspan=\"4\">\n";
//                            $cuerpo.="<table>\n"; //class=\"table table-striped table-hover\"
//                            $cuerpo.="<tr>\n";
//                            $cuerpo.="<td><input type=\"button\" class=\"btn btn-primary btn-lg\" value=\"Guardar\" onclick=\"guardar" . ucwords($name) . "()\" tabIndex=\"" . ($x + 2) . "\"></td>\n";
//                            $cuerpo.="<td><input type=\"button\"  class=\"btn btn-primary btn-lg\" value=\"Limpiar\" onclick=\"limpia" . ucwords($name) . "()\"></td>\n";
//                            $cuerpo.="<td><input type=\"button\"  class=\"btn btn-primary btn-lg\" value=\"Consultar\" onclick=\"consulta" . ucwords($name) . "()\"></td>\n";
//                            $cuerpo.="<td><input type=\"button\"  class=\"btn btn-primary btn-lg\" value=\"Eliminar\" onclick=\"baja" . ucwords($name) . "()\"></td>\n";
                            $cuerpo.="<p style=\"text-align: center;\">\n";
                            $cuerpo.="<button type=\"button\" class=\"btn btn-primary\" value=\"Guardar\" id=\"btn" . ucwords($name) . "Guardar\" name=\"btn" . ucwords($name) . "Guardar\" onclick=\"guardar" . ucwords($name) . "()\" tabIndex=\"" . ($x + 2) . "\" title=\"Boton para guadar cambios\" data-toggle=\"tooltip\" >Guardar</button>\n";
                            $cuerpo.="<button type=\"button\"  class=\"btn btn-primary\" value=\"Limpiar\" id=\"btn" . ucwords($name) . "Limpiar\" name=\"btn" . ucwords($name) . "Limpiar\" onclick=\"limpia" . ucwords($name) . "()\" title=\"Boton para limpiar y realizar un registro nuevo\" data-toggle=\"tooltip\">Limpiar</button>\n";
                            $cuerpo.="<button type=\"button\"  class=\"btn btn-primary\" value=\"Consultar\" id=\"btn" . ucwords($name) . "Consultar\" name=\"btn" . ucwords($name) . "Consultar\" onclick=\"consulta" . ucwords($name) . "()\" title=\"Boton para consultar informacion\" data-toggle=\"tooltip\">Consultar</button>\n";
                            $cuerpo.="<button type=\"button\"  class=\"btn btn-primary\" value=\"Eliminar\" id=\"btn" . ucwords($name) . "Eliminar\" name=\"btn" . ucwords($name) . "Eliminar\" onclick=\"baja" . ucwords($name) . "()\" title=\"Boton para eliminar el registro actual\" data-toggle=\"tooltip\">Eliminar</button>\n";
                            $cuerpo.="<script>\n";
                            /* $cuerpo.="<?php\n if((\$permisos[\"data\"][0]->registrar=='N') && (\$permisos[\"data\"][0]->modificar=='N'))\n echo \"\$(\\\"#btn".ucwords($name)."Guardar\\\").css(\\\"display\\\",\\\"none\\\");\\n\";\n?>\n"; */
                            /* $cuerpo.="<?php\n if(\$permisos[\"data\"][0]->modificar=='N')\n echo \"\$(\\\"#btn".ucwords($name)."Guardar\\\").css(\\\"display\\\",\\\"none\\\");\\n\";\n?>\n"; */
                            /* $cuerpo.="<?php\n if(\$permisos[\"data\"][0]->eliminar=='N')\n echo \"\$(\\\"#btn".ucwords($name)."Eliminar\\\").css(\\\"display\\\",\\\"none\\\");\\n\";\n?>\n"; */
                            /* $cuerpo.="<?php\n if(\$permisos[\"data\"][0]->consulta=='N')\n echo \"\$(\\\"#btn".ucwords($name)."Consultar\\\").css(\\\"display\\\",\\\"none\\\");\\n\";\n?>\n"; */
                            $cuerpo.="</script>\n";
                            $cuerpo.="</p>\n";
//                            $cuerpo.="</tr>\n";
//                            $cuerpo.="</table>\n";
//                            $cuerpo.="</td>\n";
//                            $cuerpo.="</tr>\n";
//                            $cuerpo.="<tr>\n";
//                            $cuerpo.="<td>&nbsp;</td>\n";
//                            $cuerpo.="<td>&nbsp;</td>\n";
//                            $cuerpo.="<td>&nbsp;</td>\n";
//                            $cuerpo.="<td>&nbsp;</td>\n";
//                            $cuerpo.="</tr>\n";
//
//                            $cuerpo.="</table>\n";

                            $cuerpo.="<div id=\"div" . ucwords($name) . "\" name=\"div" . ucwords($name) . "\" class=\"table-responsive\" width=\"100%\"></div>\n";
                            $cuerpo.="<script>\n";
                            /* $cuerpo.="<?php\n if(\$permisos[\"data\"][0]->consulta=='S')\n echo \"consulta" . ucwords($name) . "();\\n\";\n?>\n"; */

                            $cuerpo.="</script>\n";
                            $cuerpo.="</fieldset>\n";
                            $cuerpo.="</div>\n";

                            $cuerpo.="</div><!--.container -->\n";
                            // $cuerpo.="<script>\n";
                            //$cuerpo.="ajustar(parent.parent.document.getElementById(\"Contenido\"));\n";
                            // $cuerpo.="</script>\n";
                            $cuerpo.="</body>\n";
                            $cuerpo.="</html>\n";

//                            $cuerpo.="<?php\n";
//                            $cuerpo.="}else{\n";
//                            $cuerpo.="echo \"Sin Permiso para accesar a este formulario\";\n";
//                            $cuerpo.="}\n";
                            /* $cuerpo.="?>\n"; */
                            fwrite($dto, $cuerpo);
                        } else {
                            $logger->w_onError("EL ARCHIVO YA EXISTE NO SE PUEDE REESCRIBIR " . ROOT . "vistas" . DS . $name . DS . "frm" . ucwords($name) . "View.php");
                        }
                    } else {
                        $logger->w_onError("EL DIRECTORIO NO SE LOGRO CREAR");
                    }
                }

//                break;
            }
        }

        $proveedor->free_result($proveedor->stmt);
        $proveedor->close();
    }

    private function existeArchivo($NomArchivo) {
        if (file_exists($NomArchivo)) {
            return true;
        } else {
            return false;
        }
    }

    private function creaDirectorio($NomDirectorio) {
        $VectorDirectorio = preg_split("[" . DS . "]", $NomDirectorio);
        $ruta = "";
        foreach ($VectorDirectorio as $Carpeta) {
            if ($Carpeta != "." && trim($Carpeta) != "") {
                if (DEFECTO_SO == "windows") {
                    if ($ruta == "") {
                        $ruta = $Carpeta;
                    } else {
                        $ruta = $ruta . "" . DS . "" . $Carpeta;
                    }
                } else {
                    $ruta = $ruta . "/" . $Carpeta;
                }

                if (!$this->existeDirectorio($ruta)) {
                    mkdir($ruta, 0777);
                }
            }
        }
    }

    private function existeDirectorio($NomDirectorio) {
        if (is_dir($NomDirectorio) == true)
            return true;

        return false;
    }

    private function funcionExiste($function) {
        if (function_exists($function))
            return true;
        else
            return false;
    }

}

?>