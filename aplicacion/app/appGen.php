<?php
include_once(dirname(__FILE__) . "/appConfig.php");

$user = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

@$mysqli = new mysqli(host, $user, $password, "", port);
if ($mysqli->connect_error) {
    die("Error en la conexion : " . $mysqli->connect_errno .
            "-" . $mysqli->connect_error);
    exit(0);
}
$bases = "";
$sql = "show databases";
$dataBases = $mysqli->query($sql);
if ($dataBases->num_rows > 0) {
    $bases = array();
    while ($row = $dataBases->fetch_assoc()) {
        $bases[] = $row["Database"];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo appName; ?></title>

        <!-- Bootstrap Core CSS -->
        <link href="appGen/components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="appGen/components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <!-- Timeline CSS -->
        <!--<link href="appGen/dist/css/timeline.css" rel="stylesheet">-->

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
        seleccionaDB = function (div, db) {
            $.post("admin.php", {dataBase: db}, function (htmlexterno) {
                $("#"+div).html(htmlexterno);
            });
        }
        config = function (div) {
            $.post("config.php", function (htmlexterno) {
                $("#"+div).html(htmlexterno);
            });
        }
        appGen = function (div) {
            $.post("build.php", function (htmlexterno) {
                $("#"+div).html(htmlexterno);
            });
        }
    </script>

    <body>

        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">AppGen</a>
                </div>


                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li class="sidebar-search">
                                <div class="input-group custom-search-form">
                                    <input type="text" class="form-control" placeholder="Search...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                                <!-- /input-group -->
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-database fa-fw"></i> Databases<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <?php
                                    for ($index = 0; $index < count($bases); $index++) {
                                        echo "<li><a href=\"#noir\" onclick=\"seleccionaDB('divContent','" . $bases[$index] . "')\">" . $bases[$index] . "</a></li>";
                                    }
                                    ?>
                                </ul>
                                <!--/.nav-second-level--> 
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-building fa-fw"></i> Build Application<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li><a href="#noir" onclick="config('divContent')">Config Build</a></li>
                                    <li><a href="#noir" onclick="appGen('divContent')">Build</a></li>
                                </ul>
                                <!--/.nav-second-level--> 
                            </li>
                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>

            <div id="page-wrapper">
                <div class="row" id="divContent">

                </div>
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="appGen/components/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="appGen/components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="appGen/components/metisMenu/dist/metisMenu.min.js"></script>

        <!-- Morris Charts JavaScript -->
        <!--<script src="appGen/components/raphael/raphael-min.js"></script>-->
        <!--<script src="appGen/components/morrisjs/morris.min.js"></script>-->
        <!--<script src="appGen/js/morris-data.js"></script>-->

        <!-- Custom Theme JavaScript -->
        <script src="appGen/dist/js/sb-admin-2.js"></script>

    </body>

</html>
