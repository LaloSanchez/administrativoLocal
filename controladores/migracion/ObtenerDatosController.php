<?php
include_once(dirname(__FILE__) . "/../../tribunal/json/JsonDecod.Class.php");
include_once(dirname(__FILE__) . "/../../tribunal/connect/conexionRemota.php");
set_time_limit(-1);
ini_set('memory_limit', -1);

class ObtenerDatosController{
    public function getInfo(){
        $error=false;
        $datos=mysql_query("select * from tblinventarios where activo='S'");
        $array=array();
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblinventarios"]["status"]="success";
            $array["data"]["tblinventarios"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblinventarios"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tblaah where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblaah"]["status"]="success";
            $array["data"]["tblaah"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblaah"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tblcbi where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblcbi"]["status"]="success";
            $array["data"]["tblcbi"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblcbi"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tblcbm where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblcbm"]["status"]="success";
            $array["data"]["tblcbm"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblcbm"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tblclasificadoresbienes where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblclasificadoresbienes"]["status"]="success";
            $array["data"]["tblclasificadoresbienes"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblclasificadoresbienes"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tblclasificadorestiposbienes where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblclasificadorestiposbienes"]["status"]="success";
            $array["data"]["tblclasificadorestiposbienes"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblclasificadorestiposbienes"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tblcogbienes where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblcogbienes"]["status"]="success";
            $array["data"]["tblcogbienes"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblcogbienes"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tblestadosbienes where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblestadosbienes"]["status"]="success";
            $array["data"]["tblestadosbienes"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblestadosbienes"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tblestatus where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblestatus"]["status"]="success";
            $array["data"]["tblestatus"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblestatus"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tblresguardos where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblresguardos"]["status"]="success";
            $array["data"]["tblresguardos"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblresguardos"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tbltiposbienes where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tbltiposbienes"]["status"]="success";
            $array["data"]["tbltiposbienes"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tbltiposbienes"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tbltiposfuentesfinanciamiento where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tbltiposfuentesfinanciamiento"]["status"]="success";
            $array["data"]["tbltiposfuentesfinanciamiento"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tbltiposfuentesfinanciamiento"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        $datos=mysql_query("select * from tblunidadesmedida where activo='S'");
        if (!$datos) {
            $error=true;
        }else{
            $totalCount=mysql_num_rows($datos);
            $array["data"]["tblunidadesmedida"]["status"]="success";
            $array["data"]["tblunidadesmedida"]["totalCount"]=$totalCount;
            $contador=0;
            while ($fila = mysql_fetch_assoc($datos)) {
                $array["data"]["tblunidadesmedida"]["data"][$contador]=$fila;
                $contador++;
            }
        }
        return $array;
    }
}