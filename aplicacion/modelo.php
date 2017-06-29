<?php

error_reporting(E_ALL);
error_reporting(-1);
include_once(ROOT . DS . "tribunal" . DS . "connect" . DS . "Proveedor.Class.php");
include_once(ROOT . DS . "tribunal" . DS . "logger" . DS . "Logger.Class.php");

class Modelo {

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
            $logger = new Logger("/../../logs/", "Models");
            $logger->w_onError("**********COMIENZA EL PROGRAMA CON LA CREACION DEL MODELO**********");
            for ($i = 0; $i < count($tablas); $i++) {
                $campos = "";
                $contador = 0;
                $proveedor->execute("desc " . $tablas[$i]);
                if (!$proveedor->error()) {
                    if ($proveedor->rows($proveedor->stmt) > 0) {
                        while ($row = $proveedor->fetch_rows($proveedor->stmt, 0)) {
                            $campos[$contador] = array("field" => $row[0], "primary" => $row[3]);
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


                    $modelo = new Modelo();
                    /*
                     * Este pedazo de codigo lo comentamos porque ya no se utilizaran los dtos el modelo para utilizar menos archivos y que el proceso
                     * sea mas facil de entender y realizar para los desarrolladores
                     */
                    
                    /*
                    $modelo->creaDirectorio(ROOT . "modelos" . DS . DEFECTO_BD.DS."dto" . DS . $name);
                    $logger->w_onError("CREAMOS EL DIRECTORIO: " . ROOT . "modelos" . DS . DEFECTO_BD.DS."dto" . DS . $name);

                    if ($modelo->existeDirectorio(ROOT . "modelos" . DS . DEFECTO_BD.DS. "dto" . DS . $name)) {
                        if (!$modelo->existeArchivo(ROOT . "modelos" . DS . DEFECTO_BD.DS. "dto" . DS . $name . DS . ucwords($name) . "DTO.Class.php")) {
                            $dto = fopen(ROOT . "modelos" . DS . DEFECTO_BD.DS. "dto" . DS . $name . DS . ucwords($name) . "DTO.Class.php", "w");
                            $logger->w_onError("CREAMOS EL ARCHIVO: " . ROOT . "modelos" . DS. DEFECTO_BD.DS . "dto" . DS . $name . DS . ucwords($name) . "DTO.Class.php");
                            $cuerpo = "<?php\n ";
                            $cuerpo.="/*\n";
                            $cuerpo.="*************************************************\n";
                            $cuerpo.="*FRAMEWORK V2.0.0 (http://www.pjedomex.gob.mx)\n";
                            $cuerpo.="*Copyright 2009-2016 DTOS\n";
                            $cuerpo.="* Licensed under the MIT license \n";
                            $cuerpo.="* Autor: *\n";
                            $cuerpo.="* Departamento de Desarrollo de Software\n";
                            $cuerpo.="* Subdireccion de Ingenieria de Software\n";
                            $cuerpo.="* Direccion de Teclogias de Informacion\n";
                            $cuerpo.="* Poder Judicial del Estado de Mexico\n";
                            $cuerpo.="*************************************************\n";
                            $cuerpo.="*//*\n\n";
                            $cuerpo.="class " . ucwords($name) . "DTO {\n";
                            for ($x = 0; $x < count($campos); $x++) {
//                                echo $campos[$x]["field"];
                                $cuerpo .= "    private $" . $campos[$x]["field"] . ";\n";
                            }

                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo .= "    public function get" . ucwords($campos[$x]["field"]) . "(){\n";
                                $cuerpo .= "        return \$this->" . $campos[$x]["field"] . ";\n";
                                $cuerpo .= "    }\n";
                                $cuerpo .= "    public function set" . ucwords($campos[$x]["field"]) . "($" . $campos[$x]["field"] . "){\n";
                                $cuerpo .= "        \$this->" . $campos[$x]["field"] . "=$" . $campos[$x]["field"] . ";\n";
                                $cuerpo .= "    }\n";
                            }

                            $cuerpo .= "    public function toString(){\n";
                            $cuerpo .= "        return array(";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="\"" . $campos[$x]["field"] . "\"=>\$this->" . $campos[$x]["field"] . ",\n";
                            }
                            $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -2);
                            $cuerpo .= ");\n";
                            $cuerpo .= "    }\n";
                            $cuerpo .= "}\n?>";
                            fwrite($dto, $cuerpo);
                        } else {
                            $logger->w_onError("EL ARCHIVO YA EXISTE NO SE PUEDE REESCRIBIR " . ROOT . "modelos" . DS . "dto" . DS . $name . DS . ucwords($name) . "DTO.Class.php");
                        }
                    } else {
                        $logger->w_onError("EL DIRECTORIO NO SE LOGRO CREAR");
                    }*/

                    $logger->w_onError("CREAMOS EL DIRECTORIO: " . ROOT . "modelos" . DS . DEFECTO_BD.DS. "dao" . DS . $name);
                    $modelo->creaDirectorio(ROOT . "modelos" . DS. DEFECTO_BD.DS . "dao" . DS . $name);
                    if ($modelo->existeDirectorio(ROOT . "modelos" . DS . DEFECTO_BD.DS. "dao" . DS . $name)) {
                        if (!$modelo->existeArchivo(ROOT . "modelos" . DS . DEFECTO_BD.DS. "dao" . DS . $name . DS . ucwords($name) . "DAO.Class.php")) {
                            $dto = fopen(ROOT . "modelos" . DS . DEFECTO_BD.DS. "dao" . DS . $name . DS . ucwords($name) . "DAO.Class.php", "w");
                            $logger->w_onError("CREAMOS EL ARCHIVO: " . ROOT . "modelos" . DS . DEFECTO_BD.DS. "dao" . DS . $name . DS . ucwords($name) . "DAO.Class.php");
                            $cuerpo = "<?php\n ";
                            $cuerpo.="/*\n";
                            $cuerpo.="*************************************************\n";
                            $cuerpo.="*FRAMEWORK V1.0.0 (http://www.pjedomex.gob.mx)\n";
                            $cuerpo.="*Copyright 2009-2015 DAOS\n";
                            $cuerpo.="* Licensed under the MIT license \n";
                            $cuerpo.="* Autor: *\n";
                            $cuerpo.="* Departamento de Desarrollo de Software\n";
                            $cuerpo.="* Subdireccion de Ingenieria de Software\n";
                            $cuerpo.="* Direccion de Teclogias de Informacion\n";
                            $cuerpo.="* Poder Judicial del Estado de Mexico\n";
                            $cuerpo.="*************************************************\n";
                            $cuerpo.="*/\n\n";
//                            $cuerpo.= "include_once(\"../../../aplicacion/modelo.php\");\n";
//                            $cuerpo.= "include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS .".." . DS . "modelos" . DS . DEFECTO_BD.DS. "dto" . DS . $name . DS . ucwords($name) . "DTO.Class.php\");\n";
                            $cuerpo.= "include_once(dirname(__FILE__).\"" . DS . ".." . DS . ".." . DS . ".." . DS .".." . DS . "tribunal" . DS . "connect" . DS . "Proveedor.Class.php\");\n";
                            //include_once(ROOT."/tribunal/connect/Proveedor.Class.php");
                            $cuerpo.= "class " . ucwords($name) . "DAO{\n";
                            $cuerpo.=" protected \$_proveedor;\n";
                            $cuerpo.="public function __construct(\$gestor = \"".DEFECTO_GESTOR."\", \$bd = \"".DEFECTO_BD."\") {\n";
                            $cuerpo.="\$this->_proveedor = new Proveedor('" . DEFECTO_GESTOR . "', '" . DEFECTO_BD . "');\n";
                            $cuerpo.="}\n";
                            $cuerpo.="public function _conexion(){\n";
                            $cuerpo.="\$this->_proveedor->connect();\n";
                            $cuerpo.="}\n";

                            $cuerpo.="public function insert" . ucwords($name) . "($" . $name . "Array,\$proveedor=null){\n";
                            $cuerpo.="\$tmp = \"\";\n";
                            $cuerpo.="\$contador = 0;\n";
                            $cuerpo.="if (\$proveedor == null) {\n";
                            $cuerpo.="\$this->_conexion(null);\n";
                            $cuerpo.="//\$this->_proveedor->connect();\n";
                            $cuerpo.="} else if (\$proveedor != null) {\n";
                            $cuerpo.="\$this->_proveedor = \$proveedor;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="\$sql=\"INSERT INTO " . $tablas[$i] . "(\";\n";
                            $primary = "";
//                            for ($x = 0; $x < count($campos); $x++) {
//                                if ($campos[$x]["primary"] == "PRI") {
//                                    $primary = $campos[$x]["field"];
//                                }else{
//                                    $cuerpo.=$campos[$x]["field"] . ",";
//                                }
//                            }

                            for ($x = 0; $x < count($campos); $x++) {
                                if ((strtoupper($campos[$x]["field"]) == "FECHAREGISTRO") || (strtoupper($campos[$x]["field"]) == "FECHAACTUALIZACION")) {
                                    
                                } else {
                                    $cuerpo.="if(";
                                    $cuerpo.="$" . $name . "Array[\"" . ucwords($campos[$x]["field"]) . "\"]!=\"\"){\n";
                                    $cuerpo.="\$sql.=\"" . $campos[$x]["field"] . "\";\n";
                                    if ((count($campos) - 1) > $x) {
                                        $cuerpo.="if(";
                                        for ($y = ($x + 1); $y < count($campos); $y++) {
                                            if ((strtoupper($campos[$y]["field"]) == "FECHAREGISTRO") || (strtoupper($campos[$y]["field"]) == "FECHAACTUALIZACION")) {
                                                
                                            } else {
                                                $cuerpo.="($" . $name . "Array[\"" . ucwords($campos[$y]["field"]) . "\"]!=\"\") ||";
                                            }
                                        }
                                        if (substr($cuerpo, (count($cuerpo)) - 3, 3) == "||") {
                                            $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -2) . "){\n";
                                            $cuerpo.="\$sql.=\",\";\n";
                                            $cuerpo.="}\n";
                                        } else if (substr($cuerpo, (count($cuerpo)) - 3, 3) == "f(") {
                                            $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -3);
                                        }

                                        if ($campos[$x]["primary"] == "PRI") {
                                            $primary = $campos[$x]["field"];
                                        }
                                    }
                                    $cuerpo.="}\n";
                                }
                            }

                            for ($x = 0; $x < count($campos); $x++) {
                                if ((strtoupper($campos[$x]["field"]) == "FECHAREGISTRO") || (strtoupper($campos[$x]["field"]) == "FECHAACTUALIZACION")) {
                                    $cuerpo.="\$sql.=\"," . $campos[$x]["field"] . "\";\n";
//                                    if ((count($campos) - 1) > $x) {
//                                        $cuerpo.="if(";
//                                        for ($y = ($x + 1); $y < count($campos); $y++) {
//                                            $cuerpo.="($" . $name . "Dto->get" . ucwords($campos[$y]["field"]) . "()!=\"\") ||";
//                                        }
//                                        $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -2) . "){\n";
//                                        $cuerpo.="\$sql.=\",\";\n";
//                                        $cuerpo.="}\n";
//                                    }
                                }
                            }

//                            $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -1);
                            $cuerpo.="\$sql.=\") VALUES(\";\n";
//                            for ($x = 0; $x < count($campos); $x++) {
//                                if ((strtoupper($campos[$x]["field"]) == "FECHAREGISTRO") || (strtoupper($campos[$x]["field"]) == "FECHAACTUALIZACION")) {
//                                    $cuerpo.="now(),";
//                                } else {
//                                    if ($campos[$x]["primary"] != "PRI") {
//                                     $cuerpo.="'\".$" . $name . "Dto->get" . ucwords($campos[$x]["field"]) . "().\"',";   
//                                    }
//                                }
//                            }
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="if(";
                                $cuerpo.="$" . $name . "Array[\"" . ucwords($campos[$x]["field"]) . "\"]!=\"\"){\n";

                                if ((strtoupper($campos[$x]["field"]) == "FECHAREGISTRO") || (strtoupper($campos[$x]["field"]) == "FECHAACTUALIZACION")) {
//                                    $cuerpo.="\$sql.=\"now()\";\n";
                                } else {
                                    $cuerpo.="\$sql.=\"'\".\$" . $name . "Array[\"" . ucwords($campos[$x]["field"]) . "\"].\"'\";\n";
                                }

                                if ((count($campos) - 1) > $x) {
                                    $cuerpo.="if(";
                                    for ($y = ($x + 1); $y < count($campos); $y++) {
                                        if ((strtoupper($campos[$y]["field"]) == "FECHAREGISTRO") || (strtoupper($campos[$y]["field"]) == "FECHAACTUALIZACION")) {
                                            
                                        } else {
                                            $cuerpo.="($" . $name . "Array[\"" . ucwords($campos[$y]["field"]) . "\"]!=\"\") ||";
                                        }
                                    }
                                    if (substr($cuerpo, (count($cuerpo)) - 3, 3) == "||") {
                                        $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -2) . "){\n";
                                        $cuerpo.="\$sql.=\",\";\n";
                                        $cuerpo.="}\n";
                                    } else if (substr($cuerpo, (count($cuerpo)) - 3, 3) == "f(") {
                                        $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -3);
                                    }
                                }
                                $cuerpo.="}\n";
                            }

                            for ($x = 0; $x < count($campos); $x++) {
                                if ((strtoupper($campos[$x]["field"]) == "FECHAREGISTRO") || (strtoupper($campos[$x]["field"]) == "FECHAACTUALIZACION")) {
                                    $cuerpo.="\$sql.=\",now()\";\n";
//                                    if ((count($campos) - 1) > $x) {
//                                        $cuerpo.="if(";
//                                        for ($y = ($x + 1); $y < count($campos); $y++) {
//                                            $cuerpo.="($" . $name . "Dto->get" . ucwords($campos[$y]["field"]) . "()!=\"\") ||";
//                                        }
//                                        $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -2) . "){\n";
//                                        $cuerpo.="\$sql.=\",\";\n";
//                                        $cuerpo.="}\n";
//                                    }
                                }
                            }
//                            $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -1);
                            $cuerpo.="\$sql.=\")\";\n";

                            $cuerpo.="\$this->_proveedor->execute(\$sql);\n";
                            $cuerpo.="if (!\$this->_proveedor->error()) {\n";
                            $cuerpo.="\$tmp = new Array();\n";
                            $cuerpo.="\$tmp[0][\"" . $primary . "\"]=\$this->_proveedor->lastID();\n";
                            $cuerpo.="\$tmp = \$this->select" . ucwords($name) . "(\$tmp,\"\",\$this->_proveedor);\n";

                            $cuerpo.="} else {\n";
                            $cuerpo.="    \$error = true;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="if (\$proveedor == null) {\n";
                            $cuerpo.="    \$this->_proveedor->close();\n";
                            $cuerpo.="}\n";

                            $cuerpo.="unset(\$contador);\n";
                            $cuerpo.="unset(\$sql);\n";
                            $cuerpo.="return \$tmp;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="public function update" . ucwords($name) . "($" . $name . "Dto,\$proveedor=null){\n";
                            $cuerpo.="\$tmp = \"\";\n";
                            $cuerpo.="\$contador = 0;\n";
                            $cuerpo.="if (\$proveedor == null) {\n";
                            $cuerpo.="\$this->_conexion(null);\n";
                            $cuerpo.="//\$this->_proveedor->connect();\n";
                            $cuerpo.="} else if (\$proveedor != null) {\n";
                            $cuerpo.="\$this->_proveedor = \$proveedor;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="\$sql=\"UPDATE " . $tablas[$i] . " SET \";\n";
                            $primary = "";

                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="if(";
                                $cuerpo.="$" . $name . "Dto->get" . ucwords($campos[$x]["field"]) . "()!=\"\"){\n";
                                $cuerpo.="\$sql.=\"" . $campos[$x]["field"] . "='\".$" . $name . "Dto->get" . ucwords($campos[$x]["field"]) . "().\"'\";\n";
                                if ((count($campos) - 1) > $x) {
                                    $cuerpo.="if(";
                                    for ($y = ($x + 1); $y < count($campos); $y++) {
                                        $cuerpo.="($" . $name . "Dto->get" . ucwords($campos[$y]["field"]) . "()!=\"\") ||";
                                    }
                                    $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -2) . "){\n";
                                    $cuerpo.="\$sql.=\",\";\n";
                                    $cuerpo.="}\n";

                                    if ($campos[$x]["primary"] == "PRI") {
                                        $primary = $campos[$x]["field"];
                                    } else {
//                                        $cuerpo.=$campos[$x]["field"] . ",";
                                    }
                                }
                                $cuerpo.="}\n";
                            }
//                            for ($x = 0; $x < count($campos); $x++) {
//                                if (strtoupper($campos[$x]["field"]) != "FECHAREGISTRO") {
//                                    $cuerpo.=$campos[$x]["field"] . " = ";
//                                    if (strtoupper($campos[$x]["field"]) == "FECHAACTUALIZACION") {
//                                        $cuerpo.="now(),";
//                                    } else {
//                                        $cuerpo.="'\".$" . $name . "Dto->get" . ucwords($campos[$x]["field"]) . "().\"',";
//                                    }
//                                }
//                                if ($campos[$x]["primary"] == "PRI") {
//                                    $primary = $campos[$x]["field"];
//                                }
//                            }
//                            $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -1);
                            $cuerpo.="\$sql.=\" WHERE " . $primary . "='\".$" . $name . "Dto->get" . ucwords($primary) . "().\"'\";\n";

                            $cuerpo.="\$this->_proveedor->execute(\$sql);\n";
                            $cuerpo.="if (!\$this->_proveedor->error()) {\n";
                            $cuerpo.="\$tmp = new " . ucwords($name) . "DTO();\n";
                            $cuerpo.="\$tmp->set" . $primary . "($" . $name . "Dto->get" . ucwords($primary) . "());\n";
                            $cuerpo.="\$tmp = \$this->select" . ucwords($name) . "(\$tmp,\"\",\$this->_proveedor);\n";

                            $cuerpo.="} else {\n";
                            $cuerpo.="    \$error = true;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="if (\$proveedor == null) {\n";
                            $cuerpo.="    \$this->_proveedor->close();\n";
                            $cuerpo.="}\n";

                            $cuerpo.="unset(\$contador);\n";
                            $cuerpo.="unset(\$sql);\n";
                            $cuerpo.="return \$tmp;\n";
                            $cuerpo.="}\n";


                            $cuerpo.="public function delete" . ucwords($name) . "($" . $name . "Dto,\$proveedor=null){\n";
                            $cuerpo.="\$tmp = \"\";\n";
                            $cuerpo.="\$contador = 0;\n";
                            $cuerpo.="if (\$proveedor == null) {\n";
                            $cuerpo.="\$this->_conexion(null);\n";
                            $cuerpo.="//\$this->_proveedor->connect();\n";
                            $cuerpo.="} else if (\$proveedor != null) {\n";
                            $cuerpo.="\$this->_proveedor = \$proveedor;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="\$sql=\"DELETE FROM " . $tablas[$i] . "  ";
                            $primary = "";
                            for ($x = 0; $x < count($campos); $x++) {
                                if ($campos[$x]["primary"] == "PRI") {
                                    $primary = $campos[$x]["field"];
                                }
                            }
                            $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -1);
                            $cuerpo.=" WHERE " . $primary . "='\".$" . $name . "Dto->get" . ucwords($primary) . "().\"'\";\n";

                            $cuerpo.="\$this->_proveedor->execute(\$sql);\n";
                            $cuerpo.="if (!\$this->_proveedor->error()) {\n";
                            $cuerpo.="\$tmp = true;\n";
                            $cuerpo.="} else {\n";
                            $cuerpo.="\$tmp = false;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="if (\$proveedor == null) {\n";
                            $cuerpo.="    \$this->_proveedor->close();\n";
                            $cuerpo.="}\n";

                            $cuerpo.="unset(\$contador);\n";
                            $cuerpo.="unset(\$sql);\n";
                            $cuerpo.="return \$tmp;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="public function select" . ucwords($name) . "($" . $name . "Dto,\$orden=\"\",\$proveedor=null){\n";
                            $cuerpo.="\$tmp = \"\";\n";
                            $cuerpo.="\$contador = 0;\n";
                            $cuerpo.="if (\$proveedor == null) {\n";
                            $cuerpo.="\$this->_conexion(null);\n";
                            $cuerpo.="//\$this->_proveedor->connect();\n";
                            $cuerpo.="} else if (\$proveedor != null) {\n";
                            $cuerpo.="\$this->_proveedor = \$proveedor;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="\$sql=\"SELECT ";
                            $primary = "";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.=$campos[$x]["field"] . ",";
                                if ($campos[$x]["primary"] == "PRI") {
                                    $primary = $campos[$x]["field"];
                                }
                            }
                            $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -1);
                            $cuerpo.=" FROM " . $tablas[$i] . " \";\n";

                            $cuerpo.="if(";
                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="($" . $name . "Dto->get" . ucwords($campos[$x]["field"]) . "()!=\"\") ||";
                            }
                            $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -2) . "){\n";
                            $cuerpo.="\$sql.=\" WHERE \";\n";
                            $cuerpo.="}\n";

                            for ($x = 0; $x < count($campos); $x++) {
                                $cuerpo.="if(";
                                $cuerpo.="$" . $name . "Dto->get" . ucwords($campos[$x]["field"]) . "()!=\"\"){\n";
                                $cuerpo.="\$sql.=\"" . $campos[$x]["field"] . "='\".$" . $name . "Dto->get" . ucwords($campos[$x]["field"]) . "().\"'\";\n";
                                if ((count($campos) - 1) > $x) {
                                    $cuerpo.="if(";
                                    for ($y = ($x + 1); $y < count($campos); $y++) {
                                        $cuerpo.="($" . $name . "Dto->get" . ucwords($campos[$y]["field"]) . "()!=\"\") ||";
                                    }
                                    $cuerpo = substr($cuerpo, (count($cuerpo) - 1), -2) . "){\n";
                                    $cuerpo.="\$sql.=\" AND \";\n";
                                    $cuerpo.="}\n";
                                }
                                $cuerpo.="}\n";
                            }

                            $cuerpo.="if(\$orden!=\"\"){\n";
                            $cuerpo.="\$sql.=\$orden;\n";
                            $cuerpo.="}else{\n";
                            $cuerpo.="\$sql.=\"\";\n";
                            $cuerpo.="}\n";


                            $cuerpo.="\$this->_proveedor->execute(\$sql);\n";
                            $cuerpo.="if (!\$this->_proveedor->error()) {\n";
                            $cuerpo.="if (\$this->_proveedor->rows(\$this->_proveedor->stmt) > 0) {\n";
                            $cuerpo.="while (\$row = \$this->_proveedor->fetch_array(\$this->_proveedor->stmt, 0)) {\n";
                            $cuerpo.="\$tmp[\$contador] = new " . ucwords($name) . "DTO();\n";
                            for ($y = 0; $y < count($campos); $y++) {
                                $cuerpo.="\$tmp[\$contador]->set" . ucwords($campos[$y]["field"]) . "(\$row[\"" . $campos[$y]["field"] . "\"]);\n";
                            }
                            $cuerpo.="\$contador++;\n";
                            $cuerpo.="}\n";
                            $cuerpo.="} else {\n";
                            $cuerpo.="\$error = true;\n";
