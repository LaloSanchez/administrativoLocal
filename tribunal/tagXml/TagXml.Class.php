<?php

class TagXml {

    private $tag;
    private $newXml;
    private $newXmlFormat;
    private $child;

    public function cargaXml($path = "", $vista) {
        $xml = simplexml_load_file($path);

        for ($i = 0; $i < count($xml->vista); $i++) {
            $nombre = $xml->vista[$i]->attributes()->name;

            if ($nombre == $vista) {
                $this->tag = $xml->vista[$i];
            }
        }
    }

    public function getTag($tag) {
        return $this->tag->$tag;
    }

    public function getAttribut($tag, $attribut) {
        $atributos = $this->tag->$tag->attributes();
        return $atributos[$attribut];
    }

    public function createXml($path = "", $vista) {
        if (file_exists($path)) {
            $this->newXml = simplexml_load_file($path);
        } else {
            $this->newXml = new DomDocument('1.0', 'ISO-8859-1');
        }

        //$this->newXmlFormat = $this->newXml->asXML();
        $this->child = $this->newXml->addChild("vista");
        $this->child->addAttribute("name", $vista);
    }

    public function addChild($newTag,$prefijo) {
        $tag = $this->child->addChild($newTag, $newTag);
        $tag->addAttribute("name", $prefijo . ucwords($newTag));
        $tag->addAttribute("id", $prefijo . ucwords($newTag));
        $tag->addAttribute("hidden", "false");
        $tag->addAttribute("tooltip", $newTag);
        $tag->addAttribute("placeholder", $newTag);
    }

    public function saveXml($path = "") {
        $this->newXml->saveXML($path);
    }

}

//$tagXml = new TagXml();
//$tagXml->cargaXml("ejemplo.xml","frmBitacorasView");
//echo $tagXml->getTag("idBitacora")."<br>";
//echo $tagXml->getTag("cveAccion")."<br>";
//echo $tagXml->getAttribut("idBitacora","name")."<br>";
//$tagXml->createXml("ejemplo.xml", "frmBitacorasView");
//$tagXml->addChild("idBitacora");
//$tagXml->addChild("cveAccion");
//$tagXml->addChild("cveAccion");
//$tagXml->saveXml("ejemplo.xml");


//  if (file_exists("ejemplo.xml")) {
//  $xml = simplexml_load_file('ejemplo.xml');
//  } else {
//  $xml = new DomDocument('1.0', 'ISO-8859-1');
//  }


//  $xmlFormat = $xml->asXML();
//  //displaying the element in proper format
//  echo '<u><b>This is the xml code from ejemplo2.xml:</b></u>
//  <br /><br />
//  <pre>' . htmlentities($xmlFormat, ENT_COMPAT | ENT_HTML401, "ISO-8859-1") . '</pre><br /><br />';

  //adding new child to the xml
//  $newChild = $xml->addChild('vista');
//  $tag = $newChild->addChild('Prueba', 'Prueba');
//  $tag->addAttribute("name", "txtPrueba");
//  $newChild->addChild('price', '100 $');
//
//  //transforming the object in xml format
//  $xmlFormat = $xml->asXML();
//  //displaying the element in proper format
//  echo '<u><b>This is the xml code from test2.xml with new elements added:</b></u>
//  <br /><br />
//  <pre>' . htmlentities($xmlFormat, ENT_COMPAT | ENT_HTML401, "ISO-8859-1") . '</pre>';
//  $xml->saveXML('ejemplo.xml');

  //$tagXml->getTag("desAccion");
  //$tagXml->getTag("activo");
  //$xml = new DomDocument('1.0', 'ISO-8859-1');
  //$root = $xml->createElement('colaboradores');
  //$root = $xml->appendChild($root);
  //$colaborador=$xml->createElement('colaborador');
  //$colaborador =$root->appendChild($colaborador);
  //$nom=$xml->createElement('nombre','Erickr');
  //$nom =$colaborador->appendChild($nom);
  //
  //$apellido=$xml->createElement('apellido','campos');
  //$apellido =$colaborador->appendChild($apellido);
  //
  //$xml->formatOutput = true;  //poner los string en la variable $strings_xml:
  //
  //$strings_xml = $xml->saveXML();
  //
  ////Finalmente, guardarlo en un directorio:
  //
  //$xml->save('ejemplo.xml'); 
?>
