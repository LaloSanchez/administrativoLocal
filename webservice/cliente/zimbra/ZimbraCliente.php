<?php
/*
* Funciones para consumo de webservices en zimbra
*
*/

include_once(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");
include_once(dirname(__FILE__) . "/../../../tribunal/json/JsonDecod.Class.php");
include_once(dirname(__FILE__) . "/ZCS/Admin.php");
//print_r(dirname(__FILE__) . "/../../../tribunal/host/Host.Class.php");


// constantes
@define("ruta", dirname(__FILE__));
define('ZIMBRA_LOGIN', 'sigejupewebservice');
define('ZIMBRA_PASS',  '-Wq1x?3bS./');
define('ZIMBRA_SERVER', '10.22.157.26');
define('ZIMBRA_PORT', '7071');

class ZimbraCliente {

    private $host = null;
    private $zimbra = null;

    public function __construct() {
        $this->host = new Host(ruta . "/../../../tribunal/host/config.xml", "ZIMBRA");
        $this->host = $this->host->getConnect();

        // Create a new Admin class and authenticate
        $zimbra = new \sigejupev2\webservice\cliente\zimbra\ZCS\Admin(ZIMBRA_SERVER, ZIMBRA_PORT);
        $zimbra->auth(ZIMBRA_LOGIN, ZIMBRA_PASS);
        $this->zimbra = $zimbra;
        
    }

    public function getZimbraAccounts() {
        $accounts = $this->zimbra->getAccounts(array(
            'domain' => 'pjedomex.gob.mx',
            'offset' => 0,
            'limit'  => 100
        ));

        // And output them
        echo 'Cuentas en el servidor:';
        echo '<br>';
        foreach ($accounts as $account){
            echo $account->name . '<br/>';
        }
    }

    public function check_available_email($email)
    {
        try{
            $this->zimbra->getAccount('pjedomex.gob.mx', 'name', $email);
            // ya existe la cuenta, no esta disponible
            return false;
        }catch(Exception $e){
            // cuenta disponible
            return true;
        }
    }

    public function create_account($values)
    {
        try{
            $account = $this->zimbra->createAccount($values);
            return $account;
        }catch(Exception $e){
            $error = array('status' => 'error','msg'=>$e->error_message );
            echo json_encode($error);
            exit();
        }
    }

    public function delete_account($id_zimbra)
    {
        try{
            $account = $this->zimbra->deleteAccount($id_zimbra);
            return $account;
        }catch(Exception $e){
            $error = array('status' => 'error','msg'=>$e->error_message );
            echo json_encode($error);
            exit();
        }
    }
}


/*
// Get all available accounts from a domain
$accounts = $zimbra->getAccounts(array(
    'domain' => 'pjedomex.gob.mx',
    'offset' => 0,
    'limit'  => 100
));

// And output them
echo 'Cuentas en el servidor:';
echo '<br>';
foreach ($accounts as $account){
    echo $account->name . '<br/>';
}
echo '<br>';
echo '<br>';

// dominios
echo 'Dominios en el servidor:';
echo '<br>';
$dominios = $zimbra->getDomains();
foreach ($dominios as $dominio) {
    echo $dominio.'<br>';
}
echo '<br>';
echo '<br>';

// servers
echo 'Servidores:';
echo '<br>';
$servers = $zimbra->getServers();
foreach ($servers as $server) {
    echo $server.'<br>';
}
echo '<br>';
echo '<br>';

// capacidades
echo 'Quotas de pjedomex.gob.mx:';
echo '<br>';
$quota = $zimbra->getTotalQuota('pjedomex.gob.mx');
print_r($quota);
echo '<br>';
echo '<br>';

// capacidades
echo 'Listas de distribucion de pjedomex.gob.mx:';
echo '<br>';
$lista = $zimbra->getDistributionLists('pjedomex.gob.mx');
print_r($lista);
echo '<br>';
echo '<br>';
*/