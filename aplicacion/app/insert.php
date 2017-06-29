<?php
include_once(dirname(__FILE__) . "/appConfig.php");
error_reporting(E_ALL);
error_reporting(-1);
$user = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];
$base = $_POST["dataBase"];
$table = $_POST["table"];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title></title>

        <!-- Bootstrap Core CSS -->
        <link href="appGen/components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="appGen/components/bootstrap/dist/css/bootstrapValidator.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <!--<link href="appGen/components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">-->

        <!--         Timeline CSS 
                <link href="appGen/components/datatables/media/js/jquery.dataTables.min.js" rel="stylesheet">-->

        <!-- Custom CSS -->
        <link href="appGen/dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="appGen/components/morrisjs/morris.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="appGen/components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <script language="javascript">
        desTables = function (div, db, tbl) {
            var div = parent.document.getElementById(div);
            $.post("describe.php", {dataBase: db, table: tbl}, function (htmlexterno) {
                $("#" + div.id).html(htmlexterno);
            });
        }
    </script>

    <body>
        <div id="wrapper">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <a href="#noir" onclick="desTables('divContentAdmin', '<?php echo $base; ?>', '<?php echo $table; ?>')"><li class="fa fa-backward fa-fw"></li>Regresar</a>

                    <form id="formEjemplo" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Content</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="content" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-3">
                                <div id="messages"></div>
                            </div>
                        </div>
                    </form>

                    <form id="form-Generico" method="post" class="form-horizontal"> 
                        <?php
                        $mysqli = new mysqli(host, $user, $password, "information_schema", port);
                        if ($mysqli->connect_error) {
                            die("Error en la conexion : " . $mysqli->connect_errno .
                                    "-" . $mysqli->connect_error);
                            exit(0);
                        }

                        $sql = "SELECT * FROM COLUMNS WHERE TABLE_SCHEMA = '" . $base . "' AND TABLE_NAME = '" . $table . "'";
                        $sentencia = $mysqli->query($sql);
                        $campos = array();
                        if ($sentencia->num_rows > 0) {
                            while ($row = $sentencia->fetch_assoc()) {
                                if ($row["COLUMN_COMMENT"] != "") {
                                    $campos[] = $row;
                                }
                            }

                            for ($index = 0; $index < count($campos); $index++) {
                                $comment = explode('||', $campos[$index]["COLUMN_COMMENT"]);
                                echo "<div class=\"form-group\">";
                                echo "<b>" . $comment[0] . "</b>";
                                echo "<input type=\"text\" class=\"form-control\" name=\"" . $campos[$index]["COLUMN_NAME"] . "\" id=\"" . $campos[$index]["COLUMN_NAME"] . "\">";
                                echo "</div>";
                            }
                        }
                        ?>
                        <?php
                        $mysqli->close();
                        ?>
                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <button type="submit" class="btn btn-success left">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="appGen/components/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="appGen/components/bootstrap/dist/js/bootstrap.min.js"></script>       

        <!-- Morris Charts JavaScript -->
        <!--<script src="appGen/components/raphael/raphael-min.js"></script>-->
        <!--<script src="appGen/components/morrisjs/morris.min.js"></script>-->
        <!--<script src="appGen/js/morris-data.js"></script>-->

        <!-- Custom Theme JavaScript -->
        <!--<script src="appGen/dist/js/sb-admin-2.js"></script>-->
        <?php
//        $validacion = "<script>\n";
//        $validacion.="$(document).ready(function() {\n";
//        $validacion.= "$('#form-Generico').bootstrapValidator({\n";
//        $validacion.= "message: 'Este valor no es valido',\n";
//        $validacion.= "feedbackIcons: {\n";
//        $validacion.= "valid: 'glyphicon glyphicon-ok',\n";
//        $validacion.= "invalid: 'glyphicon glyphicon-remove',\n";
//        $validacion.= "validating: 'glyphicon glyphicon-refresh'\n";
//        $validacion.= "},\n";
//
//        $validacion.= "fields: { \n";
//
//        for ($index = 0; $index < count($campos); $index++) {
//            if ($campos[$index]["COLUMN_COMMENT"] != "") {
//                $comment = explode('||', $campos[$index]["COLUMN_COMMENT"]);
//                $validacion.= $campos[$index]["COLUMN_NAME"] . " : { \n";
//                $validacion.= "validators : { \n";
//
//                if ($campos[$index]["IS_NULLABLE"] == "NO") {
//                    $validacion.="notEmpty: { ";
//                    $validacion.="message: 'El password es requerido y no puede ser vacio'";
//                    $validacion.="}, \n";
//                }
//
//                $validacion.="stringLength: { \n";
//                $validacion.="min: 1,";
//                $validacion.="message: 'El password debe contener al menos 8 caracteres'";
//                $validacion.="},\n";
//
//                if ($comment[1] != "") {
//                    $validacion.="regexp: { \n";
//                    $validacion.="regexp: $comment[1],";
//                    $validacion.="message: 'El teléfono solo puede contener números'";
//                    $validacion.="}\n";
//                }
//
//                $validacion.= " }\n";
//                $validacion.= " },";
//            }
//            $validacion = substr($validacion, 0, -1) . "\n";
//        }
//        $validacion.= "}\n";
//        $validacion.= "});\n";
//        $validacion.= "});\n";
//
//        $validacion.= "</script>\n";
//        echo $validacion;
        ?>
        
    </body>

</html>