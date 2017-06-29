<?php
session_start();
include '../../fachadas/sigejupe/imagenes/ImagenesFacade.Class.php';

# toma los datos de URL
$idCarpetaJudicial = isset($_GET["idCarpetaJudicial"]) ? $_GET["idCarpetaJudicial"] : 0;
$idActuacion = isset($_GET["idActuacion"]) ? $_GET["idActuacion"] : 0;
//$tipoConsulta = isset($_GET["tipoConsulta"]) ? $_GET["tipoConsulta"] : 1;
//$idAudiencia = isset($_GET["idAudiencia"]) ? $_GET["idAudiencia"] : 0;

# consulta imagenes
$facadeImagenes = new ImagenesFacade();
$json = $facadeImagenes->getImagenes($idCarpetaJudicial, $idActuacion);

include_once(dirname(__FILE__) . "/../../tribunal/host/Host.Class.php");
$visorDocumentosExplorer = new Host(dirname(__FILE__) . "/../../tribunal/host/config.xml", "VISORDOCUMENTOSEXPLORER");
$visorDocumentosExplorer = $visorDocumentosExplorer->getConnect();

# arma ruta absoluta para documentos
$visor = new Host(dirname(__FILE__) . "/../../tribunal/host/config.xml", "VISORDOCUMENTOSSIGEJUPE");
$visor = $visor->getConnect();
$path = '//'.$visor->LISTARDOCUMENTOS->HOST.DIRECTORY_SEPARATOR;
?>

