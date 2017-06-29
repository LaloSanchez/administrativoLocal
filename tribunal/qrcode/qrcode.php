<?php 
	require("includes/qrcode.class.php");
	$qr = new SocialQrCode ();
	$qr->setType ( SocialQrCode::QRCODE_TYPE_PNG );
	if ($_REQUEST['download']==="1") {
		$qr->setSize ( 20 );
		$qr->generate ( $_GET['url'] );
		$qr->force_download ( 'qrcode.png' );
	} else {
		$qr->setSize ( 10 );
		$qr->generate ( $_GET['url'] );		
		$qr->show ();
	}
?>
