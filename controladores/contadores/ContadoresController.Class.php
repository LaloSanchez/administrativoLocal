<?php

include_once(dirname(__FILE__) . "/../../modelos/dao/GenericDAO.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/connect/Proveedor.Class.php");

class ContadoresController {

    private $proveedor;

    public function __construct() {
        
    }

    public function getContador($contadores, $proveedor = null) {
        $error = false;
        $logger = new Logger("/../../logs/", "ContadoresController");
        $logger->w_onError("**********COMIENZA EL PROGRAMA PARA OBTENER CONTADOR**********");
        $arrayContador = json_encode($contadores);
        $logger->w_onError("SE RECIBEN LOS SIGUIENTES PARAMETROS:" . $arrayContador);


        if ((int) $contadores["cveTipoDocContador"] <= 0) { //si ambos contadores vienen vacios o son menores a cero, regresa error
            $tmpDto = "";
        } else {
            if ($proveedor == null) {
                $this->proveedor = new Proveedor('mysql', 'administrativo');
                $this->proveedor->connect();
                $this->proveedor->execute("BEGIN");
            } else {
                $this->proveedor = $proveedor;
            }

            $tmpDto = $contadores;
            //////************SE OBTIENE CONTADOR ****************///////////
            $genericDAO = new GenericDAO();
            $strWHERE = " cveAdscripcion='" . $contadores["cveAdscripcion"] . "' ";
            if ($contadores["mes"] == "N") {
//                $strWHERE .= " AND mes= ''";
            } else {
                $strWHERE .= " AND mes='" . $contadores["cveMes"] . "' ";
            }
            $strWHERE .= " AND anio='" . $contadores["anio"] . "' ";
            $strWHERE .= " AND activo='S' ";
            $strWHERE .= " AND cveTipoDocContador='" . $contadores["cveTipoDocContador"] . "' ";
            $strWHERE .= " AND cveOrganigrama='" . $contadores["cveOrganigrama"] . "' ";


            $strWHERE .= " FOR UPDATE ";

            $sql = array(
                "campos" => "C.*",
                "tablas" => "tblcontadores C",
                "where" => $strWHERE,
                "orders" => ""
            );
            $paramSQL = array(
                "tabla" => "tblcontadores",
                "d" => array(),
                "tmpSql" => $sql,
                "proveedor" => $this->proveedor
            );
            $rsCarpetas = $genericDAO->select($paramSQL);
            //////************ FIN SE OBTIENE CONTADOR ****************///////////
            //////************ SE ACTUALIZA CONTADOR ****************///////////
            if ($rsCarpetas["totalCount"] > 0) {
                $logger->w_onError("ENCONTRO CONTADOR Y ACTUALIZA + 1");
                $numero = (int) $rsCarpetas["data"][0]["numero"];
                $numero = $numero + 1;
                $rsCarpetas["data"][0]["numero"] = ($numero);
                $sql = array(
                    "tablas" => "tblcontadores "
                );

                $d = array(
                    "values" => array("numero" => $rsCarpetas["data"][0]["numero"], "fechaActualizacion" => "now()"),
                    "where" => array("idContadores" => $rsCarpetas["data"][0]["idContadores"])
                );

                $paramSQL = array(
                    "tabla" => "tblcontadores",
                    "d" => $d,
                    "tmpSql" => $sql,
                    "proveedor" => $this->proveedor
                );
                $carpetasDTO = $genericDAO->update($paramSQL);

                if ($rsCarpetas["totalCount"] == 0) {
                    $error = true;
                }
                //////************ FIN  ACTUALIZA CONTADOR ****************///////////
            } else {

                //////************ SE GENERA UN NUEVO CONTADOR ****************///////////
                $auxCampos = "cveTipoDocContador,cveAdscripcion,cveOrganigrama,numero,anio,activo,fechaRegistro,fechaActualizacion";
                if ($contadores["mes"] == "S") {
                    $auxCampos .= ",mes";
                }

                $auxValues = "'" . $contadores["cveTipoDocContador"]
                        . "','" . $contadores["cveAdscripcion"]
                        . "','" . $contadores["cveOrganigrama"]
                        . "','" . "1"
                        . "','" . $contadores["anio"]
                        . "','" . "S"
                        . "'," . "now()"
                        . "," . "now()";
                if ($contadores["mes"] == "S") {
                    $auxValues .= "," . $contadores["cveMes"];
                } else {
                    
                }
                $sql = array(
                    "campos" => $auxCampos,
                    "values" => $auxValues
                );
                $param = array(
                    "tabla" => "tblcontadores",
                    "d" => array(),
                    "tmpSql" => $sql,
                    "proveedor" => $this->proveedor
                );

                $rsCarpetas = $genericDAO->insert($param);
                if ($rsCarpetas["totalCount"] == 0) {
                    $error = true;
                }

                //////************ TERMINA DE  UN NUEVO CONTADOR ****************///////////
            }


            if ($proveedor == null) {
                if ($error == false) {
                    $this->proveedor->execute("COMMIT");
                } else {
                    $this->proveedor->execute("ROLLBACK");
                }
            }
            if ($proveedor == null) {
                $this->proveedor->close();
            }
        }



        return $rsCarpetas;
    }