<html>
    <head>
        <link rel="stylesheet" href="visorImagenes.css" type="text/css">
        <script type="text/javascript" src="jquery.min.js"></script>
        <script src="../js/jquery-1.10.2.min.js"></script>
        <script src="../js/jquery-ui-1.10.4.custom.js"></script>                
        <link rel="stylesheet" href="../css/jquery-ui.css">        
        <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">-->
        <script type="text/javascript">
            var userAgent = navigator.userAgent.toLowerCase();
            var GlobalJson = <?php echo $json; ?>;
            //var GlobalCveAdscripcion = <?php #echo $_SESSION["cveAdscripcion"]; ?>;
            var checkTodo = true;
            var visorDocumentosExplorer = "<?php echo $visorDocumentosExplorer; ?>";

            jQuery.browser = {
                version: (userAgent.match(/.+(?:rv|it|ra|ie|me)[\/: ]([\d.]+)/) || [])[1],
                chrome: /chrome/.test(userAgent),
                safari: /webkit/.test(userAgent) && !/chrome/.test(userAgent),
                opera: /opera/.test(userAgent),
                msie: /msie/.test(userAgent) && !/opera/.test(userAgent),
                mozilla: /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent)
            };

            aniBotones = function () {
                $("img.imgBtnMenu").each(
                        function (i, e) {
                            $(this).mouseover(
                                    function () {
                                        $(this).css({'opacity': 1});
                                        $(this).css({'filter': 'alpha(opacity=100)'});
                                    }
                            );
                            $(this).mouseout(
                                    function () {
                                        $(this).css({'opacity': 0.30});
                                        $(this).css({'filter': 'alpha(opacity=30)'});
                                    }
                            );
                        }
                );
            };

            ocultaBotones = function () {
                $('li#liBtnZoomOut').css({'display': 'none'});
                $('li#liBtnZoomIn').css({'display': 'none'});
                $('li#liBtnRotar').css({'display': 'none'});
                $('li#liBtnImprimir').css({'display': 'none'});
                $('li#liBtnDownload').css({'display': 'none'});
                $('li#liBtnPri').css({'display': 'none'});
                $('li#liBtnAnt').css({'display': 'none'});
                $('li#liPaginas').css({'display': 'none'});
                $('li#liBtnSig').css({'display': 'none'});
                $('li#liBtnUlt').css({'display': 'none'});
                $('li#liBtnAcu').css({'display': 'none'});
                $('li#liBtnInfo').css({'display': 'none'});
                $('li#liBtnPass').css({'display': 'none'});
                $('li#liBtnCerrar').css({'display': 'none'});
                $('li#liBtnOrdenar').css({'display': 'none'});
                $('li#liBtnGuardar').css({'display': 'none'});
                $('li#liBtnBorrar').css({'display': 'none'});
                $('li#liBtnReturn').css({'display': 'none'});
                $('li#liBtnCheck').css({'display': 'none'});
            };

            mostrarBotonesDoc = function () {
                $('li#liBtnZoomOut').css({'display': 'block'});
                $('li#liBtnZoomIn').css({'display': 'block'});
                $('li#liBtnRotar').css({'display': 'none'});
                $('li#liBtnImprimir').css({'display': 'block'});
                if (Indice == 0) {
                    $('li#liBtnPri').css({'display': 'none'});
                    $('li#liBtnAnt').css({'display': 'none'});
                    if (arrFoja.length > 1) {
                        $('li#liPaginas').css({'display': 'block'});
                    } else {
                        $('li#liPaginas').css({'display': 'none'});
                    }
                    $('li#liBtnSig').css({'display': 'none'});
                    $('li#liBtnUlt').css({'display': 'none'});
                    $('li#liBtnAcu').css({'display': 'none'});
                }
            };

            mostrarBotonesImg = function () {
                $('li#liBtnZoomOut').css({'display': 'block'});
                $('li#liBtnZoomIn').css({'display': 'block'});
                $('li#liBtnRotar').css({'display': 'none'});
                $('li#liBtnImprimir').css({'display': 'block'});
                $('li#liBtnDownload').css({'display': 'block'});
                $('li#liBtnInfo').css({'display': 'none'});
                $('li#liBtnPass').css({'display': 'none'});
                $('li#liBtnCerrar').css({'display': 'none'});
                if (Indice == 0) {
                    $('li#liBtnPri').css({'display': 'none'});
                    $('li#liBtnAnt').css({'display': 'none'});
                    $('li#liPaginas').css({'display': 'block'});
                    $('li#liBtnSig').css({'display': 'block'});
                    $('li#liBtnUlt').css({'display': 'block'});
                    $('li#liBtnAcu').css({'display': 'none'});
                }
            };

            mostrarBotonesPdf = function () {
                $('li#liBtnZoomOut').css({'display': 'none'});
                $('li#liBtnZoomIn').css({'display': 'none'});
                $('li#liBtnRotar').css({'display': 'none'});
                $('li#liBtnImprimir').css({'display': 'none'});
                $('li#liBtnDownload').css({'display': 'block'});
                $('li#liBtnOrdenar').css({'display': 'block'});
                $('li#liBtnGuardar').css({'display': 'none'});
                $('li#liBtnReturn').css({'display': 'none'});
                $('li#liBtnBorrar').css({'display': 'none'});
                $('li#liBtnCheck').css({'display': 'none'});
                $('li#liBtnInfo').css({'display': 'none'});
                $('li#liBtnPass').css({'display': 'none'});
                $('li#liBtnCerrar').css({'display': 'none'});
                if (Indice == 0) {
                    $('li#liBtnPri').css({'display': 'none'});
                    $('li#liBtnAnt').css({'display': 'none'});
                    $('li#liPaginas').css({'display': 'block'});
                    $('li#liBtnSig').css({'display': 'block'});
                    $('li#liBtnUlt').css({'display': 'block'});
                    $('li#liBtnAcu').css({'display': 'none'});
                }
            };

            zoomImg = function (zm) {
                var strExtension = Extension(arrFoja[Indice].ruta);
                if (strExtension == "gif") {
                    wid = parseInt($('img#imgFojaImg').width(), 10);
                    ht = parseInt($('img#imgFojaImg').height(), 10);
                    $('img#imgFojaImg').css({'width': (wid * zm) + 'px'});
                    $('img#imgFojaImg').css({'height': (ht * zm) + 'px'});
                } else if (strExtension == "doc") {
                    var max = 20;
                    var min = 10;
                    if ($('div#divHojaDoc').css('fontSize') == "") {
                        $('div#divHojaDoc').css({'fontSize': '100%'});
                    }
                    actual = parseInt($('div#divHojaDoc').css('fontSize'), 10);
                    increment = 1;

                    if (zm == "restore") {
                        $('div#divHojaDoc').css({'fontSize': '100%'});
                    }

                    if (zm > 1 && ((actual + increment) <= max)) {
                        value = actual + increment;
                        $('div#divHojaDoc').css({'fontSize': value + 'px'});
                    } else if (zm < 1 && ((actual + increment) >= min)) {
                        value = actual - increment;
                        $('div#divHojaDoc').css({'fontSize': value + 'px'});
                    }
                }
            };

            imprimirFoja = function () {
//                var strExtension = Extension(arrFoja[Indice].ruta);
//                if (strExtension == "gif") {
//                    printDivIE('divHojaGif');
//                } else if (strExtension == "doc") {
//                    printDivIE('divHojaDoc');
//                }



//                window.print();
//                this.close();


                var ficha = document.getElementById("pageContainer1");
                alert(ficha.innerHTML);
//                var ventimp = window.open(' ', 'popimpr');
//                ventimp.document.write( ficha.innerHTML );
//                ventimp.document.close();
//                ventimp.print( );
//                ventimp.close();
            };

            printDivIE = function (divID) {
                var w = window.open('', 'imprimeArea', 'width=50,height=50,location=no,menubar=no,resizable=no,scrollbars=no,status=no');
                w.document.write("<html><head><title>Poder Judicial del Estado de MÈxico</title></head><body>");
                w.document.write(document.getElementById(divID).innerHTML);
                w.document.write('</body></html>');
                w.document.close();
                w.focus();o
                w.print();
                w.close();
            };

            rotarImg = function (id) {
                if (parseInt($("img#" + id).getRotateAngle(), 10) == 180) {
                    $("img#" + id).rotate(0);
                } else {
                    $("img#" + id).rotate(180);
                }
            };

            muestraDoc = function (idFoja, TipoDoc, Foja, idDocumentoImagen, cveOficialia, cveTipoActuacion, principal) {
                var idCarpetaJudicial = <?php echo $idCarpetaJudicial; ?>;
                var idActuacion = <?php echo $idActuacion; ?>;
                var muestraVisor = true;
                //console.log('Ruta: '+Foja)
                switch (TipoDoc) {
                    case "pdf":
                        mostrarBotonesPdf();
                        if (principal == "S") {
                            if (cveTipoActuacion == "1" || cveTipoActuacion == "2" || cveTipoActuacion == "3" || cveTipoActuacion == "10") 
                            {
                                if (GlobalCveAdscripcion == cveOficialia) {
                                    $('li#liBtnOrdenar').css({'display': 'block'});
                                } else {
                                    $('li#liBtnOrdenar').css({'display': 'none'});
                                }
                            }
                        }
                        $('#divFoja').css({"height": "100%"});
                        $('#divFoja').html("<iframe id=\"frame\" style=\"height: 500px;\" width=\"100%\" src=\"\"></iframe>");
                        var iframe = $('#frame');

                        // Detectamos si nos visitan desde IE
                        var iev = 0;
                        var ieold = (/MSIE (\d+\.\d+);/.test(navigator.userAgent));
                        var trident = !!navigator.userAgent.match(/Trident\/7.0/);
                        var rv = navigator.userAgent.indexOf("rv:11.0");

                        if (ieold)
                            iev = new Number(RegExp.$1);
                        if (navigator.appVersion.indexOf("MSIE 10") != -1)
                            iev = 10;
                        if (trident && rv != -1)
                            iev = 11;
                        if (iev > 0) {
                            iframe.attr('src', visorDocumentosExplorer + "viewer.php?archivo=" + Foja + "&idFoja=" + idFoja + "&idCarpetaJudicial=" + idCarpetaJudicial + "&idActuacion=" + idActuacion + "&idDocumentoImagen=" + idDocumentoImagen);
                        } else {
                            iframe.attr('src', 'viewer.php?archivo=' + Foja + "&idFoja=" + idFoja + "&idCarpetaJudicial=" + idCarpetaJudicial + "&idActuacion=" + idActuacion + "&idDocumentoImagen=" + idDocumentoImagen);
                        }
                        break;
                    case "jpg":
                    case "jpeg":
                    case "bmp":
                    case "gif":
                        mostrarBotonesImg();
                        $('#divFoja').css({"height": "100%"});
                        $('div#divFoja').html("");
                        var strFojaImg = "<div id=\"divHojaGif\" class=\"divFojaImgDoc divHojaGif\">\n";
                        strFojaImg += "<img id=\"imgFojaImg\" class=\"imgFojaImg\" src=\"" + Foja + "\" onload=\"calculaAnchoAlto(this.id);\"></img>\n";
                        strFojaImg += "</div>\n";
                        $('div#divFoja').append(strFojaImg);
                        break;
                }
            };

            calculaAnchoAlto = function (idImg) {
                maxW = $('div#divHojaGif').width() - 10;
                newW = maxW;

//    if(Img.width > Img.height){
                newW = maxW;
                newH = $('img#' + idImg).height() / ($('img#' + idImg).width() / maxW);
//    } else{
//            newW = Img.width/(Img.height/maxW);
//            newH = maxW;
//    }

                $('img#' + idImg).width(newW);
                $('img#' + idImg).height(newH);
                $('img#' + idImg).draggable();
            };

            Extension = function (Archivo) {
                return Archivo.substr((Archivo.length - 3), 3);
            };

            Navegar = function (Opc) {
                var json = eval(GlobalJson);
                var strPagDoc;
                var indice = $("select#cmbPag").prop("selectedIndex");
                var idSig;

                $('li#liBtnPri').css({'display': "block"});
                $('li#liBtnAnt').css({'display': "block"});
                $('li#liBtnSig').css({'display': "block"});
                $('li#liBtnUlt').css({'display': "block"});
                switch (Opc) {
                    case "ImgSig":
                        if (Indice < (TotPag - 1)) {
                            if (arrFoja[Indice + 1])
                                Indice++;
                            $('select#cmbPag option')[Indice].selected = true;
                        }
                        if (Indice == (TotPag - 1)) {
                            $('li#liBtnSig').css({'display': "none"});
                            $('li#liBtnUlt').css({'display': "none"});
                        }
                        break;
                    case "ImgUlt":
                        Indice = TotPag - 1;
                        $('select#cmbPag option')[Indice].selected = true;
                        $('li#liBtnSig').css({'display': "none"});
                        $('li#liBtnUlt').css({'display': "none"});
                        break;
                    case "ImgAnt":
                        if (Indice > 0) {
                            if (arrFoja[Indice - 1])
                                Indice--;
                            $('select#cmbPag option')[Indice].selected = true;
                        }
                        if (Indice == 0) {
                            $('li#liBtnPri').css({'display': "none"});
                            $('li#liBtnAnt').css({'display': "none"});
                        }
                        break;
                    case "ImgPri":
                        Indice = 0;
                        $('select#cmbPag option')[Indice].selected = true;
                        $('li#liBtnPri').css({'display': "none"});
                        $('li#liBtnAnt').css({'display': "none"});
                        break;
                    case "BrincaImg":
                        Indice = $("select#cmbPag").prop("selectedIndex")
                        if (Indice == 0) {
                            $('li#liBtnPri').css({'display': "none"});
                            $('li#liBtnAnt').css({'display': "none"});
                        } else if (Indice == (TotPag - 1)) {
                            $('li#liBtnSig').css({'display': "none"});
                            $('li#liBtnUlt').css({'display': "none"});
                        }
                        break;
                }

                $('divFoja').html("");
                muestraDoc(arrFoja[Indice].idImagen, Extension(arrFoja[Indice].ruta), /*arrFoja[Indice].ruta.substr(3)*/ arrFoja[Indice].ruta, arrFoja[Indice].idDocumentoImg, arrFoja[Indice].DatosCarpetajudicial.cveOficialia, json.tipoDocumento.cveTipoActuacion, json.tipoDocumento.principal);
            };

            CierraSes = function () {
                if (FinSesion == true) {
                    document.frmDatos.target = "_self";
                    document.frmDatos.action = "BorraSesion.php";
                    document.frmDatos.submit();
                }
            };

            Cerrar = function () {
                FinSesion = true;
                CierraSes();
            };

            VerInfo = function () {
                $("div#divInfoUsu").dialog({
                    modal: true,
                    width: 350,
                    height: 140
                });
            };

            generar = function () {
                var json = eval(GlobalJson);
                $('#divFoja').css({'display': "block"});
                if (json.data.type == "Error") {
                    ocultaBotones();
                    $('#divFoja').html("<span class=\"Arial14\"><b>" + json.data.text + "</b></span>");
                } else {
                    if (json.totalCount > 0) {
                        $('div#divFoja').html("");
        
                        var strPagDoc;
                        var x;
                        var strCombo = "";
                        arrFoja = json.data;
                        Indice = 0;
                        TotPag = json.totalCount;
                        
                        // arma URL absoluta
                        for( var i = 0; i < arrFoja.length; i++)
                        {
                            arrFoja[i].ruta = arrFoja[i].ruta.replace("../../../", "<?php echo $path; ?>");
                            //console.log(arrFoja[i].ruta)
                        }
                                                    
                        
                        strCombo = '<select id="cmbPag" name="cmbPag" size="1" class="frmcaja" onChange="Navegar(\'BrincaImg\');">';
                        for (x = 0; x < TotPag; x++) {
                            if (x == 0)
                                var sel = " selected";
                            else
                                var sel = "";
                            strCombo += '<option value="' + x + '"' + sel + '>' + (x + 1) + '</option>';
                        }
                        strCombo += '</select>';
                        $('span#Contador').html(strCombo);
                        $('span#ConTotPag').html(TotPag);

                        if (TotPag > 1) {
                            if (Extension(arrFoja[TotPag - 1].ruta) == "doc") {
                                $('li#liBtnAcu').css({'display': "block"});
                            } else {
                                $('li#liBtnAcu').css({'display': "none"});
                            }
                        }
                        muestraDoc(arrFoja[Indice].idImagen, Extension(arrFoja[Indice].ruta), arrFoja[Indice].ruta/* arrFoja[Indice].ruta*/, arrFoja[Indice].idDocumentoImg, arrFoja[Indice].DatosCarpetajudicial.cveOficialia, json.tipoDocumento.cveTipoActuacion, json.tipoDocumento.principal);
                        $('li#liBtnPri').css({'display': "none"});
                        $('li#liBtnAnt').css({'display': "none"});
                        if (TotPag == 1) {
                            $('li#liBtnSig').css({'display': "none"});
                            $('li#liBtnUlt')
                                    .css({'display': "none"});
                        } else {
                            $('li#liBtnSig').css({'display': "block"});
                            $('li#liBtnUlt').css({'display': "block"});
                        }
                    } else {
                        $('divFoja').html("<span class=\"Arial14\"><b>No hay documentos para esta secci√≥n del expediente!</b></span>");
                    }
                }

            }

            descargarFoja = function () {
                try {
                    var frmDownload = document.getElementById("frmDownload");
                    document.body.removeChild(frmDownload);
                } catch (r) { }
                var json = eval(GlobalJson);
                var rutas = "";
                for (var count = 0; count < json.data.length; count++) {
                    rutas += json.data[count].ruta + ",";
                }
                var formulario = document.createElement("form");
                var rutaImagen = document.createElement("input");
                var nombreImagen = document.createElement("input");
                var action = document.createElement("input");

                formulario.action = "../../controller/imagenes/DescargaImagenesController.Class.php";
                formulario.name = "frmDownload";
                formulario.id = "frmDownload";
                formulario.method = "POST";
//                    formulario.target = "_parent";
                formulario.target = "_blank";

                action.name = "action";
                action.value = "descargar";
                action.style.display = "none";
                formulario.appendChild(action);

                rutaImagen.name = "ruta";
                rutaImagen.value = rutas;
                rutaImagen.style.display = "block";
                formulario.appendChild(rutaImagen);

                nombreImagen.name = "nombre";
                nombreImagen.value = arrFoja[Indice].idDocumentoImg;
                nombreImagen.style.display = "none";
                formulario.appendChild(nombreImagen);

                document.body.appendChild(formulario);
                var frmDownload = document.getElementById("frmDownload");
                frmDownload.submit();
            }

            actualizarArchivos = function () {
                $('#divGestorDocs').html("");
                var json = eval(GlobalJson);
                if (json.data.type == "Error") {
                    ocultaBotones();
                    $('#divGestorDocs').html("<span class=\"Arial14\"><b>" + json.data.text + "</b></span>");
                } else {
                    ocultaBotones();
                    var idCarpetaJudicial = <?php echo $idCarpetaJudicial; ?>;
                    var idActuacion = <?php echo $idActuacion; ?>;

                    document.getElementById("tdVisorDocs").style.display = "none";
                    document.getElementById("tdGestorDocs").style.display = "block";
                    var divGestorDocs = document.getElementById("divGestorDocs");
                    var arrFojaGestor = json.data;
                    $('#divGestorDocs').append("<ul id='sortable'></ul>");
                    for (var index = 0; index < json.data.length; index++) {
                        var arrayRuta = json.data[index].ruta.split("/");
                        $('#sortable').append("<li id='liDocumentos" + index + "' class='ui-state-default'>" +
                                "<div>Nombre: " + arrayRuta[arrayRuta.length - 1] +
                                "<br>Posicion Original: " + json.data[index].posicion +
                                "<div><input type=\"checkbox\" name=\"chkArchivo\"/></input></div>" +
                                "</div></li>");
//                        $('#liDocumentos' + index).append(json.data[index].idImagen);
                        $('#liDocumentos' + index).append("<input type='hidden' id='hdnIdImagen" + index + "' name='hdnIdImagen' value='" + json.data[index].idImagen + "'></input>");
                        $('#liDocumentos' + index).append("<input type='hidden' id='hdnIdDocumentoImagen" + index + "' name='hdnIdDocumentoImagen' value='" + json.data[index].idDocumentoImg + "'></input>");
                        $('#liDocumentos' + index).append("<input type='hidden' id='hdnOrdenOriginal" + index + "' name='hdnOrdenOriginal' value='" + json.data[index].posicion + "'></input>");
                        /*
                         $('#liDocumentos' + index).append("<iframe id=\"frame" + index + "\"  src=\"\">1</iframe>");
                         var iframe = $('#frame' + index);
                         iframe.attr('src', 'viewer.php?archivo=../' + arrFojaGestor[index].ruta.substr(3) + '&tipoConsulta=' + tipoConsulta + "&idUsuarioExterno=" + idUsuarioExterno + "&idFoja=" + arrFojaGestor[index].idImagen +
                         "&idCarpetaJudicial=" + idCarpetaJudicial + "&idActuacion=" + idActuacion + "&idDocumentoImagen=" + arrFojaGestor[index].idDocumentoImg);
                         iframe.attr('src', "../" + arrFojaGestor[index].ruta.substr(3));
                         */
                    }
                    $("#sortable").sortable();
                    $("#sortable").disableSelection();
                    $('li#liBtnOrdenar').css({'display': 'block'});
                    $('li#liBtnGuardar').css({'display': 'block'});
                    $('li#liBtnReturn').css({'display': 'block'});
                    $('li#liBtnCheck').css({'display': 'block'});
                    $('li#liBtnBorrar').css({'display': 'block'});
                }
            }

            guardarcambios = function () {
                var longitud = document.getElementsByName('hdnIdImagen').length;
                if (longitud > 0) {
                    var json = '{';
                    json += '"totalCount": "' + longitud + '",';
                    json += '"data": [';
                    for (var index = 0; index < longitud; index++) {
                        json += '{';
                        json += '"idImagen": "' + document.getElementsByName('hdnIdImagen')[index].value + '",';
                        json += '"idDocumentoImg": "' + document.getElementsByName('hdnIdDocumentoImagen')[index].value + '",';
                        json += '"posicionOrigen": "' + document.getElementsByName('hdnOrdenOriginal')[index].value + '",';
                        json += '"posicionFinal": "' + (index + 1) + '"';
                        json += '},';
                    }
                    json = json.substring(0, json.length - 1);
                    json += ']';
                    json += '}';
                }

                if (json != "") {
                    var idCarpetaJudicial = <?php echo $idCarpetaJudicial; ?>;
                    var idActuacion = <?php echo $idActuacion; ?>;
                    $.ajax({
                        type: "POST",
                        url: "../../controller/imagenes/ImagenesController.Class.php",
                        data: {action: "actualizaOrden", datosImagenes: json, idCarpetaJudicial: idCarpetaJudicial, idActuacion: idActuacion},
                        async: false,
                        dataType: "html",
                        beforeSend: function (objeto) {
                            $('#divGestorDocs').html("<span class=\"Arial14\"><b>Actualizando orden de las Imagenes</b></span>");
                        },
                        success: function (datos) {
                            if (datos !== "") {
                                GlobalJson = eval("(" + datos + ")");
                                actualizarArchivos();
                            } else {
                                $('#divGestorDocs').html("<span class=\"Arial14\"><b>Error Actualizar orden de las Imagenes</b></span>");
                            }
                        },
                        error: function (objeto, quepaso, otroobj) {
                            $('#divGestorDocs').html("<span class=\"Arial14\"><b>Error Actualizar orden de las Imagenes</b></span>");
                        }
                    });
                }
            }

            checkList = function () {
                var chkLength = document.getElementsByName('chkArchivo').length;
                if (chkLength > 0) {
                    for (var index = 0; index < chkLength; index++) {
                        document.getElementsByName('chkArchivo')[index].checked = checkTodo;
                    }
                    if (checkTodo) {
                        checkTodo = false;
                    } else {
                        checkTodo = true;
                    }
                }
            }

            borrarSeleccionados = function () {
                var idCarpetaJudicial = <?php echo $idCarpetaJudicial; ?>;
                var idActuacion = <?php echo $idActuacion; ?>;

                var chkLength = document.getElementsByName('chkArchivo').length;
                var hdnIdImagen = document.getElementsByName('hdnIdImagen').length;
                var idImagenesBorrar = "";
                if (chkLength > 0) {
                    for (var index = 0; index < chkLength; index++) {
                        if (document.getElementsByName('chkArchivo')[index].checked) {
                            idImagenesBorrar += document.getElementsByName('hdnIdImagen')[index].value + ",";
                        }
                    }
                }

                if (idImagenesBorrar != "") {
                    if (confirm("Est· seguro de eliminar las imagenes seleccionadas?")) {
                        idImagenesBorrar = idImagenesBorrar.substring(0, idImagenesBorrar.length - 1);
                        $.ajax({
                            type: "POST",
                            url: "../../controller/imagenes/ImagenesController.Class.php",
                            data: {action: "borrarImagenes", idImagenesBorrar: idImagenesBorrar, idCarpetaJudicial: idCarpetaJudicial, idActuacion: idActuacion},
                            async: false,
                            dataType: "html",
                            beforeSend: function (objeto) {
                                $('#divGestorDocs').html("<span class=\"Arial14\"><b>Borrando Imagenes Seleccionadas</b></span>");
                            },
                            success: function (datos) {
                                if (datos !== "") {
                                    GlobalJson = eval("(" + datos + ")");
                                    actualizarArchivos();
                                } else {
                                    $('#divGestorDocs').html("<span class=\"Arial14\"><b>Error Actualizar orden de las Imagenes</b></span>");
                                }
                            },
                            error: function (objeto, quepaso, otroobj) {
                                $('#divGestorDocs').html("<span class=\"Arial14\"><b>Error Actualizar orden de las Imagenes</b></span>");
                            }
                        });
                    }
                } else {
                    alert("Seleccione un Archivo!");
                }
            }

            regresaVisor = function () {
                document.getElementById("tdVisorDocs").style.display = "block";
                document.getElementById("tdGestorDocs").style.display = "none";
                generar();
            }

            $(document).ready(function () {
                $('#divFoja').html("");
                $('#divFoja').css({'display': "none"});
                $("img.imgBtnMenu").each(
                        function (i, e) {
                            $(this).css({'opacity': 0.30});
                            $(this).css({'filter': 'alpha(opacity=30)'});
                        }
                );
                aniBotones();
                ocultaBotones();
                generar();
            });
        </script>
        <style>
            #sortable { 
                list-style-type: none; 
                margin: 0; 
                padding: 0; 
                width: 100%; 
            }
            #sortable li { 
                cursor: grab;
                margin: 3px 3px 3px 0; 
                padding: 1px; 
                float: left; 
                /*width: 100px;*/ 
                width: 25%; 
                height: 90px; 
                /*font-size: 4em;*/ 
                text-align: center; 
            }
            #sortable li div{
                color: #000000;
                padding: 1px; 
                float: left; 
                text-align: left; 
                font-weight: lighter;

            }
            #sortable li div div{
                float: right;
            }
        </style>
    </head>
    <body>
        <table id="tblContenido" style="width:98%; height:700px;" cellpadding="0" cellspacing="0">
            <tr>
                <td height="1%" align="center" style="height: 48px; background: #2e8463; /* Old browsers */
                    background: -moz-linear-gradient(top,  #2e8463 0%, #6ead99 100%); /* FF3.6+ */
                    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#2e8463), color-stop(100%,#6ead99)); /* Chrome,Safari4+ */
                    background: -webkit-linear-gradient(top,  #2e8463 0%,#6ead99 100%); /* Chrome10+,Safari5.1+ */
                    background: -o-linear-gradient(top,  #2e8463 0%,#6ead99 100%); /* Opera 11.10+ */
                    background: -ms-linear-gradient(top,  #2e8463 0%,#6ead99 100%); /* IE10+ */
                    background: linear-gradient(to bottom,  #2e8463 0%,#6ead99 100%); /* W3C */
                    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2e8463', endColorstr='#6ead99',GradientType=0 ); /* IE6-9 */

                    ">

                    <table id="tblMenuDoc" border="0" cellpadding="0" cellspacing="0" style="height: 36px;">
                        <tr>
                            <td align="center" valign="top" height="36">
                                <!--<img src="imgMenuVisor/imgBordeMenu-Izq.png" border="0"></img>-->
                            </td>
                            <td valign="top">
                                <ul id="ulMenuSup" style="height: 48px; background: #2e8463; /* Old browsers */
                                    background: -moz-linear-gradient(top,  #2e8463 0%, #6ead99 100%); /* FF3.6+ */
                                    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#2e8463), color-stop(100%,#6ead99)); /* Chrome,Safari4+ */
                                    background: -webkit-linear-gradient(top,  #2e8463 0%,#6ead99 100%); /* Chrome10+,Safari5.1+ */
                                    background: -o-linear-gradient(top,  #2e8463 0%,#6ead99 100%); /* Opera 11.10+ */
                                    background: -ms-linear-gradient(top,  #2e8463 0%,#6ead99 100%); /* IE10+ */
                                    background: linear-gradient(to bottom,  #2e8463 0%,#6ead99 100%); /* W3C */
                                    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2e8463', endColorstr='#6ead99',GradientType=0 ); /* IE6-9 */

                                    ">
                                    <li id="liBtnZoomOut" class="opcMenuSup"><img name="BtnZoomOut" id="BtnZoomOut" src="imgMenuVisor/btnZoom-Out.png" title="Zoom -" onClick="zoomImg(0.9);" class="imgBtnMenu"></li>
                                    <li id="liBtnZoomIn" class="opcMenuSup"><img name="BtnZoomIn" id="BtnZoomIn" src="imgMenuVisor/btnZoom-In.png" title="Zoom +" onClick="zoomImg(1.1);" class="imgBtnMenu"></li>
                                    <!--<li id="liBtnRecargar" class="opcMenuSup"><img name="BtnRecargar" id="BtnRecargar" src="imgMenuVisor/btnRecargar.png" title="Recargar" onClick="" class="imgBtnMenu"></li>-->
                                    <li id="liBtnRotar" class="opcMenuSup"><img name="BtnRotar" id="BtnRotar" src="imgMenuVisor/btnRotar.png" title="Rotar" onClick="rotarImg('imgFojaImg');" class="imgBtnMenu"></li>
                                    <!--<li id="liBtnPDF" class="opcMenuSup"><img name="BtnPDF" id="BtnPDF" src="imgMenuVisor/btnPDF.png" title="Exportar a PDF" onClick="" class="imgBtnMenu"></li>-->
                                    <!--<li id="liBtnCopiar" class="opcMenuSup"><img name="BtnCopiar" id="BtnCopiar" src="imgMenuVisor/btnCopiar.png" title="Copiar texto" onClick="" class="imgBtnMenu"></li>-->
                                    <li id="liBtnImprimir" class="opcMenuSup"><img name="BtnImprimir" id="BtnImprimir" src="imgMenuVisor/btnImprimir.png" title="Imprimir" onClick="imprimirFoja();" class="imgBtnMenu"></li>
                                    <li id="liBtnDownload" class="opcMenuSup"><img name="BtnDownload" id="BtnDownload" src="imgMenuVisor/btnDownload.png" title="Descargar" onClick="descargarFoja();" class="imgBtnMenu"></li>
                                    <li id="liBtnPri" class="opcMenuSup"><img name="BtnPri" id="BtnPri" src="imgMenuVisor/btnPrimero.png" title="Primero" onClick="Navegar('ImgPri');" class="imgBtnMenu"></li>
                                    <li id="liBtnAnt" class="opcMenuSup"><img name="BtnAnt" id="BtnAnt" src="imgMenuVisor/btnAnterior.png" title="Anterior" onClick="Navegar('ImgAnt');" class="imgBtnMenu"></li>
                                    <li id="liPaginas" class="opcMenuSup Arial14" style=""><div style="margin-top: 7px;">p&aacute;g.&nbsp;<span id="Contador" style="display:inline;"></span>&nbsp;de&nbsp;<span id="ConTotPag" style="display:inline;" class="imgBtnMenu"></span></div></li>
                                    <li id="liBtnSig" class="opcMenuSup"><img name="BtnSig" id="BtnSig" src="imgMenuVisor/btnSiguiente.png" title="Siguiente" onClick="Navegar('ImgSig');" class="imgBtnMenu"></li>
                                    <li id="liBtnUlt" class="opcMenuSup"><img name="BtnUlt" id="BtnUlt" src="imgMenuVisor/btnUltimo.png" title="&Uacute;ltimo" onClick="Navegar('ImgUlt');" class="imgBtnMenu"></li>
                                    <li id="liBtnAcu" class="opcMenuSup"><img name="BtnAcu" id="BtnAcu" src="imgMenuVisor/btnAcuerdo.png" title="Ver acuerdo" onClick="" class="imgBtnMenu"></li>
                                    <li id="liBtnInfo" class="opcMenuSup"><img name="BtnInfo" id="BtnInfo" src="imgMenuVisor/btnInfo.png" title="Informaci&oacute;n de cuenta" onClick="VerInfo();" class="imgBtnMenu"></li>
                                    <li id="liBtnPass" class="opcMenuSup"><img name="BtnPass" id="BtnPass" src="imgMenuVisor/btnPassword.png" title="Cambiar contrase&ntilde;a" onClick="verEncuesta();" class="imgBtnMenu"></li>
                                    <li id="liBtnCerrar" class="opcMenuSup"><img name="BtnCerrar" id="BtnCerrar" src="imgMenuVisor/btnCerrar.png" title="Cerrar" onClick="Cerrar();" class="imgBtnMenu"></li>
                                    <li id="liBtnOrdenar" class="opcMenuSup"><img name="BtnOrdenar" id="BtnOrdenar" src="imgMenuVisor/btnOrdenar.png" title="Modificar Imagenes" onClick="actualizarArchivos();" class="imgBtnMenu"></li>
                                    <li id="liBtnGuardar" class="opcMenuSup"><img name="BtnGuardar" id="BtnGuardar" src="imgMenuVisor/btnGuardar.png" title="Guardar Cambios" onClick="guardarcambios();" class="imgBtnMenu"></li>
                                    <li id="liBtnCheck" class="opcMenuSup"><img name="BtnCheck" id="BtnGuardar" src="imgMenuVisor/btnCheck.png" title="Seleccionar Todo/Ninguno" onClick="checkList();" class="imgBtnMenu"></li>
                                    <li id="liBtnBorrar" class="opcMenuSup"><img name="BtnBorrar" id="BtnBorrar" src="imgMenuVisor/btnBorrar.png" title="Borrar seleccionados" onClick="borrarSeleccionados();" class="imgBtnMenu"></li>
                                    <li id="liBtnReturn" class="opcMenuSup"><img name="BtnReturn" id="BtnReturn" src="imgMenuVisor/btnReturn.png" title="Regresar Visor" onClick="regresaVisor();" class="imgBtnMenu"></li>
                                </ul>
                            </td>
                            <td align="center" valign="top">
                                <!--<img src="imgMenuVisor/imgBordeMenu-Der.png" border="0"></img>-->
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr>
                <td id="tdVisorDocs" height="99%" align="center">
                    <div id="divFoja" name="divFoja"></div>
                </td>
            </tr>
            <tr>
                <td id="tdGestorDocs" height="99%" align="center">
                    <div id="divGestorDocs" name="divGestorDocs"></div>
                </td>
            </tr>
        </table>
    </body>
</html>
