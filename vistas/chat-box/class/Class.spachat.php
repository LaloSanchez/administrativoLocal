<?php

/**
 * SPAChat - Simple PHP Angular Ajax Chat
 *
 * @date    2013-05-31
 * @author  Jonas Sciangula Street <joni2back {at} gmail.com>
 * @date    2016-01-26 SFB
 * @author  Salvador Fernandez B / Apache MIT License SIGEJUPE v2.0
 */

namespace SPA_Common;

include_once("../../tribunal/connect/Proveedor.Class.php");

class SPA_MySQL_Database {

    protected $_proveedor;
    private $_dbLink, $_queryResponse;
    public $lastResult;

    public function __construct() {
        $this->_proveedor = new \Proveedor('mysql', 'sigejupe');
        $this->_connect();
    }

    private function _connect() {
        $this->_dbLink = $this->_proveedor->connect();
    }

    public function query($query) {
        $this->_proveedor->execute($query);
        return $this;
    }

    public function getResults() {
        $this->lastResult = array();

        while ($response = $this->_proveedor->fetch_chatobject($this->_proveedor->stmt)) {
            $this->lastResult[] = $response;
        }

        $this->_proveedor->free_result($this->_proveedor->stmt);

        return $this->lastResult;
    }

    public function getOne() {

        $this->lastResult = $this->_proveedor->fetch_chatobject($this->_proveedor->stmt);
        $this->_proveedor->free_result($this->_proveedor->stmt);

        return $this->lastResult;
    }

    public function disconnect() {
        return $this->_proveedor->close();
    }

}

abstract class Model {

    public $db;

    public function __construct() {
        $this->db = new SPA_MySQL_Database;
    }

}

abstract class Controller {

    private $_request, $_response, $_query, $_post, $_server, $_cookies;
    protected $_currentAction, $_defaultModel;

    const ACTION_POSTFIX = 'Action';
    const ACTION_DEFAULT = 'indexAction';

    public function __construct() {
        $this->_request = &$_REQUEST;
        $this->_query = &$_GET;
        $this->_post = &$_POST;
        $this->_server = &$_SERVER;
        $this->_cookies = &$_COOKIE;
        $this->init();
    }

    public function init() {
        $this->dispatchActions();
        $this->render();
    }

    public function dispatchActions() {
        $action = $this->getQuery('action');
        if ($action && $action .= self::ACTION_POSTFIX) {
            if (method_exists($this, $action)) {
                $this->setResponse(
                        call_user_func(array($this, $action), array())
                );
            } else {
                $this->setHeader("HTTP/1.0 404 Not Found");
            }
        } else {
            $this->setResponse(
                    call_user_func(array($this, self::ACTION_DEFAULT), array())
            );
        }
        return $this->_response;
    }

    public function render() {
        if ($this->_response) {
            if (is_scalar($this->_response)) {
                echo $this->_response;
            } else {
                throw new \Exception('Response content must be type scalar');
            }
            exit;
        }
    }

    public function indexAction() {
        return null;
    }

    public function setResponse($content) {
        $this->_response = $content;
    }

    public function setHeader($params) {
        if (!headers_sent()) {
            if (is_scalar($params)) {
                header($params);
            } else {
                foreach ($params as $key => $value) {
                    header(sprintf('%s: %s', $key, $value));
                }
            }
        }
        return $this;
    }

    public function setModel($namespace) {
        $this->_defaultModel = $namespace;
        return $this;
    }

