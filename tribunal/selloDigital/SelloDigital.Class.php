<?php
/**
 * Description of selloDigital
 *
 * @author edeshe
 */
include_once('Encryption.Class.php');

class selloDigital {
    
    private $nombreArchivoKey;
    private $cadenaOriginal;
    private $privKey;
    
    public function getSelloDigital(){
//        $rsa = new Crypt_RSA();
////        $rsa->loadKey('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArd+uKDi7g6cpmbotPre9KpFK4U1rM/FlEtHszIrMBmArLID4/uImes2L6K5sbKHbo3sIVdzFgrtB/ZdHTN11bM26OLpovCMs/HF3tqz93RMobdNv63IyOau4YgKZa+U2sW+1fGT1HtBesqGjlVlLZNJVR9ZETj4fuLKjNzOgegdbYFV9jIyP6JDi/9c6oyFRUI1anSWZcDFL+74Y4h4okAcbDgooczxBf3QlIJQdFVs9GOPCoIwmZ29c4VmBvESlRnjtXho/6sNQXlnLsG4v4GCzL7P1YOOf9FqV8XZuXJrEsTVJjDdYJdia6F3G/GRe+lRhReM42qlHqLKZpzjSFwIDAQAB'); // public key
//        $rsa->loadKey($this->getPrivKey()); // public key
//
//        $rsa->setEncryptionMode(CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
//        $ciphertext = $rsa->encrypt($this->getCadenaOriginal());
//        
//        $ciphertext = bin2hex($ciphertext);
//        $ciphertext = chunk_split($ciphertext,76);
//        return $ciphertext;

        $converter = new Encryption;
        $converter->setPrivateKey($this->getPrivKey());
        $encoded = $converter->encode(trim($this->getCadenaOriginal()));
//        echo "Sello Digital:<br/>".$encoded;
//        echo "<br/>";
//        echo "Cadena Original: <br/>".$converter->decode($encoded);
        return $encoded;
    }
        
    public function setNombreArchivoKey($nombreArchivoKey){
        $this->nombreArchivoKey = $nombreArchivoKey;
    }
    
    public function getNombreArchivoKey(){
        return $this->nombreArchivoKey;
    }
    
    public function setCadenaOriginal($cadenaOriginal){
        $this->cadenaOriginal = $cadenaOriginal;
    }
    
    public function getCadenaOriginal(){
        return $this->cadenaOriginal;
    }
    
    public function setPrivKey($privKey){
        $this->privKey = md5($privKey);
    }
    
    public function getPrivKey(){
        return $this->privKey;
    }
    
}

?>
