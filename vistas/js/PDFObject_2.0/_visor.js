var _visor_pag_select = 1;

function showPDF(pdfs, titulo, botones, mostrar) {
    _visor_pag_select = mostrar;
    $('#_modal_visor').remove();
    $('body').append('<div class="modal fade" id="_modal_visor" role="dialog" data-keyboard="false" data-backdrop="static">' +
            '<div class="modal-dialog modal-lg" style="">' +
            '    <div class="modal-content" style="    background:  radial-gradient(rgba(216, 2, 23, 0.45), #5d0f11);">' +
            '        <div class="modal-body" style=" text-align: center;">' +
            '            <button type="button" class="close" data-dismiss="modal" style="font-size: 19px;"><i class="fa fa-times" style="color:#000000;font-size:18px;" aria-hidden="true"></i></button>' +
            '            <span id="_titulo_visor" style="color: white;font-size: 18px; font-weight:bold;"></span> ' +
            '            <hr>' +
            '            <div id="_visor"></div>' +
            '            <div id="_pag_visor"></div>' +
            '            <br>' +
            '            <div id="divVisorBotones">' +
            '            <button type="button" class="btn btn-danger btn-xs"  id="btnDeletePDF" name="btnDeletePDF" title="Generar pdf "style="height:24px;line-height:20px">' +
            '                <i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar actual' +
            '            </button>                        ' +
            '            <button type="button" class="btn btn-default btn-xs"  id="btnDeletePDFAll" name="btnDeletePDFAll" title="Generar pdf "style="height:24px;line-height:20px">' +
            '                <i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar todo' +
            '            </button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '</div>' +
            '</div>');
    $("#_modal_visor").modal();
    if (botones) {
        $('#divVisorBotones').show();
    } else {
        $('#divVisorBotones').hide();
    }
    $('#_visor').empty();
    $('#_titulo_visor').css({'font-weight': 'bold'});
    $('#_titulo_visor').empty().append(titulo);
    var pag = pdfs.data.length;
    if (pag > 1) {
        $('#_pag_visor').css({'text-align': 'center'});
        $('#_pag_visor').bootpag({
            total: pag,
            page: 1,
            maxVisible: 100,
            leaps: false,
            firstLastUse: true,
            first: '<i class="fa fa-angle-double-left" aria-hidden="true"></i> ',
            last: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
            next: 'sig',
            prev: 'ant'
        }).on('page', function (event, num) {
            _visor_pag_select = num;
            loadPDF(pdfs.data[num - 1].ruta);
        });
        loadPDF(pdfs.data[0].ruta);
    } else {
        loadPDF(pdfs.data[0].ruta);
    }
   
    $('.pdfobject-container').css({height: ($(document).height() * .5) + 'px'});
    $('.pdfobject-container').css({width: '100%'});
    $('.pdfobject').css({border: '1px solid #666'});
}
function loadPDF(pdf) {
     
    var aux = pdf.indexOf("?");
    var param = '?';
    if (aux != -1) {
        param = '&';
    }
    param += new Date().getTime();
    PDFObject.embed(pdf + param, "#_visor", {fallbackLink: "<p>En navegador no soporta la vista de PDFs. Haga <a href='[url]'>clic aqu&iacute;</a> para descargar el PDF</p>"});
}
$("#btnDeletePDF").click(function () {
    alert(_visor_pag_select);
    alert(pdfs.data[_visor_pag_select - 1].cveDocumento);
    alert(pdfs.type.cveUsuario);
});
$("#btnDeletePDFAll").click(function () {
    alert(_visor_pag_select);
});
