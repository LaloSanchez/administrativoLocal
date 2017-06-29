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
        showTables = function (div, db) {
            var div = parent.document.getElementById(div);
            $.post("admin.php", {dataBase: db}, function (htmlexterno) {
                $("#" + div.id).html(htmlexterno);
            });
        }
        alterTable = function (div, db, tbl) {
            var div = parent.document.getElementById(div);
            $.post("alter.php", {dataBase: db, table: tbl}, function (htmlexterno) {
                $("#" + div.id).html(htmlexterno);
            });
        }
        examinarTable = function (div, db, tbl) {
            var div = parent.document.getElementById(div);
            $.post("examinar.php", {dataBase: db, table: tbl}, function (htmlexterno) {
                $("#" + div.id).html(htmlexterno);
            });
        }
        nuevoRegistro = function (div, db, tbl) {
            var div = parent.document.getElementById(div);
            $.post("insert.php", {dataBase: db, table: tbl}, function (htmlexterno) {
                $("#" + div.id).html(htmlexterno);
            });
        }
    </script>

    <body>
        <div id="wrapper">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <a href="#noir" onclick="showTables('divContent', '<?php echo $base; ?>')"><li class="fa fa-backward fa-fw"></li>Todas las Tablas</a>
                    <a href="#noir" onclick="examinarTable('divContentAdmin', '<?php echo $base; ?>','<?php echo $table; ?>')"><li class="fa fa-search fa-fw"></li>Explorar Tabla</a>
                    <a href="#noir" onclick="alterTable('divContentAdmin', '<?php echo $base; ?>')"><li class="fa fa-edit fa-fw"></li>Editar Tabla</a>
                    <a href="#noir" onclick="nuevoRegistro('divContentAdmin', '<?php echo $base; ?>','<?php echo $table; ?>')"><li class="fa fa-sun-o fa-fw"></li>Nuevo Registro</a>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-dataBase">
                        <thead>
                            <tr>
                                <th>Options</th>
                                <th>Colum Name</th>
                                <th>Null</th>
                                <th>Type</th>
                                <th>Length</th>
                                <th>Colum Key</th>
                                <th>Extra</th>
                                <th>Comment</th>
                                <th>FK</th>
                                <th>Table</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            @$mysqli = new mysqli(host, $user, $password, "information_schema", port);
                            if ($mysqli->connect_error) {
                                die("Error en la conexion : " . $mysqli->connect_errno .
                                        "-" . $mysqli->connect_error);
                                exit(0);
                            }

                            $sql = "SELECT * FROM COLUMNS WHERE TABLE_SCHEMA = '" . $base . "' AND TABLE_NAME = '" . $table . "'";
                            $sentencia = $mysqli->query($sql);
//                            printf("Error: %s", $mysqli->error);
                            ?>
                            <?php
                            if ($sentencia->num_rows > 0) {
                                while ($row = $sentencia->fetch_assoc()) {
                                    echo "<tr class=\"odd gradeX\">";
                                    echo "<td class=\"center\"><a href=\"#\"><i class=\"fa fa-edit fa-fw\"></i></a></td>";
                                    echo "<td>" . $row['COLUMN_NAME'] . "</td>";
                                    echo "<td>" . $row['IS_NULLABLE'] . "</td>";
                                    echo "<td class=\"center\">" . $row['DATA_TYPE'] . "</td>";
                                    echo "<td class=\"center\">" . ((substr($row["COLUMN_TYPE"], strlen($row['DATA_TYPE']) + 1, -1) == "") ? strlen($row["COLUMN_TYPE"]) : substr($row["COLUMN_TYPE"], strlen($row['DATA_TYPE']) + 1, -1)) . "</td>";
                                    echo "<td class=\"center\">" . $row["COLUMN_KEY"] . "</td>";
                                    echo "<td class=\"center\">" . $row["EXTRA"] . "</td>";
                                    echo "<td class=\"center\">" . $row["COLUMN_COMMENT"] . "</td>";
                                    $sql = "Select * From information_schema.key_column_usage  Where TABLE_SCHEMA='" . $base . "' And TABLE_NAME='" . $table . "' And COLUMN_NAME='" . $row['COLUMN_NAME'] . "' And REFERENCED_TABLE_SCHEMA is not null GROUP BY COLUMN_NAME";
                                    $foranea = $mysqli->query($sql);

                                    if ($foranea->num_rows > 0) {
                                        while ($fk = $foranea->fetch_assoc()) {
                                            echo "<td>" . $fk['CONSTRAINT_NAME'] . "</td>";
                                            echo "<td>" . $fk['REFERENCED_TABLE_NAME'] . "</td>";
                                        }
                                    } else {
                                        echo "<td class=\"center\">-</td>";
                                        echo "<td class=\"center\">-</td>";
                                    }
                                    echo "</tr>";
                                }
                            }
                            ?>
                            <?php
                            $mysqli->close();
                            ?>
                        </tbody>
                    </table>

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
        <script>
//                        $(document).ready(function () {
//                            $('#dataTables').DataTable({
//                                "order": [[3, "desc"]]
//                            });
//                        });
        </script>
    </body>

</html>
