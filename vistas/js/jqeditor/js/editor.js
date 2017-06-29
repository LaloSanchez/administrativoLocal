guardaTexto = function () {
    if (UE.getEditor('editor').hasContents()) {
        var strDatos = "Accion=guardaTexto";
        strDatos += "&idComentario=1";
        strDatos += "&Comentario=" + encodeURIComponent(UE.getEditor('editor').getContent());
        $.ajax({
            type: "POST",
            url: "php/editor.php",
            data: strDatos,
            async: false,
            dataType: "html",
            beforeSend: function (objeto) {
                //
            },
            success: function (datos) {
                alert(datos);
            },
            error: function (objeto, quepaso, otroobj) {
                alert("Error en la petición:\n\n" + quepaso);
            }
        });
    } else {
        alert("Necesitas escribir algun contenido!");
        UE.getEditor('editor').focus();
    }
};

cargaTexto = function () {
    var strDatos = "Accion=cargaTexto";

    $.ajax({
        type: "POST",
        url: "php/editor.php",
        data: strDatos,
        async: false,
        dataType: "html",
        beforeSend: function (objeto) {
            //
        },
        success: function (datos) {
            //alert(datos);
            UE.getEditor('editor').setContent(datos, false);
        },
        error: function (objeto, quepaso, otroobj) {
            alert("Error en la petición:\n\n" + quepaso);
        }
    });
};

$(window).load(function () {
    UE.getEditor('editor').setHeight(400);
});