var firmaElectronica = function () {
    return {
        cveTipoActuacion: 0,
        idActuacion: 0,
        scapeUrl: "",
        desActuacion: "",
        archivoDesc: "",
        cveOrigen: 0,
        fAutografa: true,
        isHTML5: false,
        download: false,
        callbackFn: null,
        validateFormToSign: function () {
            var self = this;
            $.ajax({
                type: "POST",
                url: self.scapeUrl + "/controller/actuacionesfirmantes/ActuacionesFirmantesController.php",
                data: {
                    action: "selectTotalSign",
                    cveTipoActuacion: self.cveTipoActuacion,
                    cveOrigen: self.cveOrigen
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    $(".notsign").remove();
                    $(".btnverify").remove();
                    $(".wait").show();
                    $("#btnSign").hide();
                },
                success: function (datos) {
                    $(".wait").hide();
                    if (datos != "") {
                        if (datos.estatus == "ok") {
                            var idActuacion = self.idActuacion;
                            var totalCount = datos.totalCount;
                            $("#hddNumFirmas").val(totalCount);
                            for (var i = 0; i < totalCount; i++) {
                                if (datos.resultados[i].cveGrupo == $("#hddCveGrupo").val()) {
                                    if (datos.resultados[i].userSign) {
                                        if ($("#hddNumFirmas").val() > 0) {
                                            if (idActuacion != "") {
                                                self.validateStatusSignActuacion();
                                            }
                                        }
                                    }
                                }
                            }
                        } else if (datos.estatus == "CurpNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >TU CURP NO ES V&Aacute;LIDA, POR FAVOR VER&Iacute;FICALA.</div>");
                            $(".firmtobtns").show();
                        } else if (datos.estatus == "SessionNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >POR FAVOR VER&Iacute;FICA TU SESION.</div>");
                            $(".firmtobtns").show();
                        }
                    }
                },
                failure: function (response) {
                    alert("Error en la peticion:\n\n" + response);
                }
            });
        },
        validateStatusSignDocumento: function () {
            var self = this;
            $.ajax({
                type: "POST",
                url: self.scapeUrl + "../controladores/documentosfirmados/DocumentosFirmadosController.php",
                data: {
                    action: "selectGeneralStatus",
                    idDocumento: self.idDocumento,
                    activo: "S",
                    cveTipoDocumento: self.cveTipoDocumento
                    //cveOrigen: self.cveOrigen
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    // $(".wait").show();
                    // $("#btnSign").hide();
                },
                success: function (result) {
                    console.log(result);
                    if (result.status == "success") {
                        if (result.statusDocumentoFirma == 'debeFirmar') {
                            // mostrar boton de firmar, no es el ultimo firmante
                            if (fielnetPJ.validateWebBrowser()) {
                                $(".btnFirmaContainer").html('<button type="button" onclick="javascript:firma.doSign()" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i> Firmar documento</button>');
                                $(".btnFirmaContainer").show();
                            } else {
                                $(".btnFirmaContainer").html('<h6><i class="fa fa-ban" aria-hidden="true"></i> Este navegador no soporta firma electronica</h6>');
                                $(".btnFirmaContainer").show();
                            }
                        }else if(result.statusDocumentoFirma == 'noFirma'){
                            // mostrar texto de firma pendiente
                            $(".btnFirmaContainer").html('<h6><i class="fa fa-ban" aria-hidden="true"></i> Documento pendiente de firma(s)</h6>');
                            $(".btnFirmaContainer").show();
                        }else if (result.statusDocumentoFirma == 'todosFirmaron') {
                            // verificar si tambien ya se envio al servidor externo de firma
                            if (result.statusEnvioExterno == "true") {
                                // ya se habia mandado
                                $(".btnFirmaContainer").html('<h6><i class="fa fa-certificate" aria-hidden="true"></i> Este documento ya cuenta con firma(s) electronica</h6>');
                                $(".btnFirmaContainer").show();
                            }else{
                                // ya se firmo todo generar pdf final y mandar al servidor de la firma electronica
                                $(".btnFirmaContainer").html('');
                                $(".btnFirmaContainer").show();
                                self.getPDFHTML5();
                            }
                        }
                    }else{
                        console.log(result.msg);
                        $(".btnFirmaContainer").html('');
                        $(".btnFirmaContainer").show();
                    }
                    //$(".wait").hide();
                    /*if (datos != "") {
                        if (datos.estatus == "ok") {
                            var readyToSign = false;
                            var totalCount = datos.totalCount;
                            if (totalCount > 0) {
                                if (totalCount == $("#hddNumFirmas").val()) {
                                    $.each(datos.resultados, function (index, val) {
                                        if (val.cveGrupo == $("#hddCveGrupo").val()) {
                                            if ((val.idImagenFirmada != 0) && (val.idImagenFirmada != null)) {
                                                $(".notsign").remove();
                                                $(".btnverify").remove();
                                            } else {
                                                if ((val.idRegistroFirma != null)) {
                                                    if (fielnetPJ.validateWebBrowser()) {
                                                        $(".firmtobtns").append("<div class='btnverify' style='display:inline-block' ><button class='btn btn-info frmBoton' onclick='javascript:firma.getPDFHTML5()' >Verificar Firma</button></div>");
                                                    } else {
                                                        $(".firmtobtns").append("<div class='btnverify' style='display:inline-block' ><button class='btn btn-info frmBoton' onclick='javascript:firma.verificarFirma(\"" + val.tokenFirma + "\")' >Verificar Firma</button></div>");
                                                    }
                                                } else {
                                                    $("#btnSign").show();
                                                    $("#spanMnjFirma").html("");
                                                }
                                            }
                                        } else {
                                            $("#spanMnjFirma").html("<br /><div class='col-md-12 alert alert-info text-center' >A&uacuten faltan Vo.Bo.</div>");
                                            $("#spanMnjFirma").show();
                                            $("#btnSign").hide();
                                        }
                                    });
                                } else {
                                    $.each(datos.resultados, function (indext, valt) {
                                        if (valt.cveGrupo == $("#hddCveGrupo").val()) {
                                            $("#spanMnjFirma").html("A&uacuten faltan Vo.Bo.");
                                            $("#spanMnjFirma").show();
                                            $("#btnSign").hide();
                                        }
                                    });
                                }
                            }
                        } else if (datos.estatus == "CurpNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >TU CURP NO ES V&Aacute;LIDA, POR FAVOR VER&Iacute;FICALA.</div>");
                            $(".firmtobtns").show();
                        } else if (datos.estatus == "SessionNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >POR FAVOR VER&Iacute;FICA TU SESION.</div>");
                            $(".firmtobtns").show();
                        } else {
                            $("#spanMnjFirma").html("");
                            $("#spanMnjFirma").hide();
                            if (datos.cveGrupo == $("#hddCveGrupo").val()) {
                                $("#btnSign").show();
                            } else if ((datos.totalCount > 1) && (datos.cveGrupoFirmante == $("#hddCveGrupo").val())) {
                                $("#spanMnjFirma").html("A&uacuten faltan Vo.Bo.");
                                $("#spanMnjFirma").show();
                            } else {
                                $("#spanMnjFirma").html("");
                                $("#spanMnjFirma").hide();
                            }
                        }
                    }*/
                },
                failure: function (response) {
                    alert("Error en la peticion:\n\n" + response);
                }
            });
        },
        doSign: function () {
            console.group("INICIA FIRMA ELECTRONICA");
            console.log('limpiar hddDigestiones');
            $('#hddDigestiones').val('');
            console.log("Validar si el navegador soporta HTML5");
            if (fielnetPJ.validateWebBrowser()) {
                console.log('SI soporta html5');
                console.log('funcion doSignFielNet()');
                this.doSignFielNet();
                this.isHTML5 = true;
            } else {
                console.log('NO soporta html5');
                this.doSignJava();
                this.isHTML5 = false;
            }
            return false;
        },
        doSignFielNet: function () {
            /* Inicia Proceso de Firmado */
            console.log('funcion this.generaPDF()');

            if (!this.generaPDF()){ // si no hay error
                console.log('PDF generado correctamente');
            }else{
                console.log('Error al generar el pdf doSignFielNet');
            }

            var usuario = $('#hddcveUsuarioSesion').val();
            var obj = fielnetPJ.loadCertificateAndPrivateKey(fielnet.Storages.LOCAL_STORAGE, "cer_" + usuario, "key_" + usuario);
            if (obj.state < 0) {
                /* Abrimos el Modal */
                //fielnetPJ.readCertificate("cer");
                //fielnetPJ.readPrivateKey("key");
                console.log('No hay datos de la firma, abrir modal');
                $('#myModalFirmaElectronica').modal();
            } else {
                console.log('Datos de la firma del usuario cargados correctamente');
                console.log('funcion generaFirmas()');
                this.generaFirmas();
            }
        },
        generaFirmas: function () {
            var usuario = $('#hddcveUsuarioSesion').val(), self = this;
            console.log('se cargan los datos de la firma para el usuario cer_'+usuario);
            fielnetPJ.loadCertificateAndPrivateKey(fielnet.Storages.LOCAL_STORAGE, "cer_" + usuario, "key_" + usuario);
            fielnetPJ.addExtraParameters("&idReferencia=" + this.idDocumento + "&cveTipoDocumento=" + this.cveTipoDocumento + "&cveAdscripcion=" + $('#hddCveAdscripcion').val());
            //fielnetPJ.addExtraParameters("&idReferencia=" + this.idActuacion + "&cveTipoReferencia=" + this.cveTipoActuacion + "&cveOrigenReferencia=" + this.cveOrigen + "&cveGrupo=" + $("#hddCveGrupo").val());
            console.log('funcion fielnetPJ.signFileDigest');
            fielnetPJ.signFileDigest("hddDigestiones", fielnet.Digest.SHA2, function (e) {
                if (e.state == 0) {
                    console.log('funcion validateStatusSignActuacion()')
                    self.validateStatusSignDocumento();
                } else {
                    console.log('Error en signFileDigest');
                    alert('Ocurrio un error con el servidor de firma electronica, intenta nuevamente o contacte al area de TI.');
                }
            });
            console.groupEnd();
        },
        verificarFirmaLogin: function () {
            $(".txtresponseModal").html('');
            var self = this;
            var usuario = $('#hddcveUsuarioSesion').val();
            fielnetPJ.validateKeyPairs(document.getElementById("pass_firma").value, function (resultado) {
                if (resultado.state == 0) {
                    /* Callback de Firmado */
                    fielnetPJ.saveCertificateAndPrivateKey(fielnet.Storages.LOCAL_STORAGE, "cer_" + usuario, "key_" + usuario);
                    $('.firmaElectronicaModal').modal('hide');
                    self.generaFirmas();
                } else {
                    $(".txtresponseModal").html('<div class="alert alert-info text-center" >' + resultado.description + '</div>');
                }
            });
            return false;
        },
        doSignJava: function () {
            var idActuacion = this.idActuacion;
            var self = this;
            if (idActuacion != "") {
                $.ajax({
                    type: "POST",
                    url: self.scapeUrl + "/controller/firmaelectronicahtml5/FirmaElectronicaController.php",
                    data: {
                        action: "RegistroTransferenciaFirma",
                        operation: "j03",
                        textRef: "Firma Electronica",
                        attrSign: "1",
                        validity: "3600",
                        idActuacion: idActuacion,
                        cveTipoActuacion: self.cveTipoActuacion,
                        cveOrigen: self.cveOrigen
                    },
                    async: false,
                    dataType: "json",
                    beforeSend: function () {
                        $(".wait").show();
                        $("#btnSign").hide();
                        $(".notsign").remove();
                        $(".btnverify").remove();
                    },
                    success: function (datos) {
                        $(".wait").hide();
                        if (datos != "") {
                            if (datos.estatus == "ok") {
                                var totalCount = datos.resultados.length;
                                if (totalCount > 0) {
                                    for (var i = 0; i < totalCount; i++) {
                                        if (datos.resultados[i].estado == "0") {
                                            var descripcion = datos.resultados[i].descripcion;
                                            var estado = datos.resultados[i].estado;
                                            var transferencia = datos.resultados[i].transferencia;
                                            var codigo = datos.resultados[i].codigo;
                                            self.commitDocuments(transferencia, codigo);
                                        }
                                    }
                                }
                            }
                        } else if (datos.estatus == "CurpNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >TU CURP NO ES V&Aacute;LIDA, POR FAVOR VER&Iacute;FICALA.</div>");
                            $(".firmtobtns").show();
                        } else if (datos.estatus == "SessionNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >POR FAVOR VER&Iacute;FICA TU SESION.</div>");
                            $(".firmtobtns").show();
                        }
                    },
                    failure: function (response) {
                        alert("Error en la peticion:\n\n" + response);
                    }
                });
            } else {
                alert("NO SE ENCONTRO LA ACTUACION");
            }
        },
        commitDocuments: function (transferencia, codigo) {
            if (transferencia !== "" && codigo != "") {
                var idActuacion = this.idActuacion;
                var self = this;
                var error = false;
                $.ajax({
                    type: "POST",
                    url: self.scapeUrl + "/controller/pdfactuaciones/GeneraPdfFirmaController.php",
                    data: {
                        action: "doPDF",
                        idActuacion: idActuacion,
                        cveTipoActuacion: self.cveTipoActuacion,
                        cveOrigen: self.cveOrigen
                    },
                    async: false,
                    dataType: "json",
                    beforeSend: function () {
                        $(".wait").show();
                        $("#btnSign").hide();
                    },
                    success: function (datos) {
                        $(".wait").hide();
                        if (datos != "") {
                            if (datos.type === "OK") {
                                error = self.digestionArchivo(transferencia, codigo, datos.filePath, datos.idImagenOriginal, datos.fileName);
                            } else {
                                alert("Ocurrio un error en la generacion del pdf");
                                error = true;
                            }
                        } else {
                            alert("Ocurrio un error en la generacion pdf");
                            error = true;
                        }
                        return error;
                    },
                    failure: function (response) {
                        alert("Error en la peticion:\n\n" + response);
                    }
                });
            }
        },
        digestionArchivo: function (transfer, codigo, file, idImagenOriginal, fileName) {
            if (transfer !== "" && file != "" && codigo != "") {
                var error = false;
                var self = this;
                $.ajax({
                    type: "POST",
                    url: self.scapeUrl + "/controller/firmaelectronica/FirmaElectronicaController.php",
                    data: {
                        action: "DigestionArchivoFirma",
                        transfer: transfer,
                        textRef: self.archivoDesc,
                        file: file
                    },
                    async: true,
                    dataType: "json",
                    beforeSend: function () {
                        $(".wait").show();
                        $("#btnSign").hide();
                    },
                    success: function (datos) {
                        $(".wait").hide();
                        $("#btnSign").show();
                        if (datos != "") {
                            if (datos.estatus == "ok") {
                                var totalCount = datos.resultados.length;
                                if (totalCount > 0) {
                                    for (var i = 0; i < totalCount; i++) {
                                        if (datos.resultados[i].estado == "0") {
                                            var descripcion = datos.resultados[i].descripcion;
                                            var estado = datos.resultados[i].estado;
                                            var idRegistro = datos.resultados[i].idRegistro;
                                            self.UpdateTransferenciaFirma(transfer, codigo, idRegistro, idImagenOriginal, fileName);
                                        }
                                    }
                                }
                            } else if (datos.estatus == "CurpNotFound") {
                                $(".firmtobtns").append("<div class='alert alert-danger notsign' >TU CURP NO ES V&Aacute;LIDA, POR FAVOR VER&Iacute;FICALA.</div>");
                                $(".firmtobtns").show();
                            } else if (datos.estatus == "SessionNotFound") {
                                $(".firmtobtns").append("<div class='alert alert-danger notsign' >POR FAVOR VER&Iacute;FICA TU SESION.</div>");
                                $(".firmtobtns").show();
                            } else {
                                alert(datos.mnj);
                                error = true;
                            }
                        } else {
                            alert("Ocurrio un error en la peticion");
                            error = true;
                        }
                    },
                    failure: function (response) {
                        alert("Error en la peticion:\n\n" + response);
                    }
                });
            }
            return error;
        },
        UpdateTransferenciaFirma: function (transfer, codigo, idRegistro, idImagenOriginal, fileName) {
            if (transfer != "" && codigo != "" && idRegistro != "") {
                var idActuacion = this.idActuacion;
                var self = this;
                $.ajax({
                    type: "POST",
                    url: self.scapeUrl + "/controller/actuacionesfirmadas/ActuacionesFirmadasController.php",
                    data: {
                        action: "select",
                        idActuacion: idActuacion,
                        cveTipoActuacion: self.cveTipoActuacion,
                        cveOrigen: self.cveOrigen
                    },
                    async: false,
                    dataType: "json",
                    beforeSend: function () {
                        $(".wait").show();
                        $("#btnSign").hide();
                    },
                    success: function (datos) {
                        $(".wait").hide();
                        $("#btnSign").show();
                        if (datos != "") {
                            if (datos.estatus == "ok") {
                                var totalCount = datos.totalCount;
                                for (var i = 0; i < totalCount; i++) {
                                    $.ajax({
                                        type: "POST",
                                        url: self.scapeUrl + "/controller/actuacionesfirmadas/ActuacionesFirmadasController.php",
                                        data: {
                                            action: "update",
                                            idActuacionFirmada: datos.resultados[i].idActuacionFirmada,
                                            transferenciaFirma: transfer,
                                            tokenFirma: codigo,
                                            idRegistroFirma: idRegistro,
                                            idImagenOriginal: idImagenOriginal,
                                            fileNameFirma: fileName,
                                            cveOrigen: self.cveOrigen
                                        },
                                        async: false,
                                        dataType: "json",
                                        beforeSend: function () {
                                            $(".btnverify").remove();
                                            $(".wait").show();
                                            $("#btnSign").hide();
                                        },
                                        success: function (datos) {
                                            $(".wait").hide();
                                            if (datos != "") {
                                                self.generacionJar(codigo);
                                                alert("Esta por descargarse un JAR, por favor ejecutalo!!");
                                                $(".firmtobtns").append("<div class='btnverify' ><button class='btn btn-info frmBoton' onclick='javascript:firma.verificarFirma(\"" + codigo + "\")' >Verificar Firma</button></div>");
                                            }
                                        },
                                        failure: function (response) {
                                            alert("Error en la peticion:\n\n" + response);
                                        }
                                    });
                                }
                            }
                        } else if (datos.estatus == "CurpNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >TU CURP NO ES V&Aacute;LIDA, POR FAVOR VER&Iacute;FICALA.</div>");
                            $(".firmtobtns").show();
                        } else if (datos.estatus == "SessionNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >POR FAVOR VER&Iacute;FICA TU SESION.</div>");
                            $(".firmtobtns").show();
                        }
                    },
                    failure: function (response) {
                        alert("Error en la peticion:\n\n" + response);
                    }
                });
            }
        },
        generacionJar: function (codigo) {
            $("#codigo").val(codigo);
            if ($("#codigo").val() !== "") {
                $("#frmGeneraJar").submit();
            }
        },
        verificarFirma: function (verificarFirma) {
            if (verificarFirma != "") {
                var self = this;
                $.ajax({
                    type: "POST",
                    url: self.scapeUrl + "/controller/firmaelectronica/FirmaElectronicaController.php",
                    data: {
                        "action": "verificarFirma",
                        "idReferencia": verificarFirma,
                    },
                    async: false,
                    dataType: "json",
                    beforeSend: function () {
                        $(".wait").show();
                        $("#btnSign").hide();
                        $(".notsign").remove();
                        $(".btnverify").hide();
                    },
                    success: function (datos) {
                        $(".wait").hide();
                        if (datos != "") {
                            if (datos.type == "Error") {
                                var str = "<div class='alert alert-info notsign' style='margin-top: 5px;' >Documento a&uacuten no ha sido Firmado.</div>";
                                $(".firmtobtns").append(str);
                                $("#btnSign").show();
                                $(".btnverify").css({"display": "inline-block"}).show();
                            } else {
                                $(".btnverify").remove();
                                self.getPdfSimple();
                                self.validateFormToSign();
                                if ((self.callbackFn) && (typeof self.callbackFn === 'function')) {
                                    self.callbackFn();
                                }
                            }
                        } else if (datos.estatus == "CurpNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >TU CURP NO ES V&Aacute;LIDA, POR FAVOR VER&Iacute;FICALA.</div>");
                            $(".firmtobtns").show();
                        } else if (datos.estatus == "SessionNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >POR FAVOR VER&Iacute;FICA TU SESION.</div>");
                            $(".firmtobtns").show();
                        }
                    },
                    failure: function (response) {
                        alert("Error en la peticion:\n\n" + response);
                    }
                });
            }
        },
        getPdfSimple: function () {
            var idActuacion = this.idActuacion;
            var self = this;
            if (idActuacion != "") {
                $("#btnuploadKey").hide();
                $(".btntoDownload").hide();
                $.ajax({
                    type: "POST",
                    url: self.scapeUrl + "/controller/firmaelectronica/FirmaElectronicaController.php",
                    data: {
                        action: "pdfSimplePersonalSimple",
                        activo: "S",
                        idReferencia: idActuacion,
                        cveGrupo: "",
                        cveReferencia: self.cveTipoActuacion,
                        cveOrigen: self.cveOrigen,
                        fAutografa: self.fAutografa
                    },
                    async: false,
                    dataType: "json",
                    beforeSend: function () {
                        $(".wait").show();
                        $("#btnSign").hide();
                        $("#btnuploadKey").hide();
                    },
                    success: function (datos) {
//                        datos = eval("(" + datos + ")");
                        if (datos != "") {
                            if (datos.type == 'OK') {
                                $(".wait").hide();
                                $("#btnSign").show();
                                if (self.download) {
                                    $(".btntoDownload").show();
                                    $("#btnSign").hide();
                                }
                            } else {
                                $("#btnuploadKey").show();
                                alert(datos.text)
                            }
                        } else if (datos.estatus == "CurpNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >TU CURP NO ES V&Aacute;LIDA, POR FAVOR VER&Iacute;FICALA.</div>");
                            $(".firmtobtns").show();
                        } else if (datos.estatus == "SessionNotFound") {
                            $(".firmtobtns").append("<div class='alert alert-danger notsign' >POR FAVOR VER&Iacute;FICA TU SESION.</div>");
                            $(".firmtobtns").show();
                        }
                    },
                    failure: function (response) {
                        $("#btnuploadKey").show();
                        alert("Error en la peticion:\n\n" + response);
                    }
                });
            } else {
                alert("Para obtener un PDF firmado, es necesario consultar la actuaci�n");
            }

        },
        downloadFirma: function () {
            $("#idActuacionDownloadcto").val(0);
            $("#idorigencionDownloadcto").val(0);
            $("#idtipoActuacionDownloadcto").val(0);
            if (this.idActuacion != "") {
                $("#idActuacionDownloadcto").val(this.idActuacion);
                $("#idorigencionDownloadcto").val(this.cveOrigen);
                $("#idtipoActuacionDownloadcto").val(this.cveTipoActuacion);
                $("#imprimirDctoFirmado").submit();
            } else {
                alert("NO SE ENCONTRO EL DOCUMENTO");
            }
        },
        generaPDF: function () {
            var idDocumento = this.idDocumento;
            var self = this;
            var error = false;
            $.ajax({
                type: "POST",
                url: self.scapeUrl + "../controladores/firmaelectronicahtml5/GeneraPdfFirmaController.php",
                data: {
                    action: "doPDFDigestion",
                    idDocumento: idDocumento,
                    cveTipoDocumento: self.cveTipoDocumento
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    $("#btnFirmar").attr('disabled','disabled');
                },
                success: function (datos) {
                    //console.log(datos);
                    /*
                    {
                      "documento": {
                        "activo": "S",
                        "cveAdscripcion": "10234",
                        "cveTipoDocumento": "11",
                        "cveUsuario": "7786",
                        "digestion": "n9oseNz7Ycdh8iaRkdEDyHB4taA=",
                        "fechaActualizacion": "2017-03-13 10:27:36",
                        "fechaRegistro": "2017-03-10 11:49:02",
                        "fileNameFirma": "imagenes/10234/2017/Oficio/115OF.pdf",
                        "generado": "S",
                        "idDocumentosFirmados": "3",
                        "idImagenFirmada": null,
                        "idImagenOriginal": "9094",
                        "idReferencia": "115",
                        "idRegistroFirma": null,
                        "singleName": "115OF.pdf",
                        "tokenFirma": null,
                        "transferenciaFirma": null
                      },
                      "msg": "El pdf se encontro y se actualizo la digestion correctamente",
                      "status": "success"
                    }
                    */
                    //$(".wait").hide();
                    
                    if (datos.status == 'success') {
                        self.push_digestion_json(datos.documento.singleName, datos.documento.digestion);
                    }else{
                        alert('Ocurrio un error en la generacion del pdf '+datos.msg);
                        error =  true;
                    }
                    /*if (datos != "") {
                        if (datos.type === "OK") {
                            var nombre = datos.actuacion.fileNameFirma.split('/');
                            self.push_digestion_json(nombre[1], datos.actuacion.digestion);
                        } else {
                            alert("Ocurrio un error en la generacion del pdf");
                            error = true;
                        }
                    } else {
                        alert("Ocurrio un error en la generacion pdf");
                        error = true;
                    }*/
                    return error;
                },
                failure: function (response) {
                    alert("Error en la peticion:\n\n" + response);
                },
                complete: function () {
                    $("#btnFirmar").attr('disabled',false);
                }
            });
        },
        push_digestion_json: function (file_name_and_extension, digestion) {
            if ($('#hddDigestiones').val() != "") {
                console.log($('#hddDigestiones').val());
                // existen archivos en el json, agregar el nuevo
                var json_digestiones_exist = jQuery.parseJSON($('#hddDigestiones').val());
                console.log("AGREGA: "+JSON.stringify(json_digestiones_exist));
                json_digestiones_exist.digestiones.push(
                        {documento: file_name_and_extension, digestion: digestion}
                );
                console.log("AGREGO: "+JSON.stringify(json_digestiones_exist));
                $('#hddDigestiones').val(JSON.stringify(json_digestiones_exist));
            } else {
                // no hay archivos, crear json
                console.log('primer archivo agregado a hddDigestiones');
                var json_digestiones = '{"digestiones":[{"documento":"' + file_name_and_extension + '", "digestion":"' + digestion + '"}]}';
                $('#hddDigestiones').val(json_digestiones);
                console.log("AGREGO: "+json_digestiones);
            }
        },
        getPDFHTML5: function () {
            var idReferencia = this.idDocumento;
            var cveTipoDocumento = this.cveTipoDocumento;
            //var cveOrigen = this.cveOrigen;
            var fAutografa = this.fAutografa;
            var self = this;
            $.ajax({
                type: "POST",
                url: self.scapeUrl + "../controladores/firmaelectronicahtml5/FirmaElectronicaController.php",
                data: {
                    "metodo": "firmaArchivos",
                    "idReferencia": idReferencia,
                    "cveTipoDocumento": cveTipoDocumento,
                    //"cveOrigen": cveOrigen,
                    "fAutografa": fAutografa
                },
                async: false,
                dataType: "json",
                beforeSend: function () {
                    
                },
                success: function (datos) {
                    console.log("respuesta desde firmaElectronica.js funcion getPDFHTML5");
                    console.log(datos);
                    /*if (datos != "") {
                        if (datos.type == "Error") {
                            var str = "<div class='alert alert-info notsign' style='margin-top: 5px;' >Documento a&uacuten no ha sido Firmado.</div>";
                            $(".firmtobtns").append(str);
                            $("#btnSign").show();
                            $(".btnverify").css({"display": "inline-block"}).show();
                        } else {
                            $(".btnverify").remove();
                            self.getPdfSimple();
                            self.validateFormToSign();
                            if ((self.callbackFn) && (typeof self.callbackFn === 'function')) {
                                self.callbackFn();
                            }
                        }
                    } else if (datos.estatus == "CurpNotFound") {
                        $(".firmtobtns").append("<div class='alert alert-danger notsign' >TU CURP NO ES V&Aacute;LIDA, POR FAVOR VER&Iacute;FICALA.</div>");
                        $(".firmtobtns").show();
                    } else if (datos.estatus == "SessionNotFound") {
                        $(".firmtobtns").append("<div class='alert alert-danger notsign' >POR FAVOR VER&Iacute;FICA TU SESION.</div>");
                        $(".firmtobtns").show();
                    }*/
                },
                failure: function (response) {
                    alert("Error en la peticion:\n\n" + response);
                }
            });
        }
    }
}