<?php
error_reporting(0);
/**
 * Description of EmailHELPER
 *
 * @author edeshe
 */
require_once('PHPMailer/class.phpmailer.php');

class EmailHELPER {
    private $mail;
    private $hostSmtp;
    private $portSmtp;
    private $senderAddress;
    private $toName = "Destinatario";
    private $toAddress;
    private $ccAddress;
    private $bccAddress;
    private $subject;
    private $isHTMLFormat;
    private $sendAttach;
    private $body;
    private $debug;
    private $loginUser;
    private $loginPass;
    private $auth = false;
    private $Filename;
    
    public function __construct() {
        $this->mail = new PHPMailer();
    }
    
    public function send(){
        $this->mail->IsSMTP();  // telling the class to use SMTP
        $this->mail->SMTPAuth = false;
        $this->mail->CharSet = "UTF-8";
        $this->mail->Host = $this->getHostSmtp(); // SMTP server
        //$mail->Port     = $this->getPortSmtp(); // SMTP server port
        $this->mail->SetFrom($this->getSenderAddress(), 'Notificación solicitud');
        $this->mail->AddAddress($this->getToAddress(), $this->getToName());
        if(strlen($this->getCcAddress()) > 0) $this->mail->AddCC($this->getCcAddress(), $this->getToName());
        $this->mail->Subject = $this->getSubject();
        $this->mail->AltBody = 'Para ver este mensaje usa un visor compatible con HTML';
        if($this->isSendAttach()){
            $this->mail->AddAttachment($this->getFilename());
        }
//        $this->mail->Body = $this->getBody();
        $this->mail->IsHTML(true);
        $this->mail->MsgHTML($this->getBody());
        $this->mail->WordWrap = 50;

        if(!$this->mail->Send()) {
//          echo "Mensaje NO enviado.<br>\n";
  //        echo "Mailer error: ".$this->mail->ErrorInfo."<br>\n";
          return false;
        } else {
          //echo "Mensaje enviado correctamente =).<br>\n";
          return true;
        }
    }

    public function getHostSmtp() {
            return $this->hostSmtp;
    }

    public function setHostSmtp($hostSmtp) {
            $this->hostSmtp = $hostSmtp;
    }

    public function getPortSmtp() {
            return $this->portSmtp;
    }

    public function setPortSmtp($portSmtp) {
            $this->portSmtp = $portSmtp;
    }

    public function getSenderAddress() {
            return $this->senderAddress;
    }

    public function setSenderAddress($senderAddress) {
            $this->senderAddress = $senderAddress;
    }

    public function getToAddress() {
            return $this->toAddress;
    }

    public function setToAddress($toAddress) {
            $this->toAddress = $toAddress;
    }
    
    public function getToName() {
            return $this->toName;
    }

    public function setToName($toName) {
            $this->toName = $toName;
    }

    public function getCcAddress() {
            return $this->ccAddress;
    }

    public function setCcAddress($ccAddress) {
            $this->ccAddress = $ccAddress;
    }

    public function getBccAddress() {
            return $this->bccAddress;
    }

    public function setBccAddress($bccAddress) {
            $this->bccAddress = $bccAddress;
    }

    public function getSubject() {
            return $this->subject;
    }

    public function setSubject($subject) {
            $this->subject = $subject;
    }

    public function isHTMLFormat() {
            return $this->isHTMLFormat;
    }

    public function setIsHTMLFormat($isHTMLFormat) {
            $this->isHTMLFormat = $isHTMLFormat;
    }

    public function isSendAttach() {
            return $this->sendAttach;
    }

    public function setSendAttach($sendAttach) {
            $this->sendAttach = $sendAttach;
    }

    public function getBody() {
            return $this->body;
    }

    public function setBody($body){
            $this->body = $body;
    }

    public function isDebug() {
            return $this->debug;
    }

    public function setDebug($debug) {
            $this->debug = $debug;
    }

    public function getLoginUser() {
            return $this->loginUser;
    }

    public function setLoginUser($loginUser) {
            $this->loginUser = $loginUser;
    }

    public function getLoginPass() {
            return $this->loginPass;
    }

    public function setLoginPass($loginPass) {
            $this->loginPass = $loginPass;
    }

    public function isAuth() {
            return $this->auth;
    }

    public function setAuth($auth) {
            $this->auth = $auth;
    }

    public function getFilename() {
            return $this->Filename;
    }

    public function setFilename($filename) {
            $this->Filename = $filename;
    }

}

?>
