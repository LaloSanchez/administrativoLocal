

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.security.AccessController;
import java.security.PrivilegedAction;
import java.util.logging.Level;
import java.util.logging.Logger;

import javax.swing.JApplet;

public class LanzaWS extends JApplet implements ActionListener {

    public void init() {
        //Nada
        String strl="";
        System.out.println("Lanzando Java Web Start.");
        createDir("C:\\BORRAME");
        //idCarpetaJudicial=
       
        strl="0-125-NOTIFICACION-JUZGADO DE CONTROL DE TOLUCA-1-22-80-2016";
        strl+="-http://10.22.157.48/sigejupe/fachadas/sigejupe/imagenes/ImagenesFacade.Class.php";
        strl+="-http://10.22.165.166/sigejupev2/vistas/digitalizacion/";
          
        Lanzar (strl);
/*	 
idCarpetaJudicial=0
idActuacion=125
Descripcion=NOTIFICACION TRADICIONAL
descJuzgado=JUZGADO DE CONTROL DE TOLUCA
Numero=1
cveDocumento=22
idUsuario=80
anio=2016
URLupload=http://10.22.157.48/sigejupe/fachadas/sigejupe/imagenes/ImagenesFacade.Class.php
URLcodebase=http://10.22.165.166/sigejupev2/vistas/digitalizacion/
 */  
    }
    
    public void Lanzar(String cadenalarga)
    {       
    //String path="C:\\Program Files (x86)\\Java\\jdk1.8.0_73\\bin\\javaws.exe";
    String path="C:\\BORRAME\\javaws.exe";
    String jnlpfile="C:\\borrame\\digital.jnlp";
    
    String[] cmd=new String[] { path, jnlpfile };
    System.out.println(cmd[0]+"-"+cmd[1]+"-"+cadenalarga);
    crear_jnlp(cadenalarga);
    lanzarws (cmd);
    }

