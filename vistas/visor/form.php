<!-- ejemplo para llmara al visor -->
<html>
    <head>
        <link rel="stylesheet" href="visorImagenes.css" type="text/css">
        <script type="text/javascript" src="jquery.min.js"></script>
        <script src="../js/jquery-1.10.2.min.js"></script>
        <script src="../js/jquery-ui-1.11.14.js"></script>                
        <script>
    /**
     * 
     * @param int idCarpetaJudicial
     * @param int idActuacion
     * @returns NULL
     */
    getImagenes = function (idCarpetaJudicial, idActuacion){
        $('#iPopUpVisor').attr('src', document.getElementById("srcVisor").value)
        var iPopUpDigitalizacion = document.getElementById("iPopUpVisor")
        iPopUpDigitalizacion.src = iPopUpDigitalizacion.src + "?idCarpetaJudicial=" + idCarpetaJudicial + "&idActuacion=" + idActuacion
        console.log("URL: " + iPopUpDigitalizacion.src);
        $("#divPopUpVisor").show();
};

$(function () 
{
	getImagenes(1,0)
});

</script>

    </head>
    <body>
        <input type="hidden" id="srcVisor" value="index.php" />
        <input type="hidden" id="idUsuarioExterno" value="2187" />
        <div id="divPopUpVisor" title="Documentos">                   
            <iframe id="iPopUpVisor" style="width:100%; height:700px"  frameborder="0" marginheight="0" marginwidth="0" allowtransparency></iframe>
        </div>
    </body>
</html>


<!-- Para probar imagenes -->
<!--<form action="./../../../fachadas/sigejupe/imagenes/ImagenesFacade.Class.php" 
      method="post" enctype="multipart/form-data">
  
  <label>ID carpeta judicial</label>
  <input type="text" name="idCarpetaJudicial" value="1">
  <br>
  
  <label>ID actuacion</label>
  <input type="text" name="idActuacion" value="">
  <br>
  
  <label>clave tipo documento</label>
  <input type="text" name="cveTipoDocumento" value="1">
  <br>
  
  <label>Archivo</label>
  <input type="file" name="archivo" id="archivo">
  <br><br>
  <input type="hidden" name="accion" value="guardar">
  <input type="submit">
</form>-->

<!-- para probar consulta externa -->
<!--<form action="./../../../fachadas/sigejupe/consultaexterna/ConsultaexternaFacade.Class.php" 
      method="post" enctype="multipart/form-data">
  
  <label>ID persona autorizada</label>
  <input type="text" name="idPersonaautorizada" value="1">
  <br>
  
  <label>ID carpeta judicial</label>
  <input type="text" name="IdCarpetajudicial" value="">
  <br>
  
  <label>ID detalle carpeta judicial</label>
  <input type="text" name="idCj" value="">
  <br>
  
  <label>ID detalle actuaci&oacute;n</label>
  <input type="text" name="idAct" value="">
  <br>
    
  <select name="accion" id="accion">
    <option value="consultarpersona">Consultar por persona</option>
    <option value="consultarcarpetas">Consultar por carpeta</option>
    <option value="consultardetallecarpeta">Consultar detalle carpeta</option>
  </select>
  <input type="submit">
</form>-->

