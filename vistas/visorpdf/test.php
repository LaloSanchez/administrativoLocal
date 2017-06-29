<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test para visor de PDF's</title>
  </head>
  <body>
    <input type="text" id="idCarpetaJudicial" placeholder="Id carpeta judicial"><br>
    <input type="text" id="idActuacion" placeholder="Id actuacion"><br>
    <input type="text" id="cveTipoDocumento" placeholder="cve tipo documento"><br>
    <button id="btn-visor">
        Mostrar
    </button>
    <hr>
    <div id="visor"></div>
    <script src="jquery.js"></script>
    <script src="jquery.ui.js"></script>
    <script>
        
        /**
         * función para mandar llamar al visor de PDF
         * @params
         * URL, de acuerdo a la ubicación de tu archivo se deberá armar la siguiente URL
         * http://PATH/vistas/visorpdf/index.php
         
         * @var: idCarpetaJudicial, idActuacion, cveTipoDocumento
         * Siempre deberá enviarse cveTipoDocumento acompañada de idCarpetaJudicial ó idActuacion
         */
        $('#btn-visor').click(function()
        {
           $.ajax({
                type: 'POST',
                // URL ejemplo http://PATH/vistas/visorpdf/index.php
                url: 'index.php',
                data: {idCarpetaJudicial: $('#idCarpetaJudicial').val(), idActuacion: $('#idActuacion').val(), cveTipoDocumento: $('#cveTipoDocumento').val()},
                async: false,
                dataType: 'html',
                beforeSend: function () {
                    $('#visor').html('<h3>Consultando información ... espere.</h3>');
                },
                success: function (data) {
//                    console.log(data)
                    $('#visor').html(data);
                },
                error: function (objeto, quepaso, otroobj) {
                    $('#visor').html('<h3>Ocurri&oacute; un error, intente nuevamente. '+  otroobj +'</h3>');
                    console.log('\nOBJ: '+objeto+ '\nQ: '+quepaso+'\nO:'+otroobj)
                }
            });
        });
        
        
    </script>
  </body>
</html>