    public function consultar($param, $proveedor = null) {
        $genericDAO = new GenericDAO();
        $proveedor = new Proveedor('mysql', 'administrativo');
        $proveedor->connect();

        $genericDAO = new GenericDAO();

        $strWHERE = "DSC.activo='S'";
        $strWHERE .= " AND DSC.cveTipoDocContador = tc.cveContadorTipoDocumento";
        if ($param["idContadores"] != "") {
            $strWHERE .= " AND DSC.idContadores=" . $param["idContadores"];
        }

        $sql = array(
            "campos" => "DSC.*, tc.desContadorTipoDocumento",
            "tablas" => "tblcontadores as DSC, tblcontadortipodocumento as tc ",
            "where" => $strWHERE,
            "orders" => "DSC.fechaRegistro"
        );
        $paramSQL = array(
            "tabla" => "tblcontadores as DSC, tblcontadortipodocumento as tc  ",
            "d" => array(),
            "tmpSql" => $sql,
            "proveedor" => $proveedor
        );
        $carpetasDTO = $genericDAO->select($paramSQL);
        return $carpetasDTO;
    }

    public function modificar($param, $proveedor = null) {
        $genericDAO = new GenericDAO();
        $proveedor = new Proveedor('mysql', 'administrativo');
        $proveedor->connect();

        $sql = array(
            "tablas" => "tblcontadores"
        );

        $d = array(
            "values" => array(
                "numero" => $param["numero"],
                "anio" => $param["anio"],
                "mes" => $param["cveMes"],
                "cveTipoDocContador" => $param["cveTipoDocContador"],
                "activo" => $param["activo"],
                "fechaActualizacion" => "now()",
            ),
            "where" => array(" idContadores" => $param["idContadores"])
        );

        $paramSQL = array(
            "tabla" => "tblcontadores",
            "d" => $d,
            "tmpSql" => $sql,
            "proveedor" => $proveedor
        );
        $carpetasDTO = $genericDAO->update($paramSQL);
        return $carpetasDTO;
    }
    
    public function dataTable($params){
        $limit = array("max" => $params["limit"]["max"], "pag" => $params["limit"]["pag"]);
        $d = array("limit" => $limit);
        $sql = array("campos" => "DSC.idContadores,tc.desContadorTipoDocumento, DSC.numero, DSC.anio, DSC.mes, DSC.activo",
            "tablas" => "tblcontadores as DSC
                         INNER JOIN tblcontadortipodocumento tc ON (tc.cveContadorTipoDocumento = DSC.cveTipoDocContador)",
            "orders" => $params["order"]["column"] . " " . $params["order"]["dir"],
            "where" => "DSC.activo='S' ");

        if ($params['search']['value'] != "") {
            $sql['where'] = "DSC.activo='S' AND (DSC.cveTipoDocContador LIKE '%" . $params['search']['value'] . "%' OR DSC.cveAdscripcion  LIKE '%" . $params['search']['value'] . "%' OR DSC.cveOrganigrama LIKE '%" . $params['search']['value'] . "%' OR  DSC.numero LIKE '%" . $params['search']['value'] . "%' OR DSC.mes LIKE '%" . $params['search']['value'] . "%' OR DSC.anio LIKE '%" . $params['search']['value'] . "%' OR tc.desContadorTipoDocumento LIKE '%" . $params['search']['value'] . "%')";
        }

        $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
        return $this->datatableGenerico($params, $param, $limit, $nombreTabla = $sql['tablas'], $sql['where']);
    }
    
    public function datatableGenerico($params, $param, $limit, $nombreTabla, $condiciones = "", $agrupacion = "", $orders = "") {

        $genericoDao = new GenericDAO();
        $row = $genericoDao->select($param);
        if (($row != "") && (sizeof($row) > 0)) {
            $d = array("campos" => "");
            $d = array_merge($d, array());

            $sql = array("campos" => "count(*) as Total", "values" => "", "tablas" => $nombreTabla, "where" => $condiciones, "groups" => $agrupacion, "orders" => $orders);

            $param = array("tabla" => "", "d" => $d, "tmpSql" => $sql, "proveedor" => null);
            $arrayTot = $genericoDao->select($param);
            //print_r($arrayTot);
            $data = array();
            for ($index = 0; $index < sizeof(@$row["data"]); $index++) {
                $registro = array();
                foreach ($row["data"][$index] as $key => $value) {

                    $registro[] = $value;
                }
                $data[] = $registro;
            }
            $output = array(
                "draw" => $params["draw"],
                "recordsTotal" => (int) $row["totalCount"],
                "recordsFiltered" => (int) $arrayTot["data"][0]["Total"],
                "start" => $limit["pag"],
                "length" => $limit["max"],
                "data" => $data);
            $json = new Encode_JSON();

            return $json->encode($output);
        } else {
            return json_encode(array("status" => "error", "totalCount" => 0, "msg" => "sin informacion a mostrar"));
        }
    }
    
}

//$contadores = array();
//
//***EJEMPLO ARRAY***********************************
//$contadores["cveAdscripcion"] = 850;
//$contadores["cveTipoDocContador"] = 1;
//$contadores["cveOrganigrama"] = "010201010101";
//$contadores["mes"] = "S";
//$contadores["cveMes"] = 3;
//$contadores["anio"] = 2017;
//***EJEMPLO ARRAY***********************************
//$contadores["cveAdscripcion"] = 850;
//$contadores["cveTipoDocContador"] = 1;
//$contadores["cveOrganigrama"] = "3013403000";
//$contadores["mes"] = "N";
//$contadores["cveMes"] = 0;
//$contadores["anio"] = 2017;
////////
//$contadoresController = new ContadoresController();
//$contadores = $contadoresController->getContador($contadores);
//var_dump($contadores);
?>