//                            $cuerpo.="throw new Exception(\"No se loalizaron tablas de la bd\");\n";
                            $cuerpo.="}\n";

                            $cuerpo.="} else {\n";
                            $cuerpo.="    \$error = true;\n";
                            $cuerpo.="}\n";

                            $cuerpo.="if (\$proveedor == null) {\n";
                            $cuerpo.="    \$this->_proveedor->close();\n";
                            $cuerpo.="}\n";

                            $cuerpo.="unset(\$contador);\n";
                            $cuerpo.="unset(\$sql);\n";
                            $cuerpo.="return \$tmp;\n";
                            $cuerpo.="}\n";

                            $cuerpo .= "}\n?>";
                            fwrite($dto, $cuerpo);
                        } else {
                            $logger->w_onError("EL ARCHIVO YA EXISTE NO SE PUEDE REESCRIBIR " . ROOT . "modelos" . DS . "dto" . DS . $name . DS . ucwords($name) . "DTO.Class.php");
                        }
                    } else {
                        $logger->w_onError("EL DIRECTORIO NO SE LOGRO CREAR");
                    }
                }

//                break;
            }
        }

        $logger->w_onError("**********TERMINA EL PROGRAMA CON LA CREACION DEL MODELO**********");

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

//                echo $ruta;
//                echo "<br>";

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