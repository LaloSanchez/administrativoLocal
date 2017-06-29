<?php
include_once(dirname(__FILE__) . "/class/configlobal.php");
include_once(dirname(__FILE__) . "/class/Class.spachat.php");

function getExt($file) {
    return substr($file, strpos($file, ".") + 1, strlen($file) - strpos($file, "."));
}

function sanitizeFileName($dangerous_filename) {
    $flag = strripos($dangerous_filename, ".");


    if ($flag == false)
        $lpos = -1;
    else
        $lpos = strripos($dangerous_filename, ".");

    $dangerous_characters = array(".", "+", "*", "=", " ", '"', "'", "&", "/", "\\", "?", "#");

    $cleanstring = str_replace($dangerous_characters, '_', $dangerous_filename);

    if ($lpos != -1) {
        $cleanstring[$lpos] = '.';
    } else {
        $cleanstring = $cleanstring . ".txt";
    }

    return $cleanstring;
}

//Validar cuando no se escoje archivo, validar cuando hay limite de espacio.

$new_name = "nofile";

if (isset($_FILES['file'])) {
    $message = '';
    $dir_upload = UPLOAD_DIR;
    $max_size = MAXFILESIZE;
    $file = $_FILES['file'];

    if ($file['size'] <= $max_size && $file['size'] > 0) {

        $filename = sanitizeFileName($file['name']);

        $ext = getExt($filename);
        $nom = substr($filename, 0, strpos($filename, "."));
        $old_name = $file['name'];

        $new_name = TOKEN . $nom . "=" . USERNAME . "." . $ext;

        $copied = copy($file['tmp_name'], $dir_upload . $new_name);

        if ($copied) {
            $message = 'OK';
            $mensaje = "Subió correctamente el archivo.";
        } else {
            $message = 'BAD';
            $mensaje = "No subió correctamente el archivo";
        }
    } else {
        $message = 'LIM';
        $mensaje = "El archivo excedió el limite o no se seleccionó correctamente";
    }

    if ($message == "OK") {
        $chatApp->getModel()->addMessage(USERNAME, IDUSER, "ATENCION: Subí archivo [" . $old_name . "] a este chat.", '0.0.0.0', TOKEN);
    }
    //echo $new_name;
    echo json_encode(array("type" => $message, "text" => $mensaje));
    exit;
}
?>
<!DOCTYPE html>
<html ng-app="ChatApp">
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="Expires" content="-1">
        <title>Chat SIPEJUVE2</title>
    </head>

    <script src="./ajax/libs/angularjs/1.3.14/angular.min.js"></script>
    <script src="./ajax/libs/angularjs/1.3.14/angular-sanitize.js"></script>

    <script src="./ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="./js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>-->
    <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet" />
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">-->
    <script type="text/javascript" src="./js/bootstrap-filestyle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="./angular/chatangular.js"></script>

    <script>
        var idusuario = '<?php echo USERNAME; ?>';
        var chatid = '<?php echo TOKEN ?>';

        $(document).ready(function () {
<?php
if (isset($message)) {
    echo "\$('#subiobien').modal('show');";
    unset($message);
}
?>
            //    checkaliveAction();
        });


        function F5()
        {
            refreshIframe();
        }

        function OpenFile(obj) {
            var validation = $(obj).attr("data-target");
            if (validation == "#choose-file") {
                window.parent.openModalFiles();
            }
        }

        function WhoisOnline(obj) {
            var validation = $(obj).attr("data-target");
            if (validation == "#whoisonline") {
                window.parent.openModalWhoisOnline();
            }
        }

        $(document).on('hidden.bs.modal', function (e) {
            if (($(e.target).attr('data-refresh') == 'true')) {
<?php
if (!isset($file)) {
    echo "location.reload();";
}
?>
            }
        });

        function refreshIframe() {
            var iframe = document.getElementById('directory');
            iframe.src = iframe.src;
        }
//setInterval(refreshIframe, 10000);
    </script>



    <body ng-controller="ChatAppCtrl">
        <div class="container-fixed">
            <div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-body">
                    <div class="direct-chat-messages">
                        <div class="direct-chat-msg" ng-repeat="message in messages" ng-if="historyFromId < message.id" ng-class="{'right':!message.me}">
                            <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name" ng-class="{'pull-left':message.me, 'pull-right':!message.me}">{{ message.username}}</span>
                                <span class="direct-chat-timestamp " ng-class="{'pull-left':!message.me, 'pull-right':message.me}">{{ message.date}}</span>
                            </div>
                            <img class="direct-chat-img" src="./images/Unknown-person.gif" alt="">
                            <div class="direct-chat-text right">
                                <span>{{message.message}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <form ng-submit="saveMessage()" ng-init="checkaliveAction();" ng-click="checkaliveAction();">
                            <div class="col-md-12">
                                <input <?php echo $activar; ?> type="text" placeholder="{{ mensaje}}" autofocus="autofocus" class="form-control {{activo}}" ng-model="me.message" ng-enter="saveMessage()">
                            </div>
                            <hr />
                            <div class="col-md-12" >
                                <button <?php echo $activar; ?> ng-click="checkaliveAction();" type="submit" class="btn btn-success btn-flat {{activo}}">Enviar</button>
                                <button <?php echo $activar; ?> ng-click="checkaliveAction();" type="button" class="btn btn-primary btn-flat {{activo}}" data-toggle="modal" data-target="#choose-file{{activo}}" onClick="javascript:OpenFile(this);">
                                    <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span> Archivos </button>
                                <button <?php echo $activar; ?> ng-click="checkaliveAction();" type="button" class="btn btn-info btn-flat {{activo}}" data-toggle="modal" data-target="#whoisonline{{activo}}" onClick="javascript:WhoisOnline(this);">
                                    <span class="glyphicon glyphicon-user" aria-hidden="true" style="color:black"></span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="subiobien" role="dialog">
            <div class="modal-dialog modal-sm" style="width: 90%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Mensaje Informativo:</h4>
                    </div>
                    <div class="modal-body">
                        <h5><p><?php echo $mensaje; ?></p></h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
