var iframe;

$(document).ready(function () {

    iframe = document.getElementById('chat');

    $(".headerChat").click(function (e) {
        var toggleState = $('.toggle_chat').css('display');

        $('.toggle_chat').slideToggle();

        if (toggleState == 'block')
        {
            $(".headerChat div").attr('class', 'open_btn');
        } else {
            $(".headerChat div").attr('class', 'close_btn');
        }
        $chat = $("#chat");
        $chat.contents().find('.direct-chat-messages').scrollTop(10000000);
    });
});


function refreshChat() {
    iframe.src = iframe.src;
//    $('#chat').contentWindow.location.reload(true);
}


function SwitchChat(chatfingerprint, cveNumero, tipochat, titlechat, color)
{
    $(".headerChat").html(titlechat + '<div class="close_btn">&nbsp;</div>');
    $(".headerChat").css({"background-color": color});
    $("#minimize").css({"background-color": color});
    $("#maximize").css({"background-color": color});

    var dataString = 'chatident=' + chatfingerprint + "&cveNumero=" + cveNumero + "&tipochat=" + tipochat;

    $.ajax({
        type: "POST",
        url: "./chat-box/chatsessionid.php",
        data: dataString,
        dataType: 'json',
        async: false,
        cache: false,
        beforeSend: function (xhr) {
            $(".shout_box").hide("slow");
        },
        success: function (response) {
            $(".shout_box").show("slow");
            //alert(response.message);
        }
    });
    refreshChat();
}