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
        desTables = function (div, db,tbl) {
            var div = parent.document.getElementById(div);
            $.post("describe.php", {dataBase: db,table: tbl}, function (htmlexterno) {
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
    </script>

    <body>
        <div id="wrapper">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <?php
                    @$mysqli = new mysqli(host, $user, $password, $base, port);
                    if ($mysqli->connect_error) {
                        die("Error en la conexion : " . $mysqli->connect_errno .
                                "-" . $mysqli->connect_error);
                        exit(0);
                    }

                    $sql = "desc " . $table;
                    $sentencia = $mysqli->query($sql);
                    $sql = "Select ";
                    $colum = array();
                    if ($sentencia->num_rows > 0) {
                        while ($row = $sentencia->fetch_assoc()) {
                            $sql.=" " . $row["Field"] . " ,";
                            $colum[] = $row["Field"];
                        }
                    }

                    $sql = substr($sql, 0, -1) . " FROM " . $table;
                    $sentencia = $mysqli->query($sql);
                    ?>
                    <div class="alert-success col-lg-12" >
                        <?php
                        echo $sql;
                        ?>
                    </div>
                    <a href="#noir" onclick="desTables('divContentAdmin', '<?php echo $base; ?>', '<?php echo $table; ?>')"><li class="fa fa-backward fa-fw"></li>Regresar</a>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-dataBase">
                        <thead>
                            <tr>
                                <?php
                                for ($index = 0; $index < count($colum); $index++) {
                                    ?>
                                    <th><?php echo $colum[$index]; ?></th>
                                    <?php
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            if ($sentencia->num_rows > 0) {
                                while ($row = $sentencia->fetch_assoc()) {
                                    echo "<tr class=\"odd gradeX\">";
                                    for ($index = 0; $index < count($colum); $index++) {
                                        echo "<td class=\"center\">" . $row[$colum[$index]] . "</td>";
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
