<?php

session_start();
// este es el bueno 
//include_once(dirname(__FILE__) . "/../../modelos/sigejupe/dto/chatmessages/ChatMessagesDTO.Class.php");
//include_once(dirname(__FILE__) . "/../../modelos/sigejupe/dao/chatmessages/ChatMessagesDAO.Class.php");
//ini_set("AddDefaultCharset" , "ISO-8859-1");

@$cveUsuarioSistema = $_SESSION["cveUsuarioSistema"];
@$cvePerfil = $_POST["cvePerfil"];
@$jsonResonse = "";
if(isset($_POST['cveUsuarioSistema'])){
   $_SESSION["cveUsuarioSistema"] = @$_POST['cveUsuarioSistema'];
   $cveUsuarioSistema = $_SESSION["cveUsuarioSistema"];
}
if (($cvePerfil !== "") && (isset($_SESSION["cveUsuarioSistema"]))) {
    $fileJson = "../../archivos/" . $cveUsuarioSistema . ".json";
    if (file_exists($fileJson)) {

        $json = file_get_contents($fileJson);
        if ($json !== "") {
            $json = json_decode($json, true);
            if ($json["cveUsuario"] === $cveUsuarioSistema) {
                $_SESSION["cveUsuarioSistema"] = $json["cveUsuario"];
                $_SESSION["numEmpleado"] = $json["numEmpleado"];
                $_SESSION["nombre"] = $json["nombre"] . " " . $json["paterno"] . " " . $json["materno"];
                $_SESSION["email"] = $json["email"];
                $_SESSION["tipoUsuario"] = $json["tipoUsuario"];
//                $_SESSION["cveOrganigrama"] = $json["cveOrganigrama"];
//                $_SESSION["descOrganigrama"] = $json["descOrganigrama"];
                
                foreach ($json["perfiles"][0]["perfil"] as $perfil) {
                    if ($perfil["cvePerfil"] === $cvePerfil) {
                        $_SESSION["cveGrupo"] = $perfil["cveGrupo"];
                        $_SESSION["cveSistema"] = $perfil["cveSistema"];
                        $_SESSION["cvePerfil"] = $perfil["cvePerfil"];
                        $_SESSION["cveAdscripcion"] = $perfil["cveAdscripcion"];
                        $_SESSION["desAdscripcion"] = utf8_decode($perfil["desAdscripcion"]);
                        $_SESSION["cveDistrito"] = $perfil["cveDistrito"];
                        $_SESSION["cveOrganigrama"] = $perfil["cveOrganigrama"];
                        $_SESSION["tipoAdscripcion"] = $perfil["tipoAdscripcion"];
                        $_SESSION["Nombre"] = utf8_decode($_SESSION["nombre"]);
                        $jsonResonse = "{";
                        $jsonResonse .= '"cvePerfil":'. json_encode($cvePerfil) . ",";
                        $jsonResonse .= '"cveGrupo":'. json_encode($perfil["cveGrupo"]) . ",";
                        $jsonResonse .= '"cveSistema":'. json_encode($perfil["cveSistema"]) . ",";
                        $jsonResonse .= '"cvePerfilSes":'. json_encode($perfil["cvePerfil"]) . ",";
                        $jsonResonse .= '"cveUsuarioSistema":'. json_encode($_SESSION["cveUsuarioSistema"]) . ",";
                        $jsonResonse .= '"cveAdscripcion":'. json_encode($perfil["cveAdscripcion"]) . ",";
                        $jsonResonse .= '"desAdscripcion":'. json_encode($perfil["desAdscripcion"]) . ",";
                        $jsonResonse .= '"cveOrganigrama":'. json_encode($perfil["cveOrganigrama"]) . ",";
                        $jsonResonse .= '"tipoAdscripcion":'. json_encode($perfil["tipoAdscripcion"]) . "";
//                        $_SESSION["cveOrganigrama"] = $json["cveOrganigrama"];
//                        $_SESSION["descOrganigrama"] = $json["descOrganigrama"];
                        echo $jsonResonse .= "}";
                        
			/*
			Se comenta chat general
                        $chatMessagesDto = new ChatMessagesDTO();
                        $chatMessagesDao = new ChatMessagesDAO();
                        $chatMessagesDto->setCveUsuario($_SESSION['cveUsuarioSistema']);
                        $chatMessagesDto->setChatId(md5($_SESSION['cveAdscripcion'] . "-3"));
                        $chatMessagesDto = $chatMessagesDao->selectDistintChatMessages($chatMessagesDto, " AND chatId NOT in (select chatId from tblchatcerrados)  ", null);
                        if ($chatMessagesDto != "" && count($chatMessagesDto) > 0) {
//                            echo "aqui";
                        } else {
                            $chatId = md5($_SESSION['cveAdscripcion'] . "-3");
                            $chatMessagesMPDto = new ChatMessagesDTO(); //INVITACI�N A SALA DE CHAT DE JUZGADO
                            $chatMessagesDao = new ChatMessagesDAO();
                            $chatMessagesMPDto->setChatId($chatId);
                            $chatMessagesMPDto->setIpUsuario($_SERVER['REMOTE_ADDR']);
                            $chatMessagesMPDto->setMensaje("SE AGREGO A :" . $_SESSION["Nombre"] . " AL CHAT DEL JUZGADO ");
                            $chatMessagesMPDto->setCveUsuario($_SESSION['cveUsuarioSistema']);
                            $chatMessagesMPDto->setNombreUsuario($_SESSION['Nombre']);
                            $chatMessagesMPDto->setCveNumero($_SESSION['cveAdscripcion']);
                            $chatMessagesMPDto->setTipoChat("3"); # tipo chat 1 = chats de juzgados
                            $chatMessagesMPDto = $chatMessagesDao->insertChatMessages($chatMessagesMPDto, null);
                            if ($chatMessagesMPDto != "" && count($chatMessagesMPDto) > 0) {
                                $chatMessagesMPDto = $chatMessagesMPDto[0];
                            } else {
                                $error = true;
                                $tmp = array("type" => "Error", "text" => "ERROR AL AGREGAR AL USUARIO A LA SALA DE CHAT DEL JUZGADO");
                            }

                        }*/
                    }
                }
            } else {
                echo "No corresponde Usuario Session vs Json";
            }
        } else {
            echo "Empty Json";
        }
    } else {
        echo "No Existe archivo";
    }
} else {
    echo "Sin session";
}
?>
