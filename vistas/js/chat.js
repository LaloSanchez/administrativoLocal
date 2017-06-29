var Chat = function () {
    return {
        uploadFiles: function () {
            // --> Enviamos el Archivo que se desea subir
            var self = this;
            var formData = new FormData();
            formData.append("file", $('#fileUpload')[0].files[0]);
            var $form = $("#sendFileUpload");
            var urlToSend = $form.attr("action");
            $.ajax({
                url: encodeURI(urlToSend),
                type: "POST",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (dataBack) {
                    try {
                        var datos = eval("(" + dataBack + ")");
                        if (datos.type == "OK") {
                            bootbox.alert(datos.text);
                            self.refreshIframe("directory", "contenidoArchivosModal");
                            self.ajustarIframe("directory", "contenidoArchivosModal");
                            $("#sendFileUpload")[0].reset();
                        } else {
                            bootbox.alert(datos.text);
                        }
                    } catch (e) {
                        bootbox.alert("OCURRIO UN ERROR AL ENVIAR EL ARCHIVO");
                    }
                }
            });
            return false;
        },
        getUsersOnLine: function () {
            // --> Enviamos el Archivo que se desea subir
            var self = this;
            var $form = $("#sendFileUpload");
            var data = {
                "action": "usersonline"
            }
            var urlToSend = $form.attr("action");
            $.ajax({
                url: encodeURI(urlToSend),
                type: "GET",
                data: data,
                async: true,
                success: function (dataBack) {
                    try {
                        var datos = dataBack;
                        var table = "";
                        $("#tableUsersOnline tbody > tr").remove();
                        if (datos.lenght != 0) {
                            $.each(datos, function (index, val) {
                                table += "<tr>";
                                table += "<td>" + val.username + "</td>";
                                if (val.status == 1) {
                                    table += "<td><span class=\"glyphicon glyphicon-signal\"  style=\"color:darkgreen\" ></span></td>";
                                } else {
                                    table += "<td><span class=\"glyphicon glyphicon-flash\" style=\"color:red\"></span></td>";
                                }
                                table += "</tr>";
                            });
                        } else {
                            table += "<tr><td colspan='2' >NO HAY USUARIOS ACTIVOS</td></tr>";
                        }
                        $("#tableUsersOnline").append(table);
                    } catch (e) {
                        bootbox.alert("OCURRIO UN ERROR AL INTENTAR OBTENER LOS USUARIOS ONLINE");
                    }
                }
            });
            return false;
        },
        refreshIframe: function (id, idCanvas) {
            var iframe = document.getElementById(id);
            iframe.src = iframe.src;
            this.ajustarIframe(id, idCanvas)
        },
        ajustarIframe: function (idIframe, idCanvas) {
            var iFrameID = document.getElementById(idIframe);
            if (iFrameID) {
                iFrameID.height = "";
                $("#" + idCanvas).css("height", "0px");
                var height = iFrameID.contentWindow.document.getElementById("content").scrollHeight;
                iFrameID.height = (height + 36) + "px";
                $("#" + idCanvas).css("height", (height + 120) + "px");
            }
        },
        minimize: function () {
            $(".shout_box").animate({
                right: -400
            }, function () {
                $(".box_iconMessage").fadeIn();
            });
        },
        maximize: function () {
            $(".box_iconMessage").hide();
            $(".shout_box").animate({
                right: 0
            });
        }
    }
}