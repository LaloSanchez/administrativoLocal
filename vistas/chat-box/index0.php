<?php
session_start();
$cveuser="MOY";
$ordecat="00001";

$cadunic="+".$ordecat;

$token=md5($cadunic);

$_SESSION['idname']  = $cveuser;
$_SESSION['chatid']  = $token;
session_write_close();

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chat Box [<?php echo $token; ?>]</title>

<script src="ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="css/stylemessage.css">
<script type="text/javascript">


$(document).ready(function() {

	//toggle hide/show shout box
	$(".close_btn").click(function (e) {
		//get CSS display state of .toggle_chat element
		var toggleState = $('.toggle_chat').css('display');

		//toggle show/hide chat box
		$('.toggle_chat').slideToggle();

		//use toggleState var to change close/open icon image
		if(toggleState == 'block')
		{
			$(".header div").attr('class', 'open_btn');
		}else{
			$(".header div").attr('class', 'close_btn');
		}


	});
});

</script>
</head>

<body>

<!-- md5("SFB-TEST+00001") => bc891d8f242a2a4246d1a953c1aeea18 -->

<div class="shout_box">
<div class="header">SALA DE CHAT<div class="close_btn">&nbsp;</div></div>
  <div class="toggle_chat">
  <div class="message_box" >
      <iframe src="spachat.php" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>
    </div>
    </div>
</div>
</body>
</html>
