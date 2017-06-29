$(document).ready(function() {
	$('#qrsubmit').click(function() {
		var url = $('input[name=qrurl]').val();
		$('#qrcode').remove();
		$('#qrresult').append('<img id="qrcode" src="qrcode.php?url='+url+'" />');
		$('#qrcodelink').remove();
		$('#qrresultlink').append('<a href="qrcode.php?download=1&url='+url+'" id="qrcodelink">DOWNLOAD</a>');
		return false;
	});
});