    public Boolean SaveToFile(String text)
    {
    final String textEx = text;
    return AccessController.doPrivileged(new PrivilegedAction<Boolean>() {

            @Override
            public Boolean run()
            {

    File file = new File("C:\\borrame\\digital.jnlp");

    // if file doesnt exists, then create it 
    if ( ! file.exists( ) )
    {
        try {
            file.createNewFile();
        } catch (IOException ex) {
            Logger.getLogger(LanzaWS.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    file.setReadable(true);
    file.setWritable(true);
    FileWriter fw = null;
                try {
                    fw = new FileWriter( file.getAbsoluteFile( ) );
                } catch (IOException ex) {
                    Logger.getLogger(LanzaWS.class.getName()).log(Level.SEVERE, null, ex);
                }
    BufferedWriter bw = new BufferedWriter( fw );
                try {
                    bw.write( textEx );
                } catch (IOException ex) {
                    Logger.getLogger(LanzaWS.class.getName()).log(Level.SEVERE, null, ex);
                }
                try {
                    bw.close( );
                } catch (IOException ex) {
                    Logger.getLogger(LanzaWS.class.getName()).log(Level.SEVERE, null, ex);
                }
    return true;
    } 
    }
    );
    }
    
     private boolean createDir(String path) {
        try {
            File files = new File(path);
            if (!files.exists()) {
                if (files.mkdirs()) {
                    System.out.println("Carpeta creada de forma correcta");
                    
                    return true;
                } else {
                    System.out.println("Error al crear la carpeta");
                    return false;
                }
            } else {
                System.out.println("La carpeta ya existe");
                return true;
            }
        } catch (Exception e) {
            System.out.println("Existio un error al crear la ruta");
            return false;
        }
    }

    
    public void crear_jnlp(String cadenalarga) 
    {
        
       String[] vars = cadenalarga.split("-");
            
      String jnlptxt="";    
   
       jnlptxt+= "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n";
       jnlptxt+= "<jnlp codebase=\""+vars[9]+"\" href=\"\" spec=\"1.0+\">\n";
       jnlptxt+= "<information>\n";
       jnlptxt+= " <title>scanner</title>\n";
       jnlptxt+= " <vendor>bySFB</vendor>\n";
       jnlptxt+= " <homepage href=\"\"/>\n";
       jnlptxt+= "	<offline-allowed/>\n";
       jnlptxt+= "<description>scanner</description>\n";
       jnlptxt+= "<description kind=\"short\">scanner</description>\n";
       jnlptxt+= "</information>\n";
       jnlptxt+= "<update check=\"always\"/>\n";
       jnlptxt+= "<security>\n";
       jnlptxt+= "<all-permissions/>\n";
       jnlptxt+= "</security>\n";
       jnlptxt+= "<resources>\n";
       jnlptxt+= " <j2se version=\"1.6+\"/>\n";        
       jnlptxt+= "	<jar href=\"lib/commons-io-2.3.jar\"/>\n";
       jnlptxt+= "	<jar href=\"lib/commons-logging-1.1.3.jar\"/>\n";
       jnlptxt+= "<jar href=\"lib/httpclient-4.3.5.jar\"/>\n";
       jnlptxt+= "    <jar href=\"lib/httpcore-4.3.2.jar\"/>\n";
	jnlptxt+= "	<jar href=\"lib/httpmime-4.3.5.jar\"/>\n";
	jnlptxt+= "	<jar href=\"lib/itextpdf-5.5.3.jar\"/>\n";
	jnlptxt+= "	<jar href=\"lib/jai_imageio-1.1.jar\"/>\n";
	jnlptxt+= "	<jar href=\"lib/morena.jar\"/>\n";
	jnlptxt+= "	<jar href=\"lib/morena_license.jar\"/>\n";
	jnlptxt+= "	<jar href=\"lib/morena_windows.jar\"/>\n";
	jnlptxt+= "	<jar href=\"lib/morena_osx.jar\"/>\n";
	jnlptxt+= "	<jar href=\"lib/twain.jar\"/>\n";
        jnlptxt+= "<jar href=\"scanner.jar\" main=\"true\"/>\n";
        jnlptxt+= "</resources>\n";
        jnlptxt+= "<application-desc main-class=\"digitalizacion.Digitalizacion\">\n";
                
jnlptxt+= "<argument>idCarpetaJudicial="+vars[0]+"</argument>\n";
jnlptxt+= "    <argument>idActuacion="+vars[1]+"</argument>\n";
jnlptxt+= "    <argument>Descripcion="+vars[2]+"</argument>\n";
jnlptxt+= "    <argument>descJuzgado="+vars[3]+"</argument>\n";
jnlptxt+= "    <argument>Numero="+vars[4]+"</argument>\n";
jnlptxt+= "    <argument>cveDocumento="+vars[5]+"</argument>\n";
jnlptxt+= "    <argument>idUsuario="+vars[6]+"</argument>\n";
jnlptxt+= "    <argument>anio="+vars[7]+"</argument>\n";
jnlptxt+= "    <argument>URLupload="+vars[8]+"</argument>\n";
jnlptxt+= "	</application-desc>\n";
jnlptxt+= "</jnlp>\n";
        
        if(SaveToFile(jnlptxt)) System.out.println ("creado correctamente JNLP");
        else System.out.println ("creado correctamente JNLP");
    }        
    
    
    
        public void lanzarws(final String params[]) {
        AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                try {
                    System.out.println("*************************************************");
                    System.out.println("Se manda traer el programa de digitalizacion");
                    System.out.println("params: " + params);
                    System.out.println("*************************************************");

                    Process p = Runtime.getRuntime().exec(params);
               
                    //*****
                    BufferedReader reader = new BufferedReader(new InputStreamReader(p.getInputStream()));
                    while ((reader.readLine()) != null) {}
                    //******
                    p.waitFor();
                } catch (Exception e) {
                    System.err.println("Error: " + e);
                }
                return null;
            }
        });
    }

    @Override
    public void actionPerformed(ActionEvent e) {
        throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
    }
    
    

}
