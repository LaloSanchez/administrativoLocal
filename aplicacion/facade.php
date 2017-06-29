<?php

error_reporting(E_ALL);
error_reporting(-1);
include_once(ROOT . DS . "tribunal" . DS . "connect" . DS . "Proveedor.Class.php");
include_once(ROOT . DS . "tribunal" . DS . "logger" . DS . "Logger.Class.php");

class Facade {

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
            $logger = new Logger("/../../logs/", "Facades");
            $logger->w_onError("**********COMIENZA EL PROGRAMA CON LA CREACION DEL FACADE**********");
            for ($i = 0; $i < count($tablas); $i++) {
                $campos = "";
                $contador = 0;
                $proveedor->execute("desc " . $tablas[$i]);
                if (!$proveedor->error()) {
                    if ($proveedor->rows($proveedor->stmt) > 0) {
                        while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
                            $campos[$contador] = array("field" => $row[0], "primary" => $row[3], "type" => $row[1]);
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

                    $facade = new Facade();
                    $facade->creaDirectorio(ROOT . "fachadas" . DS . DEFECTO_BD . DS . $name);
                    $logger->w_onError("CREAMOS EL DIRECTORIO: " . ROOT . "fachadas" . DS . DEFECTO_BD . DS . $name);
                    if ($facade->existeDirectorio(ROOT . "fachadas" . DS . DEFECTO_BD . DS . $name)) {
                        if (!$facade->existeArchivo(ROOT . "fachadas" . DS . DEFECTO_BD . DS . $name . DS . ucwords($name) . "Facade.Class.php")) {
                            $dto = fopen(ROOT . "fachadas" . DS . DEFECTO_BD . DS . $name . DS . ucwords($name) . "Facade.Class.php", "w");
                            $logger->w_onError("CREAMOS EL ARCHIVO: " . ROOT . "fachadas" . DS . DEFECTO_BD . DS . $name . DS . ucwords($name) . "Facade.Class.php");
                            $cuerpo = "<?php\n\n";
                            $cuerpo.="/*\n";
                            $cuerpo.="*************************************************\n";
                            $cuerpo.="*FRAMEWORK V2.0.0 (http://www.pjedomex.gob.mx)\n";
                            $cuerpo.="*Copyright 2009-2016 FACADES\n";
                            $cuerpo.="* Licensed under the MIT license \n";
                            $cuerpo.="* Autor: *\n";
                            $cuerpo.="* Departamento de Desarrollo de Software\n";
                            $cuerpo.="* Subdireccion de Ingenieria de Software\n";
                            $cuerpo.="* Direccion de Teclogias de Informacion\n";
                            $cuerpo.="* Poder Judicial del Estado de Mexico\n";
                            $cuerpo.="*************************************************\n";
                            $cuerpo.="*/\n\n";
                            $cuerpo.="session_start();\n";

//                            $cuerpo.="include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS . "modelos" . DS . DEFECTO_BD . DS . "dto" . DS . $name . DS . ucwords($name) . "DTO.Class.php\");\n";
                            $cuerpo.= "include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS . "controladores" . DS . DEFECTO_BD . DS . $name . DS . ucwords($name) . "Controller.Class.php\");\n";
                            $cuerpo.= "include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS . "tribunal" . DS . "connect" . DS . "Proveedor.Class.php\");\n";
//                            $cuerpo.= "include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS . "tribunal" . DS . "dtotojson" . DS . "DtoToJson.Class.php\");\n";
                            $cuerpo.= "include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS . "tribunal" . DS . "json" . DS . "JsonEncod.Class.php\");\n";
                            $cuerpo.= "include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS . "tribunal" . DS . "json" . DS . "JsonDecod.Class.php\");\n";
                            //$cuerpo.= "include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . "webservice/cliente/" . DS . "permisos" . DS . "PermisosCliente.php\");\n\n";
                            $cuerpo.= "class " . ucwords($name) . "Facade {\n";
                            $cuerpo.= "private \$proveedor;\n";
                            $cuerpo.= "public \$ap = array(\"A\", \"B\", \"C\", \"D\", \"E\", \"F\", \"G\", \"H\", \"I\", \"J\", \"K\", \"L\", \"M\", \"N\", \"O\", \"P\", \"Q\", \"R\", \"S\", \"T\", \"U\", \"V\", \"W\", \"X\", \"Y\", \"Z\", \"AA\", \"AB\");\n";
                            $cuerpo.= "\n";
                            $cuerpo.= "\n";

                            $cuerpo.="public function __construct() {\n";
                            $cuerpo.="}\n";

                            $cuerpo.="public function select" . ucwords($name) . "($" . ucwords($name) . "Array, \$limit = null, \$draw = null, \$o = null, \$s = null){\n";
                            $cuerpo.="\$campos=\"\";\n";
                            $cuerpo.="\$like = \"\";\n";
                            $cuerpo.="\$contador = 1;\n";
                            $cuerpo.="\$join = \"\";\n";
                            $cuerpo.="\$orderBy = \"\";\n";
                            $cuerpo.="\$search = \"\";\n";
                            $cuerpo.="\$index = 0;\n";
                            $cuerpo.="foreach ($" . ucwords($name) . "Array as \$key => \$value) {\n";
//                            $cuerpo.="\$campos.=\$key . \",\";\n";
                            $cuerpo.="if ((\$value != \"\") && (\$value != null)){\n";
                            $cuerpo.="\$where[\"where\"][\$key] = \$value;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="if (\$o != null) {\n";
                            $cuerpo.="if (\$o[\"column\"] == \$index) {\n";
                            $cuerpo.="\$orderBy = \$this->ap[0] . \".\" . \$key . \" \" . \$o[\"dir\"];\n";
                            $cuerpo.="}\n";
                            $cuerpo.="}\n";

                            $cuerpo.="\$campos.=\$this->ap[0] . \".\" . \$key . \",\";\n";
                            $cuerpo.="\$like.=\$this->ap[0] . \".\" . \$key . \",\";\n";
                            $cuerpo.="\$index++;\n";

                            $cuerpo.="}\n";
                            $cuerpo.="\$campos = substr(\$campos, 0, -1);\n";
                            $cuerpo.="\$like = substr(\$campos, 0, -1);\n";
                            $cuerpo.= "\n";


                            $cuerpo.= "if (\$s != null) {\n";
                            $cuerpo.= "if (\$s[\"value\"] != \"\") {\n";
                            $cuerpo.= "\$search = \" concat_ws(' ',\" . \$like . \") like '%\" . \$s[\"value\"] . \"%' \";\n";
                            $cuerpo.= "}\n";
                            $cuerpo.= "}\n";

                            $cuerpo.="\$d = array(\"campos\" => \$campos,\"limit\" => \$limit, \"order\" => \$orderBy, \"search\" => \$search);\n";
                            $cuerpo.="\$d = array_merge(\$d, \$where);\n";
                            $cuerpo.="\$sql = array();\n";

                            $cuerpo.="/*\n";
                            $cuerpo.="* Ejemplo del campo sql\n";
                            $cuerpo.="*campos: los campos a mostrar o a insertar en la base de datos\n";
                            $cuerpo.="*values: los valores de los campos que se definieron en el apartado de campos y deben de corresponder en el orden y la camtidad\n";
                            $cuerpo.="*tabla: Nombre de la tabla a en la cual se realizara la accion buscada\n";
                            $cuerpo.="*where: Condicion para la sentencia sql\n";
                            $cuerpo.="*groups: los campos con los cuales se realizara el agrupamiento que se realizara en el query\n";
                            $cuerpo.="*orders: Los campos y el tipo de ordenamiento que se realizara en el query\n";
                            $cuerpo.="*\n";
                            $cuerpo.="* \$sql = array(\"campos\" => \"campo1,campo2,campo3,campo N\", \"values\" => \"valor1,valor2,valor3,valor N\", \"tablas\" => \"" . DEFECTO_PREFIJO . $name . "\", \"where\" => \"\", \"groups\" => \"\", \"orders\" => \"\");\n";
                            $cuerpo.="* \\";
                            $cuerpo.="*/\n";

                            $cuerpo.="\$param = array(\"tabla\" => \"" . DEFECTO_PREFIJO . $name . " as \".\$this->ap[0].\"\", \"d\" => \$d, \"tmpSql\" => \$sql, \"proveedor\" => null);\n";

                            $cuerpo.="$" . ucwords($name) . "Controller = new " . ucwords($name) . "Controller();\n";
                            $cuerpo.="$" . ucwords($name) . "Array = $" . ucwords($name) . "Controller->select" . ucwords($name) . "(\$param);\n";
                            $cuerpo.="if(($" . ucwords($name) . "Array!=\"\") && (sizeof($" . ucwords($name) . "Array)>0)){\n";
                            $cuerpo.="if ((\$s != null) && (\$o != null) && (\$limit != null) && (\$draw != null)) {\n";
                            $cuerpo.="\$d = array(\"campos\" => \"\");\n";
                            $cuerpo.="\$d = array_merge(\$d, array());\n";
                            $cuerpo.="\n";
                            $cuerpo.="\n";

                            $cuerpo.="\$strWhere = \" \";\n";
                            $cuerpo.="if(sizeof(\$where)>0){\n";
                            $cuerpo.="\$strWhere .= \" Where \";\n";
                            $cuerpo.="foreach(\$where as \$key => \$value){\n";
                            $cuerpo.="\$strWhere .= \" \".\$key.\"='\".\$value.\"' And\";\n";
                            $cuerpo.="}\n";
                            $cuerpo.="\$strWhere = trim(\$strWhere,\"And\");\n";
                            $cuerpo.="}\n";


                            $cuerpo.="\$sql = array(\"campos\" => \"count(*) as Total\", \"values\" => \"\", \"tablas\" => \"" . DEFECTO_PREFIJO . $name . " as \".\$this->ap[0].\"\", \"where\" => \$strWhere, \"groups\" => \"\", \"orders\" => \"\");\n";

                            $cuerpo.="\$param = array(\"tabla\" => \"" . DEFECTO_PREFIJO . $name . "\", \"d\" => \$d, \"tmpSql\" => \$sql, \"proveedor\" => null);\n";
                            $cuerpo.="\$arrayTot = \$genericController->selectGeneric(\$param);\n";

                            $cuerpo.="\$data = array();\n";
                            $cuerpo.="for (\$index = 0; \$index < sizeof(@\$array[\"data\"]); \$index++) {\n";
                            $cuerpo.="\$registro = array();\n";
                            $cuerpo.="foreach (\$array[\"data\"][\$index] as \$key => \$value) {\n";
                            $cuerpo.="\$registro[] = \$value;\n";
                            $cuerpo.="}\n";
                            $cuerpo.="\$data[] = \$registro;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="\$output = array(\n";
                            $cuerpo.="\"draw\" => \$draw,\n";
                            $cuerpo.="\"recordsTotal\" => (int) \$array[\"totalCount\"],\n";
                            $cuerpo.="\"recordsFiltered\" => (int) \$arrayTot[\"data\"][0][\"Total\"], //,\n";
                            $cuerpo.="\"start\" => \$limit[\"pag\"],\n";
                            $cuerpo.="\"length\" => \$limit[\"max\"],\n";
                            $cuerpo.="\"data\" => \$data);\n";

                            $cuerpo.="return \$output;";
                            $cuerpo.="}else{\n";
                            $cuerpo.="return $" . ucwords($name) . "Array;\n";
                            $cuerpo.="}\n";
                            $cuerpo.="}\n";
//                            $cuerpo.="\$jsonDto = new Encode_JSON();\n";
                            $cuerpo.="return array(\"totalCount\"=>\"0\",\"text\"=>\"SIN RESULTADOS A MOSTRAR\");\n";
                            $cuerpo.="}\n";
                            $cuerpo.="public function insert" . ucwords($name) . "($" . ucwords($name) . "Array){\n";

                            $cuerpo.="\$d = array();\n";
                            $cuerpo.="\$campos=\"\";\n";
                            $cuerpo.="foreach ($" . ucwords($name) . "Array as \$key => \$value) {\n";
                            $cuerpo.="\$campos.=\$key . \",\";\n";
                            $cuerpo.="if ((strtoupper(\$key) == \"FECHAREGISTRO\") || (strtoupper(\$key) == \"FECHAACTUALIZACION\")) {\n";
                            $cuerpo.="\$d[\"values\"][\$key] = \"now()\";\n";
                            $cuerpo.="} else if ((strtoupper(\$key) == \"ACTIVO\")) {\n";
                            $cuerpo.="\$d[\"values\"][\$key] = \"S\";\n";
                            $cuerpo.="}else {\n";
                            $cuerpo.="if ((\$value != \"\") && (\$value != null))\n";
                            $cuerpo.="\$d[\"values\"][\$key] = \$value;\n";
                            $cuerpo.="}\n";
                            $cuerpo.="}\n";

                            $cuerpo.="/*\n";
                            $cuerpo.="* Ejemplo del campo sql\n";
                            $cuerpo.="*campos: los campos a mostrar o a insertar en la base de datos\n";
                            $cuerpo.="*values: los valores de los campos que se definieron en el apartado de campos y deben de corresponder en el orden y la camtidad\n";
                            $cuerpo.="*tabla: Nombre de la tabla a en la cual se realizara la accion buscada\n";
                            $cuerpo.="*where: Condicion para la sentencia sql\n";
                            $cuerpo.="*groups: los campos con los cuales se realizara el agrupamiento que se realizara en el query\n";
                            $cuerpo.="*orders: Los campos y el tipo de ordenamiento que se realizara en el query\n";
                            $cuerpo.="*\n";
                            $cuerpo.="* \$sql = array(\"campos\" => \"campo1,campo2,campo3,campo N\", \"values\" => \"valor1,valor2,valor3,valor N\", \"tablas\" => \"" . DEFECTO_PREFIJO . $name . "\", \"where\" => \"\", \"groups\" => \"\", \"orders\" => \"\");\n";
                            $cuerpo.="* \\";
                            $cuerpo.="*/\n";


                            $cuerpo.="\$sql = array();\n";
                            $cuerpo.="\$param = array(\"tabla\" => \"" . DEFECTO_PREFIJO . $name . "\", \"d\" => \$d, \"tmpSql\" => \$sql, \"proveedor\" => null);\n";
                            $cuerpo.="$" . ucwords($name) . "Controller = new " . ucwords($name) . "Controller();\n";
                            $cuerpo.="$" . ucwords($name) . "Array = $" . ucwords($name) . "Controller->insert" . ucwords($name) . "(\$param);\n";
                            $cuerpo.="if($" . ucwords($name) . "Array!=\"\"){\n";
//                            $cuerpo.="\$dtoToJson = new DtoToJson($" . ucwords($name) . "Dto);\n";
//                            $cuerpo.="return \$dtoToJson->toJson(\"REGISTRO REALIZADO DE FORMA CORRECTA\");\n";
                            $cuerpo.="return $" . ucwords($name) . "Array;\n";
                            $cuerpo.="}\n";
//                            $cuerpo.="\$jsonDto = new Encode_JSON();\n";
                            $cuerpo.="return array(\"totalCount\"=>\"0\",\"text\"=>\"OCURRIO UN ERROR AL REALIZAR EL REGISTRO\");\n";
                            $cuerpo.="}\n";
                            $cuerpo.="public function update" . ucwords($name) . "($" . ucwords($name) . "Array){\n";

                            $cuerpo.="\$d = array();\n";
                            $cuerpo.="foreach ($" . ucwords($name) . "Array as \$key => \$value) {\n";
                            $cuerpo.="if ((strtoupper(\$key) == \"FECHAACTUALIZACION\")) {\n";
                            $cuerpo.="\$d[\"values\"][\$key] = \"now()\";\n";
                            $cuerpo.="} else if(strtoupper(\$key) != \"FECHAREGISTRO\"){\n";
                            $cuerpo.="if ((\$value != \"\") && (\$value != null))\n";
                            $cuerpo.="\$d[\"values\"][\$key] = \$value;\n";
                            $cuerpo.="}\n";
                            $cuerpo.="}\n";

                            for ($x = 0; $x < count($campos); $x++) {
                                if ($campos[$x]["primary"] == "PRI") {
                                    $primary = $campos[$x]["field"];
                                }
                            }

                            $cuerpo.="\$d = array_merge(\$d,array(\"where\" => array(\"" . $primary . "\" => $" . ucwords($name) . "Array[\"" . $primary . "\"])));\n";
                            $cuerpo.="\$sql = array();\n";

                            $cuerpo.="/*\n";
                            $cuerpo.="* Ejemplo del campo sql\n";
                            $cuerpo.="*campos: los campos a mostrar o a insertar en la base de datos\n";
                            $cuerpo.="*values: los valores de los campos que se definieron en el apartado de campos y deben de corresponder en el orden y la camtidad\n";
                            $cuerpo.="*tabla: Nombre de la tabla a en la cual se realizara la accion buscada\n";
                            $cuerpo.="*where: Condicion para la sentencia sql\n";
                            $cuerpo.="*groups: los campos con los cuales se realizara el agrupamiento que se realizara en el query\n";
                            $cuerpo.="*orders: Los campos y el tipo de ordenamiento que se realizara en el query\n";
                            $cuerpo.="*\n";
                            $cuerpo.="* \$sql = array(\"campos\" => \"campo1,campo2,campo3,campo N\", \"values\" => \"valor1,valor2,valor3,valor N\", \"tablas\" => \"" . DEFECTO_PREFIJO . $name . "\", \"where\" => \"\", \"groups\" => \"\", \"orders\" => \"\");\n";
                            $cuerpo.="* \\";
                            $cuerpo.="*/\n";

                            $cuerpo.="\$param = array(\"tabla\" => \"" . DEFECTO_PREFIJO . $name . "\", \"d\" => \$d, \"tmpSql\" => \$sql, \"proveedor\" => null);\n";


                            $cuerpo.="$" . ucwords($name) . "Controller = new " . ucwords($name) . "Controller();\n";
                            $cuerpo.="$" . ucwords($name) . "Array = $" . ucwords($name) . "Controller->update" . ucwords($name) . "(\$param);\n";
                            $cuerpo.="if($" . ucwords($name) . "Array!=\"\"){\n";
//                            $cuerpo.="\$dtoToJson = new DtoToJson($" . ucwords($name) . "Dto);\n";
//                            $cuerpo.="return \$dtoToJson->toJson(\"REGISTRO ACTUALIZADO\");\n";
                            $cuerpo.="return $" . ucwords($name) . "Array;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="return array(\"totalCount\"=>\"0\",\"text\"=>\"OCURRIO UN ERROR AL REALIZAR LA ACTUALIZACION\");\n";
                            $cuerpo.="}\n";
                            $cuerpo.="public function delete" . ucwords($name) . "($" . ucwords($name) . "Array){\n";
                            $cuerpo.="\$d = array();\n";
                            $cuerpo.="foreach ($" . ucwords($name) . "Array as \$key => \$value) {\n";
                            $cuerpo.="if ((strtoupper(\$key) == \"FECHAACTUALIZACION\")) {\n";
                            $cuerpo.="\$d[\"values\"][\$key] = \"now()\";\n";
                            $cuerpo.="} else if(strtoupper(\$key) == \"ACTIVO\"){\n";
                            $cuerpo.="\$d[\"values\"][\$key] = \"N\";\n";
                            $cuerpo.="}\n";
                            $cuerpo.="}\n";

                            for ($x = 0; $x < count($campos); $x++) {
                                if ($campos[$x]["primary"] == "PRI") {
                                    $primary = $campos[$x]["field"];
                                }
                            }

                            $cuerpo.="\$d = array_merge(\$d,array(\"where\" => array(\"" . $primary . "\" => $" . ucwords($name) . "Array[\"" . $primary . "\"])));\n";
                            $cuerpo.="\$sql = array();\n";

                            $cuerpo.="/*\n";
                            $cuerpo.="* Ejemplo del campo sql\n";
                            $cuerpo.="*campos: los campos a mostrar o a insertar en la base de datos\n";
                            $cuerpo.="*values: los valores de los campos que se definieron en el apartado de campos y deben de corresponder en el orden y la camtidad\n";
                            $cuerpo.="*tabla: Nombre de la tabla a en la cual se realizara la accion buscada\n";
                            $cuerpo.="*where: Condicion para la sentencia sql\n";
                            $cuerpo.="*groups: los campos con los cuales se realizara el agrupamiento que se realizara en el query\n";
                            $cuerpo.="*orders: Los campos y el tipo de ordenamiento que se realizara en el query\n";
                            $cuerpo.="*\n";
                            $cuerpo.="* \$sql = array(\"campos\" => \"campo1,campo2,campo3,campo N\", \"values\" => \"valor1,valor2,valor3,valor N\", \"tablas\" => \"" . DEFECTO_PREFIJO . $name . "\", \"where\" => \"\", \"groups\" => \"\", \"orders\" => \"\");\n";
                            $cuerpo.="* \\";
                            $cuerpo.="*/\n";

                            $cuerpo.="\$param = array(\"tabla\" => \"" . DEFECTO_PREFIJO . $name . "\", \"d\" => \$d, \"tmpSql\" => \$sql, \"proveedor\" => null);\n";

                            $cuerpo.="$" . ucwords($name) . "Controller = new " . ucwords($name) . "Controller();\n";
                            $cuerpo.="$" . ucwords($name) . "Array = $" . ucwords($name) . "Controller->delete" . ucwords($name) . "(\$param);\n";
                            $cuerpo.="if($" . ucwords($name) . "Array!=\"\"){\n";
                            $cuerpo.="return $" . ucwords($name) . "Array;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="return array(\"totalCount\"=>\"0\",\"text\"=>\"OCURRIO UN ERROR AL REALIZAR EL LA BAJA\");\n";
                            $cuerpo.="}\n";

                            $cuerpo.="public function date(\$value) {\n";
                            $cuerpo.="\$patron = \"/^(0[1-9]|1[0-9]|2[0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}$/\";\n";
                            $cuerpo.="return preg_match(\$patron, (string) \$value);\n";
                            $cuerpo.="}\n";

                            $cuerpo.="public function dateTime(\$value) {\n";
                            $cuerpo.="\$patron = \"/^(0[1-9]|1[0-9]|2[0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}\ (0[0-9]|1[0-9]|2[0-4])\:(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])\:(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])$/\";\n";
                            $cuerpo.="return preg_match(\$patron, (string) \$value);\n";
                            $cuerpo.="}\n";

                            $cuerpo.="public function esFecha(\$text) {\n";
                            $cuerpo.="if (\$this->date(\$text)) {\n";
                            $cuerpo.="\$fecha = explode(\"/\", \$text);\n";
                            $cuerpo.="return \$fecha[2] . \"-\" . \$fecha[1] . \"-\" . \$fecha[0];\n";
                            $cuerpo.="} else if (\$this->dateTime(\$text)) {\n";
                            $cuerpo.="\$fechaHora = explode(\" \", \$text);\n";
                            $cuerpo.="\$fecha = explode(\"/\", \$fechaHora[0]);\n";
                            $cuerpo.="return \$fecha[2] . \"-\" . \$fecha[1] . \"-\" . \$fecha[0] . \" \" . \$fechaHora[1];\n";
                            $cuerpo.="}\n";
                            $cuerpo.="return \$text;\n";
                            $cuerpo.="}\n";

                            $cuerpo.= "}\n";
                            $cuerpo.="\n";
                            $cuerpo.="\n";
                            $cuerpo.="\n";

                            $cuerpo.="\$campos=\"\";\n";

                            $cuerpo.="$" . $name . "Array = Array();\n\n";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="@$" . $name . "Array[0][\"" . $campos[$x]["field"] . "\"]=\$_POST[\"" . $campos[$x]["field"] . "\"];\n";
//                                $cuerpo.="@\$campos.=\"" . $campos[$x]["field"] . ",\";\n";

                                if ($campos[$x]["primary"] == "PRI") {
                                    $primary = $campos[$x]["field"];
                                }
                            }

                            $cuerpo.="if (isset(\$_POST[\"start\"]) && isset(\$_POST[\"length\"])) {\n";
                            $cuerpo.="@\$pag = trim(\$_POST[\"start\"]);\n";
                            $cuerpo.="@\$maxRows = trim(\$_POST[\"length\"]);\n";
                            $cuerpo.="\$limit = array(\"max\" => \$maxRows, \"pag\" => \$pag);\n";
                            $cuerpo.="} else {\n";
                            $cuerpo.="\$limit = null;\n";
                            $cuerpo.="}\n";
                            $cuerpo.="\n";
                            $cuerpo.="if (isset(\$_POST[\"order\"])) {\n";
                            $cuerpo.="@\$order[\"column\"] = trim(\$_POST[\"order\"][0][\"column\"]);\n";
                            $cuerpo.="@\$order[\"dir\"] = trim(\$_POST[\"order\"][0][\"dir\"]);\n";
                            $cuerpo.="} else {\n";
                            $cuerpo.="\$order = \"\";\n";
                            $cuerpo.="}\n";
                            $cuerpo.="\n";
                            $cuerpo.="if (isset(\$_POST[\"search\"])) {\n";
                            $cuerpo.="@\$search[\"value\"] = trim(\$_POST[\"search\"][\"value\"]);\n";
                            $cuerpo.="} else {\n";
                            $cuerpo.="\$search = \"\";\n";
                            $cuerpo.="}\n";
                            $cuerpo.="\n";
                            $cuerpo.="\n";
                            $cuerpo.="@\$accion=\$_POST[\"accion\"];\n\n";
                            $cuerpo.="$" . $name . "Facade = new " . ucwords($name) . "Facade();\n";

                            $cuerpo.="\nif( (\$accion==\"guardar\") && ($" . $name . "Array[0][\"" . $primary . "\"]==\"\") ){\n";

                            $cuerpo.="$" . $name . "Array=$" . $name . "Facade->insert" . ucwords($name) . "(\$" . $name . "Array[0]);\n";
                            $cuerpo.="echo json_encode($" . $name . "Array);\n";
                            $cuerpo.="} else if((\$accion==\"guardar\") && ($" . $name . "Array[0][\"" . $primary . "\"]!=\"\")){\n";

                            $cuerpo.="$" . $name . "Array=$" . $name . "Facade->update" . ucwords($name) . "(\$" . $name . "Array[0]);\n";
                            $cuerpo.="echo json_encode($" . $name . "Array);\n";

                            $cuerpo.="} else if(\$accion==\"consultar\"){\n";
                            $cuerpo.="$" . $name . "Array=$" . $name . "Facade->select" . ucwords($name) . "(\$" . $name . "Array[0], \$limit, \$draw, \$order, \$search);\n";
                            $cuerpo.="echo json_encode($" . $name . "Array);\n";
                            $cuerpo.="} else if( (\$accion==\"baja\") && ($" . $name . "Array[0][\"" . $primary . "\"]!=\"\") ){\n";
                            $cuerpo.="$" . $name . "Array=$" . $name . "Facade->delete" . ucwords($name) . "(\$" . $name . "Array[0]);\n";
                            $cuerpo.="echo json_encode($" . $name . "Array);\n";

                            $cuerpo.="} else if( (\$accion==\"seleccionar\") && ($" . $name . "Array[0][\"" . $primary . "\"]!=\"\") ){\n";
                            $cuerpo.="$" . $name . "Array=$" . $name . "Facade->select" . ucwords($name) . "(\$" . $name . "Array[0]);\n";
                            $cuerpo.="echo json_encode($" . $name . "Array);\n";
                            $cuerpo.="}\n\n\n?>";
                            fwrite($dto, $cuerpo);
                        } else {
                            $logger->w_onError("EL ARCHIVO YA EXISTE NO SE PUEDE REESCRIBIR " . ROOT . "fachadas" . DS . $name . DS . ucwords($name) . "Facade.Class.php");
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