    public function setSession($key, $value) {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function setCookie($key, $value, $seconds = 3600) {
        $this->_cookies[$key] = $value;
        if (!headers_sent()) {
            setcookie($key, $value, time() + $seconds);
            return $this;
        }
    }

    public function getRequest($param = null, $default = null) {
        if ($param) {
            return isset($this->_request[$param]) ?
                    $this->_request[$param] : $default;
        }
        return $this->_request;
    }

    public function getQuery($param = null, $default = null) {
        if ($param) {
            return isset($this->_query[$param]) ?
                    $this->_query[$param] : $default;
        }
        return $this->_query;
    }

    public function getPost($param = null, $default = null) {
        if ($param) {
            return isset($this->_post[$param]) ?
                    $this->_post[$param] : $default;
        }
        return $this->_post;
    }

    public function getServer($param = null, $default = null) {
        if ($param) {
            return isset($this->_server[$param]) ?
                    $this->_server[$param] : $default;
        }
        return $this->_server;
    }

    public function getSession($param = null, $default = null) {
        if ($param) {
            return isset($this->_session[$param]) ?
                    $this->_session[$param] : $default;
        }
        return $this->_session;
    }

    public function getCookie($param = null, $default = null) {
        if ($param) {
            return isset($this->_cookies[$param]) ?
                    $this->_cookies[$param] : $default;
        }
        return $this->_cookies;
    }

    public function getModel() {
        if ($this->_defaultModel && class_exists($this->_defaultModel)) {
            return new $this->_defaultModel;
        }
    }

    public function sanitize($string, $quotes = ENT_QUOTES, $charset = 'utf-8') {
        return htmlentities($string, $quotes, $charset);
    }

}

abstract class Helper {
    
}

namespace SPA_Chat;

use SPA_Common;

class Model extends SPA_Common\Model {

    public function getMessages($token) {

        $sql = "(SELECT idChatMessage as id, nombreUsuario as username, cveUsuario as iduser, ";
        $sql = $sql . "mensaje as message, ipUsuario as ip, DATE_FORMAT(fechaRegistro,'%d-%m-%Y %H:%i:%s') as date, chatId as chatid, ";
        $sql = $sql . "cveNumero as cveNumero, tipoChat as tipochat FROM tblchatmessages ";
        $sql = $sql . "WHERE chatid like '" . $token . "' ";
        $sql = $sql . "ORDER BY DATE_FORMAT(`date`,'%d-%m-%Y %H:%i:%s') ASC)";
//echo $sql;
        $response = $this->db->query($sql);

        return $response->getResults();
    }

