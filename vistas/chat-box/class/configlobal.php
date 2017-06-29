<?php

//$tokenchat=$_GET['chatid'];

//session_id($tokenchat);
session_start();

define('TOKEN',$_SESSION['chatid']);
define('USERNAME',$_SESSION['idname']);
define('IDUSER',$_SESSION['iduser']);
define('CVENUMERO',$_SESSION['cveNumero']);
define('CHATTYPE',$_SESSION['tipochat']);

define('UPLOAD_DIR',"./uploads/");
define('MAXFILESIZE',10000000);
define('DB_USERNAME',       'sigejupe');
define('DB_PASSWORD',       'sigejupe2015');
define('DB_HOST',           'localhost');
define('DB_NAME',           'htsj_sigejupe');
define('CHAT_HISTORY',      '150');
define('CHAT_ONLINE_RANGE', '1');
define('ADMIN_USERNAME_PREFIX', 'super_');

//define('TOKEN','bc891d8f242a2a4246d1a953c1aeea18');
?>