    public function addMessage($username, $iduser, $message, $ip, $token) {
        if ($this->isChatClosed() >= 1) {
            return false;
        } else {
            $dangerous_characters = array('"', "'", "\\");
//            $username = addslashes($username);
//            $message = addslashes($message);
            $username = str_replace($dangerous_characters, ' ', $username);
            $message = str_replace($dangerous_characters, ' ', $message);

            $username = utf8_decode($username);
            $message = utf8_decode($message);
            return (bool) $this->db->query("INSERT INTO tblchatmessages
            VALUES (NULL, '{$username}','{$iduser}','{$message}', '{$ip}', (SELECT DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s')),'{$token}','" . CVENUMERO . "','" . CHATTYPE . "')");
        }
    }

    public function getOnline($count = true, $timeRange = CHAT_ONLINE_RANGE) {
        if ($count) {
            $response = $this->db->query("SELECT count(*) as total FROM tblchatonline");
            return $response->getOne();
        }
        return $this->db->query("SELECT ipUsuario as ip FROM tblchatonline")->getResults();
    }

    public function isChatClosed() {
        $response = $this->db->query("select count(*) as id from tblchatcerrados where chatId='" . TOKEN . "'");
        return $response->getOne()->id;
    }

    public function getOnlineUsers() {
        $sql = "select nombreUsuario as username,if (fechaActualizacion>subtime(now(),'0:01'),1,0) as status from tblchatonline where chatId='" . TOKEN . "' group by nombreUsuario;";
        $response = $this->db->query($sql);
        return $response->getResults();
    }

    public function updateOnline($hash, $username, $ip) {
        return (bool) $this->db->query("REPLACE INTO tblchatonline
            VALUES ('{$hash}','{$username}'," . IDUSER . ",'{$ip}', NOW(),'" . TOKEN . "')") or die(mysql_error());
    }

    public function __destruct() {
        if ($this->db) {
            $this->db->disconnect();
        }
    }

}

class Controller extends SPA_Common\Controller {

    protected $_model;

    public function __construct() {
        $this->setModel('SPA_Chat\Model');
        $GLOBALS['users'] = $this->getModel()->getOnlineUsers();
        $_SESSION['usersOnLine'] = $this->getModel()->getOnlineUsers();
        parent::__construct();
    }

    public function indexAction() {
        
    }

    public function listAction() {

        $messages = $this->getModel()->getMessages(TOKEN);

        foreach ($messages as &$message) {
            $message->me = $this->getServer('REMOTE_ADDR') === $message->ip;
        }

        //print_r($messages);
        //json_encode($messages);
        //return $this->array2json($messages);
        //$resu='"id":"81","username":"SFB","iduser":"666","message":"dddd","ip":"10.22.165.166","date":"2016-02-16 16:39:13","chatid":"a1c6a4f2f36f2a7a68af7d540e4848e3","cveNumero":"56","tipochat":"4","me":true';
        //return "[{".$resu."}]";

        $str = "[";

        foreach ($messages as &$message) {
            if ($str == "[")
                $str = $str . '{"id":"' . $message->id . '","username":"' . utf8_encode($message->username) . '","message":"' . utf8_encode($message->message) . '","date":"' . $message->date . '","me":"' . $message->me . '"}';
            else
                $str = $str . ',{"id":"' . $message->id . '","username":"' . utf8_encode($message->username) . '","message":"' . utf8_encode($message->message) . '","date":"' . $message->date . '","me":"' . $message->me . '"}';
        }
        $str = $str . "]";

        $this->setHeader(array('Content-Type' => 'application/json'));
        return $str;
    }

    public function saveAction() {
        $username = $this->getPost('username');
        $message = $this->getPost('message');
        $ip = $this->getServer('REMOTE_ADDR');
        //$this->setCookie('username', $username, 9999 * 9999);

        $result = array('success' => false);
        if ($username && $message) {
            $cleanUsername = preg_replace('/^' . ADMIN_USERNAME_PREFIX . '/', '', $username);
            $result = array(
                'success' => $this->getModel()->addMessage($cleanUsername, IDUSER, $message, $ip, TOKEN)
            );
        }

        if ($this->_isAdmin($username)) {
            $this->_parseAdminCommand($message);
        }

        $this->setHeader(array('Content-Type' => 'application/json'));
        return json_encode($result);
    }

    private function _isAdmin($username) {
        return preg_match('/^' . ADMIN_USERNAME_PREFIX . '/', $username);
    }

    private function _parseAdminCommand($message) {
        if ($message == '/clear') {
            $this->getModel()->removeMessages();
            return true;
        }
        if ($message == '/online') {
            $online = $this->getModel()->getOnline(false);
            $ipArr = array();
            foreach ($online as $item) {
                $ipArr[] = $item->ip;
            }
            $message = 'Online: ' . implode(", ", $ipArr);
            $this->getModel()->addMessage('Admin', IDUSER, $message, '0.0.0.0', '-');
            return true;
        }
    }

    private function _getMyUniqueHash() {
        $unique = $this->getServer('REMOTE_ADDR');
        $unique .= $this->getServer('HTTP_USER_AGENT');
        $unique .= $this->getServer('HTTP_ACCEPT_LANGUAGE');
        $unique .= IDUSER;
        return md5($unique);
    }

    public function checkAction() {
        $this->setHeader(array('Content-Type' => 'application/json'));
        $result = array(
            'success' => $this->getModel()->ischatClosed()
        );

        return json_encode($result);
    }

    public function pingAction() {
        $ip = $this->getServer('REMOTE_ADDR');
        $hash = $this->_getMyUniqueHash();

        $this->getModel()->updateOnline($hash, USERNAME, $ip);

        $onlines = $this->getModel()->getOnline();

        $GLOBALS['users'] = $this->getModel()->getOnlineUsers();

        $this->setHeader(array('Content-Type' => 'application/json'));
        return json_encode($onlines);
    }

    public function usersonlineAction() {
        $online = $this->getModel()->getOnlineUsers();
        $this->setHeader(array('Content-Type' => 'application/json'));
        return json_encode($online);
    }

}

$chatApp = new Controller();

$ischatClose = $chatApp->getModel()->ischatClosed();

if ($ischatClose >= 1) {
    $mensaje = "Chat cerrado por el Juez..." . print_r($ischatClose);
    $activar = "disabled=\"disabled\"";
} else {
    $mensaje = "Escribe el mensaje...";
    $activar = " ";
}
?>
