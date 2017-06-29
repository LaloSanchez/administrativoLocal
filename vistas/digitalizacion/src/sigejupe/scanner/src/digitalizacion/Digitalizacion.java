package digitalizacion;

import SK.gnome.twain.TwainException;
import com.itextpdf.text.Document;
import com.itextpdf.text.Rectangle;
import com.itextpdf.text.pdf.PRStream;
import com.itextpdf.text.pdf.PdfName;
import com.itextpdf.text.pdf.PdfObject;
import com.itextpdf.text.pdf.PdfReader;
import com.itextpdf.text.pdf.PdfWriter;
import com.itextpdf.text.pdf.parser.PdfImageObject;
import escaner.Escaner;
import imagenes.CuadroImagen;
import imagenes.ImageTool;
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Container;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics2D;
import java.awt.GridLayout;
import java.awt.List;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.geom.AffineTransform;
import java.awt.image.BufferedImage;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.net.MalformedURLException;
import java.net.URL;
import java.security.AccessController;
import java.security.PrivilegedAction;
import java.util.Arrays;
import java.util.Vector;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.imageio.ImageIO;
import javax.swing.JApplet;
import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JFileChooser;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JProgressBar;
import javax.swing.JScrollPane;
import java.awt.TextArea;
import java.io.BufferedReader;
import java.io.FileFilter;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.PrintStream;
import java.util.ArrayList;
import java.util.Iterator;
import javax.imageio.ImageReader;
import javax.imageio.stream.ImageInputStream;
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
import javax.swing.JCheckBox;
import javax.swing.JFrame;
import org.apache.commons.io.comparator.LastModifiedFileComparator;
import org.apache.http.HttpEntity;
import org.apache.http.client.config.RequestConfig;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.MultipartEntityBuilder;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClientBuilder;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.util.EntityUtils;
import org.apache.commons.io.FilenameUtils;
import org.apache.commons.io.filefilter.WildcardFileFilter;

public class Digitalizacion extends JApplet implements ActionListener {

    CuadroImagen cuadro = null;
    JScrollPane scroll = null;
    JPanel pOpciones = null;
    JPanel pAcciones = null;

    JComboBox cboZoom = null;
    JComboBox cboDispositivos = null;

    JButton btnRefrescar = null; 
    JButton btnAumentar = null;
    JButton btnDisminuir = null;
    JButton btnRotar = null;
    JButton btnEscanear = null;
    JButton btnExaminar = null;
    JButton btnSubir = null;
    JButton btnConvertir = null;
    TextArea textArea=null;
    JProgressBar progreso = null;
    JLabel pAccion = null;
    JCheckBox chcDoble = null;
    JCheckBox chcColor = null;
    
    JLabel lblDispositivos = null;

    String path = "C:\\borrame";
    String importadir=null;
    private Vector vImages = null;
    private File files = null;

    private int numeroimg = 0;
    public boolean duplex; 
    public boolean encolor=false;

//    private TwainSource sr[];
    BufferedImage imagen = null;
    private String archivoActual = "";
    private CloseableHttpResponse response;
     
    digitalizacion.FileDirectory mydir;
    digitalizacion.InfoPanel myInfoPanel;
    
    public String idCarpetaJudicial;
    public String idActuacion;
    public String Descripcion;
    public String cveDocumento;
    public String Numero;
    public String anio;
    public String descJuzgado;

    
    public String idUsuario;
    public String URL;
    
    public String prefix;
    
    
 
    
 boolean validname(String filename)
{
 int pos1, pos2;
 pos1=filename.indexOf(".");
 pos2=filename.lastIndexOf(".");
 
 if (pos1!=pos2) return false;
 if ((filename.indexOf(".pdf")>-1)||(filename.indexOf(".jpg")>-1)) return true;
 else return false; 
 
}   
 
    public String getURLSubmit()
    {
    String urlprm;
   
    if (this.getAnio().length()>1)
    {    
        
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
    urlprm="";
    urlprm="idCarpetaJudicial="+this.getIdCarpetaJudicial()+"&"; 
    urlprm=urlprm+"idActuacion="+this.getIdActuacion()+"&";
    urlprm=urlprm+"Descripcion="+this.getDescripcion()+"&";
    urlprm=urlprm+"descJuzgado="+this.getDescJuzgado()+"&";
    urlprm=urlprm+"Numero="+this.getNumero()+"&";  
    urlprm=urlprm+"anio="+this.getAnio()+"&";
    urlprm=urlprm+"cveDocumento="+this.getcveDocumento()+"&";
    urlprm=urlprm+"idUsuario="+this.getIdUsuario()+"&";
    urlprm=urlprm+"accion=guardar";
    
    return urlprm;
    }
    else {
         //return "";
         return ("idCarpetaJudicial=0&idActuacion=125&Descripcion=NOTIFICACION TRADICIONAL&DescJuzgado=JUZGADO DE CONTROL DE TOLUCA&Numero=155&cveDocumento=22&idUsuario=80&anio=2016&accion=guardar");
         } 
    }        
   
      public void setPrefix() 
      {
        this.prefix= this.getDescripcion().substring(0, 3)+"_"+
        this.getIdCarpetaJudicial()+"_"+
        this.getIdActuacion()+"_";        
      }
    
      public String getPrefix() 
      {
       return this.prefix;
      }
      
    public String getIdUsuario() {
        return idUsuario;
    }

    public void setIdUsuario(String idUsuario) {
        this.idUsuario = idUsuario;
    }
      
    public String getDescripcion() {
        return Descripcion;
    }

    public void setDescripcion(String Descripcion) {
        this.Descripcion = Descripcion;
    }
    
    public String getIdCarpetaJudicial() {
        return idCarpetaJudicial;
    }

    public void setIdCarpetaJudicial(String idCarpetaJudicial) {
        this.idCarpetaJudicial = idCarpetaJudicial;
    }

    public String getIdActuacion() {
        return idActuacion;
    }

    public void setIdActuacion(String idActuacion) {
        this.idActuacion = idActuacion;
    }

    public String getcveDocumento() {
        return cveDocumento;
    }

    public void setcveDocumento(String Descripcion) {
        this.cveDocumento = Descripcion;
    }
    
    public String getURL() {
        return URL;
    }

    public void setURL(String URL) {
        this.URL = URL;
    }

    public String getDescJuzgado() {
        return descJuzgado;
    }

    public void setDescJuzgado(String descJuzgado) {
        this.descJuzgado = descJuzgado;
    }

    public String getNumero() {
        return Numero;
    }

    public void setNumero(String Numero) {
        this.Numero = Numero;
    }

    public String getAnio() {
        return anio;
    }

    public void setAnio(String anio) {
        this.anio = anio;
    }

   
    public void botones_scanneando()
    {        
        btnRefrescar.setEnabled(false);
        btnAumentar.setEnabled(false);
        btnDisminuir.setEnabled(false);
        btnEscanear.setEnabled(true);
        btnSubir.setEnabled(false);
        chcDoble.setEnabled(false);
        chcColor.setEnabled(false); 
        progreso.setEnabled(true);
    }
    
    public void botones_subiendo()
    {        
        btnRefrescar.setEnabled(false);
        btnAumentar.setEnabled(false);
        btnDisminuir.setEnabled(false);
        btnEscanear.setEnabled(false);
        btnSubir.setEnabled(false);
        chcDoble.setEnabled(false);
        chcColor.setEnabled(false);
        progreso.setEnabled(true);
    }
    

    public void botones_deshabilitar()
    {        
        btnRefrescar.setEnabled(false);
        btnAumentar.setEnabled(false);
        btnDisminuir.setEnabled(false);
        btnEscanear.setEnabled(false);
        btnSubir.setEnabled(false);
        chcDoble.setEnabled(false);
        chcColor.setEnabled(false);
        progreso.setVisible(true);
        progreso.setEnabled(false);
    }
    
     public void botones_habilitar()
    {        
        btnRefrescar.setEnabled(true);
        btnAumentar.setEnabled(true);
        btnDisminuir.setEnabled(true);
        btnEscanear.setEnabled(true);
        btnSubir.setEnabled(true);
        chcDoble.setEnabled(true);
        chcColor.setEnabled(true);
        progreso.setVisible(true);
    }
    
    public void init() {
        cuadro = new CuadroImagen();
        scroll = new JScrollPane(cuadro, JScrollPane.VERTICAL_SCROLLBAR_ALWAYS, JScrollPane.HORIZONTAL_SCROLLBAR_ALWAYS);
        getContentPane().add(scroll, BorderLayout.CENTER);

        pOpciones = new JPanel();
        
        btnConvertir = new JButton("CONVERTIR");
        btnRefrescar = new JButton();
        btnRefrescar.setBorder(BorderFactory.createEmptyBorder());
      
        btnRefrescar.setIcon(new ImageIcon(getClass().getResource("/Resources/refresh.png")));
        
        btnAumentar = new JButton("+");
        btnDisminuir = new JButton("-");
        btnRotar = new JButton("Rotar");
        btnEscanear = new JButton("Escanear");
        btnExaminar = new JButton("Importar..");
        btnSubir = new JButton("Subir");
        
        lblDispositivos = new JLabel("Dispositivos");
        
        chcDoble = new JCheckBox("Doble",true);
        chcColor = new JCheckBox("Color",false);
      
     
        progreso = new JProgressBar();
        
        Dimension prefSize = progreso.getPreferredSize();
        prefSize.width = 80;
        progreso.setPreferredSize(prefSize);
        
        progreso.setSize(this.getWidth()-50, 200);
        progreso.setVisible(true);
        progreso.setEnabled(true);
                
        //pOpciones.add(btnConvertir); 
        pOpciones.add(btnRefrescar);
        pOpciones.add(btnAumentar);
        pOpciones.add(btnDisminuir);
        pOpciones.add(btnEscanear);
        pOpciones.add(chcDoble);
        pOpciones.add(chcColor);
        pOpciones.add(btnSubir);
        pOpciones.add(btnExaminar);
        pOpciones.add(progreso);
        
        duplex = true;
        pAcciones = new JPanel();
        pAcciones.add(pOpciones, BorderLayout.CENTER);
 ;
        //pAcciones.add(progreso, BorderLayout.SOUTH);

        getContentPane().add(pAcciones, BorderLayout.NORTH);
        
       // btnConvertir.addActionListener(this);
        btnRefrescar.addActionListener(this);
        btnAumentar.addActionListener(this);
        btnDisminuir.addActionListener(this);
        btnEscanear.addActionListener(this);
        btnExaminar.addActionListener(this);
        btnSubir.addActionListener(this);
        btnRotar.addActionListener(this);
        chcDoble.addActionListener(this);
        chcColor.addActionListener(this);

        getContentPane().setSize(700, 650);
        if (createDir() == false) {
            JOptionPane.showMessageDialog(this, "El directorio " + path + " No existe ");
             this.myInfoPanel.SettextArea("El directorio " + path + " No existe .");
        }
    }

    private String browseButton() {
        try {
            JFileChooser browser = new JFileChooser();
            File f = new File("C:\\");
            File ruta = null;
            browser.setDialogTitle("Importar desde....");
            browser.setCurrentDirectory(f);
            browser.setFileSelectionMode(JFileChooser.DIRECTORIES_ONLY);

            int seleccion = browser.showDialog(Digitalizacion.this, "Select");
            if (seleccion == browser.APPROVE_OPTION) {
                //ruta = browser.getCurrentDirectory();
                ruta = browser.getSelectedFile();
            }
            if (seleccion == browser.ABORT || seleccion == browser.CANCEL_OPTION || seleccion == browser.ERROR_OPTION) {
                System.out.println("El usuario cancelo");
                this.myInfoPanel.SettextArea("El usuario canceló.");
                botones_habilitar();
            }

            //System.out.println("Path: " + ruta.toString());
            return ruta.toString();
        } catch (Exception e) {
            System.out.println("Error: al seleccionar la ruta" + e.toString());
            return "";
        }
    }

/*    private void ultimoNumero() {
        File[] ficheros = getListDir();
        Integer nodos = ficheros.length;
        for (int index = 0; index < nodos; index++) {
            if (isDirectorio(ficheros[index].getName()) == false) {
                if (ficheros[index].getName().substring((ficheros[index].getName().length() - 4), ficheros[index].getName().length()).equals(".pdf")) {
                   // if (ficheros[index].getName().substring(0, 3) == null ? "tmp" == null : ficheros[index].getName().substring(0, 3).equals("tmp")) {
                   if (ficheros[index].getName().substring(0, 3) == null ? this.prefix == null : ficheros[index].getName().substring(0, 3).equals(this.prefix)) {
                   
                        if (numero < Integer.parseInt(ficheros[index].getName().substring(3, (ficheros[index].getName().length() - 4)))) {
                            numero = Integer.parseInt(ficheros[index].getName().substring(3, (ficheros[index].getName().length() - 4)));
                            System.out.println("Numero: " + Integer.toString(numero));
                        }
                    }
                }
            }
        }
    }
*/
    
    public static boolean contiene(String[] arr, String targetValue) {
	for(String s: arr){
		if(s.equals(targetValue))
			return true;
	}
	return false;
}
    
    
    private Integer ultimoConvertido() {
        
        String jpgfile;
        String pdffile;
        
        int index;
        File[] ficheros = getListDir();
        
      String[] archivos = new String[ficheros.length];
      
      for (int i = 0; i < ficheros.length; i++) {
         archivos[i] = ficheros[i].getName();
      }
             
        for (index = 0; index < ficheros.length; index++) {
        
            if (isDirectorio(ficheros[index].getName()) == false) {
                //********* 
                jpgfile=FilenameUtils.getBaseName(ficheros[index].getName())+".jpg";
                pdffile=FilenameUtils.getBaseName(ficheros[index].getName())+".pdf";
                
                if (contiene(archivos,jpgfile) && !contiene(archivos, pdffile)) break;     
            }
        }
        
       return index;
    }
    
    private void ultimoNumero() {
        File[] ficheros = getListDir();
        Integer nodos = ficheros.length;
        Integer numactual=0;
        
        for (int index = 0; index < nodos; index++) {
        
            if (isDirectorio(ficheros[index].getName()) == false) {
                //********* 
                //System.out.println("checando: " +ficheros[index].getName());
                if ((ficheros[index].getName().contains(".pdf"))&&(ficheros[index].getName().contains(this.prefix))) {
                        numactual= Integer.parseInt(ficheros[index].getName().substring(ficheros[index].getName().lastIndexOf("_")+1,ficheros[index].getName().lastIndexOf(".")));
                        //System.out.println("Numero actual: " + Integer.toString(numactual));
                            
                        if (numeroimg < numactual)  numeroimg = numactual;                     
                }
                //****** 
            }
        }
        
        System.out.println("Numero maximo: " + Integer.toString(numeroimg));
    }
    
    
    private boolean isDirectorio(String url) {
        try {
            File f = new File(path + "\\" + url);
            return f.isDirectory();
        } catch (Exception e) {
            System.out.println("Error: " + e.getMessage());
            return true;
        }
    }

    private boolean createDir() {
        try {
            files = new File(path);
            if (!files.exists()) {
                if (files.mkdirs()) {
                    System.out.println("Carpeta creada de forma correcta");
                    botones_habilitar();
                    return true;
                } else {
                    System.out.println("Error al crear la carpeta");
                    return false;
                }
            } else {
                System.out.println("La carpeta ya existe");
                botones_habilitar();
                return true;
            }
        } catch (Exception e) {
            System.out.println("Existio un error al crear la ruta");
            return false;
        }
    }

    private File[] getListDir() {
        try {
            File f = new File(path);
            String[] ficheros = null;
            
            FileFilter filter = new FileFilter() {
            @Override
            public boolean accept(File pathname) {
              return validname(pathname.getName());
            }
            };
            
            if (f.exists()) {
                File[] tmp = f.listFiles(filter);
                 
                Arrays.sort(tmp, LastModifiedFileComparator.LASTMODIFIED_COMPARATOR);
                return tmp;
//                int contador=0;
//                for(int i=0; i < tmp.length;i++){
////                  ficheros[contador] = tmp[i].getName();
//                  System.out.println("Name: " + tmp[i].getName());
//                  contador++;
//                }
//                ficheros = f.list();
            }
//            return ficheros;
        } catch (Exception e) {
            File[] ficheros = null;
            System.out.println("Error: " + e.getMessage());
            return ficheros;
        }
        return null;
    }

     private File[] getDirOrigen(String ruta) {
        try {
            File f = new File(ruta);
            String[] ficheros = null;
            
            FileFilter filter = new FileFilter() {
            @Override
            public boolean accept(File pathname) {
              return validname(pathname.getName());
            }
            };
            
            if (f.exists()) {
                File[] tmp = f.listFiles(filter);
                 
                Arrays.sort(tmp, LastModifiedFileComparator.LASTMODIFIED_COMPARATOR);
                return tmp;
//                int contador=0;
//                for(int i=0; i < tmp.length;i++){
////                  ficheros[contador] = tmp[i].getName();
//                  System.out.println("Name: " + tmp[i].getName());
//                  contador++;
//                }
//                ficheros = f.list();
            }
//            return ficheros;
        } catch (Exception e) {
            File[] ficheros = null;
            System.out.println("Error: " + e.getMessage());
            return ficheros;
        }
        return null;
    }

    public String listarArchivos() {
        return (String) AccessController.doPrivileged(new PrivilegedAction() {
            public String run() {
                File[] ficheros = getListDir();
                String json = "[";
                Integer nodos = ficheros.length;
                for (int index = 0; index < nodos; index++) {
                    if (isDirectorio(ficheros[index].getName()) == false) {
                        if (ficheros[index].getName().substring((ficheros[index].getName().length() - 4), ficheros[index].getName().length()).equals(".pdf")) {
                            if (ficheros[index].getName().substring(0, 3) == null ? "tmp" == null : ficheros[index].getName().substring(0, 3).equals("tmp")) {
                                json += "{\"archivo\":\"" + path + "\\\\" + ficheros[index].getName() + "\"},";
                            }
                        }
                    }
                }
                json = json.substring(0, json.length());
                json += "]";
                System.out.println(json);
                return json;
            }
        });
    }

    public void escanear() {
        AccessController.doPrivileged(new PrivilegedAction() {
            public Object run() {
                final Escaner escaner = new Escaner();
                try {
                    if (numeroimg == 0) {
                        ultimoNumero();
                    }
                    try {
                        escaner.prefix=getPrefix();
                        escaner.escanear(path, numeroimg, duplex, encolor);
                    } catch (TwainException ee) {
                        System.out.println(" Error: " + ee.getMessage());
                    }

                    new Thread(new Runnable() {
                        public void run() {
                            com(escaner);
                        }
                    }).start();

                } catch (Exception ex) {
//                Logger.getLogger(Digitalizacion.class.getName()).log(Level.SEVERE, null, ex);
                }
                return null;
            }
        });
    }

    public void browseDir() {
        AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                importadir=browseButton();
                return null;
            }
        });

    }

    public void Disminuir() {
        AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                cuadro.disminuir();
                return null;
            }
        });
    }

    public void Aumentar() {
        AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                cuadro.aumentar();
                return null;
            }
        });

    }

    @Override
    public void actionPerformed(ActionEvent e) {
       /* if (e.getSource() == btnConvertir)
        {
         System.out.println("btnConvertir");
         ConvertirTodoaPDF();
         //habilitar subir
        } else */
        if  (e.getSource()==chcColor)
        {    
            if (encolor==true) encolor=false;  
            else encolor=true;      
            
             //this.myInfoPanel.SettextArea("Color="+encolor);
        }       
        else if (e.getSource() == btnRefrescar)
        {
          System.out.println("btnRefrescar");
          this.mydir.reloadTree();
        } else if (e.getSource() == btnAumentar) {
           // this.myInfoPanel.SettextArea("zoom++");
            System.out.println("zoom++");
            cuadro.aumentar();
        } else if (e.getSource() == btnDisminuir) {
            System.out.println("zoom--");
            cuadro.disminuir();
        } else if (e.getSource() == cboZoom) {
            System.out.println("Zoom Seleccionado: " + cboZoom.getSelectedItem().toString());

        } else if (e.getSource() == btnEscanear) {
            botones_scanneando();
            //*****************************************************
            final Escaner escaner = new Escaner();
            try {
                if (numeroimg == 0) {
                    ultimoNumero();
                }
                try {
                    escaner.prefix=getPrefix();
                    escaner.escanear(path, numeroimg,duplex, encolor);
                    numeroimg=0;
                } catch (TwainException ee) {
                    System.out.println(" Error: " + ee.getMessage());
                    this.myInfoPanel.SettextArea(" Error: " + ee.getMessage());
                }

                new Thread(new Runnable() {
                    public void run() {
                        com(escaner);
                    }
                }).start();
                
                mydir.reloadTree();
                
        //***************************************************************************************
            } catch (Exception ex) {
//                Logger.getLogger(Digitalizacion.class.getName()).log(Level.SEVERE, null, ex);
            }
        } else if (e.getSource() == btnExaminar) {
            browseDir();
            if (this.importadir.length()>2)
            {    
            System.out.println("Importando dir="+ this.importadir);
            importarDesde(this.importadir);
            }
            botones_habilitar();
            
        } else if (e.getSource() == btnSubir) {
           
            botones_habilitar();
        //***********************************************************************    
            new Thread(new Runnable() {
                public void run() {
                    try {
                        
                        if (getURLSubmit().length()>3)
                        {  
                            System.out.println("URLupload="+getURL());
                            submit(getURLSubmit(),getURL());
                          //submit(getURLSubmit(), "http://10.22.165.204/sigejupev2/fachadas/sigejupe/imagenes/ImagenesFacade.Class.php");
                        }
                        else { JOptionPane.showMessageDialog(null, "Error: Ejecución fuera del JWS");}
                        //submit("idActuacion=9&cveTipoDocumento=12&accion=guardar","http://10.22.165.204/sigejupev2/fachadas/sigejupe/imagenes/ImagenesFacade.Class.php");
                     
                    } catch (IOException ex) {
                        Logger.getLogger(Digitalizacion.class.getName()).log(Level.SEVERE, null, ex);
                        myInfoPanel.SettextArea(" Error: Conexión al Internet.");
                        botones_habilitar();
                    }
                }
            }).start();
        //***************************************************************************    
            mydir.reloadTree();
            botones_habilitar();
            
        } else if (e.getSource() == btnRotar) {
//            new Thread(new Runnable() {
//                public void run() {
            System.out.println("Se procede a rotar la imagen");
            cuadro.rotar();
//                }
//            }).start();

        } else if (e.getSource()==chcDoble){
          if (duplex==true){
            duplex=false;  
          }else{
            duplex=true;    
          }
        }
    }

    public void ConvertirTodoaPDF()
    {
     System.out.println("Convertir");
     convertIrfan(path+"\\*.jpg /convert="+path+"\\*.pdf");
    }
    
    public void cargarJPG(final String archivo) throws IOException {
        AccessController.doPrivileged(new PrivilegedAction() {

            @Override
            public Object run() {
             float FACTOR = 0.991f;
             System.out.println("Archivo: " +archivo);
             BufferedImage bimg = null;

              try {
                  bimg = ImageIO.read(new File(archivo));
                  //***
                    int width = (int) (bimg.getWidth());
                    int height = (int) (bimg.getHeight());

                    BufferedImage img = new BufferedImage(width, height, BufferedImage.SCALE_REPLICATE);

                    AffineTransform at = AffineTransform.getScaleInstance(FACTOR, FACTOR);
                    Graphics2D g = img.createGraphics();
                    g.drawRenderedImage(bimg, at);
                  //***
                  imagen=img;
              } catch (IOException e) {
                        System.err.println("Error: " + e.getMessage());
                  }
                return null;
            }
        });
        cuadro.setImagen(imagen);
        cuadro.zom((float) -.7);
    }

    public void cargarPDF(final String archivo) throws IOException {
        AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                System.out.println("Archivo: " + archivo);
                archivoActual = archivo;
                try {
                    float FACTOR = 0.991f;
                    FileInputStream file = new FileInputStream(archivo);
                    Document documento = null;
                    PdfReader reader = null;
                    try {
                        reader = new PdfReader(file);
                    } catch (IOException ex) {
//                        Logger.getLogger(digitalizacion.class.getName()).log(Level.SEVERE, null, ex);
                    }
                    PdfObject object = null;
                    PRStream stream = null;

                    object = (PRStream) reader.getPdfObject(1);
                    stream = (PRStream) object;
                    PdfObject pdfsubtype = stream.get(PdfName.SUBTYPE);
                    System.out.println("Stream subType: " + pdfsubtype);
                    PdfImageObject image = null;
                    try {
                        image = new PdfImageObject(stream);
                        System.out.println(image.getFileType());
                    } catch (IOException ex) {
                        //
                    }
                    BufferedImage bimg = null;
                    try {
                        bimg = image.getBufferedImage();
                    } catch (IOException ex) {
                        //
                    }
                    int width = (int) (bimg.getWidth());
                    int height = (int) (bimg.getHeight());

                    BufferedImage img = new BufferedImage(width, height, BufferedImage.SCALE_REPLICATE);

                    AffineTransform at = AffineTransform.getScaleInstance(FACTOR, FACTOR);
                    Graphics2D g = img.createGraphics();
                    g.drawRenderedImage(bimg, at);
//                    ByteArrayOutputStream imgBytes = new ByteArrayOutputStream();
//                    try {
//                        imagenIcon.setIcon(new ImageIcon(img));
//                        imagenIcon.setSize(250, 350);
//
//                        ImageIO.write(img, "JPG", new File(path + "\\tmp.jpg"));
//                    } catch (IOException ex) {
//                        Logger.getLogger(digitalizacion.class.getName()).log(Level.SEVERE, null, ex);
//                    }
                    imagen = img;
                } catch (FileNotFoundException eFile) {
                    System.err.println("Error: " + eFile.getMessage());
                    
                }
                return null;
            }
        });
        cuadro.setImagen(imagen);
        cuadro.zom((float) -.6);
    }

    public void rotarImagen() {
        cuadro.setImagen(imagen);
    }

    public void convertIrfan(final String params) {
        AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                try {
                    System.out.println("*************************************************");
                    System.out.println("Se manda traer el programa de digitalizacion");
                    System.out.println("params: " + params);
                     
                    //Process p = Runtime.getRuntime().exec("C:\\Digital\\IrfanView\\i_view32.exe " + params);
             
                    String cmd ="C:\\borrame\\jpeg2pdf.exe " + params;  
                    
                    System.out.println (cmd);
                    Process p = Runtime.getRuntime().exec(cmd);
                    p.waitFor();
                    int exitVal = p.exitValue();
                   
                    System.out.println ("exit"+exitVal);
                    /*
                    BufferedReader reader = new BufferedReader(new InputStreamReader(p.getInputStream()));
                    while ((reader.readLine()) != null) {}
                    
                    p.waitFor();*/
                } catch (Exception e) {
                    System.err.println("Error: " + e);
                }
                return null;
            }
        });
    }

   public void convertJPEG(final String param1, final String param2) {
        AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                try {
                         
            //  cd c:\borra2 & c:\digital\jpeg2pdf.exe c:\borra2\tmp2.jpg -o c:\borra2\tmp2.pdf
            String cmd1, cmd2;
            cmd1=param1+"\\"+param2+".jpg";
            cmd2=param1+"\\"+param2+".pdf";
            
            ProcessBuilder   ps=new ProcessBuilder("c:\\digital\\jpeg2pdf.exe",cmd1,"-o",cmd2);
            ps.directory(new File(param1));
            //ProcessBuilder   ps=new ProcessBuilder("cmd.exe", "/C dir/w & echo ");
   
            ps.redirectErrorStream(true);

Process pr = ps.start();  

      try (BufferedReader in = new BufferedReader(new InputStreamReader(pr.getInputStream()))) {
          String line;
          while ((line = in.readLine()) != null) {
              System.out.println(line);
          }
          pr.waitFor();
          System.out.println("ok!"+ps.command());
      }
//***
                } catch (Exception e) {
                    System.err.println("Error: " + e);
                }
                return null;
            }
        });
    }
  
//******************************************************************************************************
       private static void copyFile(final File source, final File dest) throws IOException {
           
	AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                try {
                  
				 InputStream is = null;
                                 OutputStream os = null;
				 
              try {
                  is = new FileInputStream(source);
                  os = new FileOutputStream(dest);
                  byte[] buffer = new byte[1024];
                  int length;
                 while ((length = is.read(buffer)) > 0) {
                      os.write(buffer, 0, length);
                    }
                  } finally {
                    is.close();
                    os.close();
                            }
				  
                } catch (Exception e) {
                    System.err.println("Error: " + e);
                }
                return null;
            }
        });
	}	
    //******************************************************************************************************
    public void importarDesde(String origen) 
     {
        int contador = 1;
        String cadena = "";
        
            File lista[] = getDirOrigen(origen);
            this.botones_deshabilitar();
            progreso.setVisible(true);
            progreso.setMaximum((int) lista.length);          
            progreso.setStringPainted(true);
            progreso.setString("Importando...");
            progreso.repaint();
            
            for (int j = 0; j < lista.length; j++) {
                if (lista[j].getName().substring((lista[j].getName().length() - 4), lista[j].getName().length()).equals(".jpg")) 
                {
                    cadena = origen + "\\" +FilenameUtils.getBaseName(lista[j].getName())+".jpg";
				
                    System.out.println("Archivo: " + cadena);
                    
                    if (isJPEG(cadena))
                    {
                        try {
                            
                            contador += 1;
                            progreso.setValue(j);
                                 
                            System.out.println("Archivo: " + cadena);
                            
                            System.out.println("convertir archivo");
                            //convertIrfan(origen+"\\"+lista[j].getName()+" /convert="+origen+"\\"+FilenameUtils.getBaseName(lista[j].getName())+".pdf");
                            convertJPEG(origen+"\\",FilenameUtils.getBaseName(lista[j].getName()));
                            //convertJPEG(FilenameUtils.getBaseName(lista[j].getName()));
                            //convertIrfan(origen+"\\"+lista[j].getName()+" -o "+origen+"\\"+FilenameUtils.getBaseName(lista[j].getName())+".pdf");
                           
                            System.out.println("copiar archivos");
                            ultimoNumero();
                            copyFile(new File(origen+"\\"+FilenameUtils.getBaseName(lista[j].getName())+".jpg"), new File(path+"\\"+this.prefix+(this.numeroimg+1)+".jpg"));
                            copyFile(new File(origen+"\\"+FilenameUtils.getBaseName(lista[j].getName())+".pdf"), new File(path+"\\"+this.prefix+(this.numeroimg+1)+".pdf"));
                            
                        } catch (IOException ex) {
                            Logger.getLogger(Digitalizacion.class.getName()).log(Level.SEVERE, null, ex);
                        }
                    }
                  }  
                }
          
       //progreso.setVisible(false);
        botones_habilitar();
        this.mydir.reloadTree();

        if (contador > 1) {
            JOptionPane.showMessageDialog(this, "Archivos importados de forma correcta");
            progreso.setString("");
            progreso.setValue(0);
            progreso.setEnabled(false);
        }
    }

    //******************************************************************************************************
    
    
    
    public void com(Escaner escaner) {
        int contador = 1;
        try {
            File lista[] = getListDir();
            this.botones_deshabilitar();
            progreso.setMaximum((int) lista.length);
            progreso.setVisible(true);
            progreso.setStringPainted(true);
            
            for (int j = ultimoConvertido(); j < lista.length; j++) {
                if (lista[j].getName().substring((lista[j].getName().length() - 4), lista[j].getName().length()).equals(".jpg")) {
                    String cadena = "";
                    cadena = path + "\\" + lista[j].getName().substring(0, (lista[j].getName().length() - 4));
                    System.out.println("Archivo: " + cadena);
                    System.out.println(path+"\\"+lista[j].getName()+" -o "+path+"\\"+FilenameUtils.getBaseName(lista[j].getName())+".pdf");
                    //convertIrfan(path+"\\"+lista[j].getName()+" -o "+path+"\\"+FilenameUtils.getBaseName(lista[j].getName())+".pdf");
                    convertJPEG(path+"\\",FilenameUtils.getBaseName(lista[j].getName()));
                    
                    contador += 1;
                    progreso.setValue(contador);
                    progreso.setString("JPG->PDF...");
                    progreso.repaint();
                    repaint();
                }
            }
        } catch (Exception e) {
            System.err.println("Error al generar archivos pdf: " + e.getMessage());
        }
        progreso.setVisible(false);
        botones_habilitar();
        this.mydir.reloadTree();
//        File lista[] = getListDir();
        if (contador > 1) {
            JOptionPane.showMessageDialog(this, "Archivos generados de forma correcta");
            progreso.setString("");
            progreso.setValue(0);
            progreso.setEnabled(false);
           // try {
           //     getAppletContext().showDocument(new URL("javascript:listarPdf()"));
           // } catch (MalformedURLException me) {
           //     System.err.println("Error: " + me.getMessage());
           // }
        }
    }

    public void submit(String parameter, String url) throws IOException {
        //CloseableHttpClient httpclient = HttpClients.createDefault();
        CloseableHttpResponse response = null;
//*********************************************************************************************
    int timeout = 10;
    RequestConfig config = RequestConfig.custom()
       .setConnectTimeout(timeout * 1000)
       .setConnectionRequestTimeout(timeout * 1000)
       .setSocketTimeout(timeout * 1000).build();
    
    CloseableHttpClient httpclient = HttpClientBuilder.create().setDefaultRequestConfig(config).build();
//*********************************************************************************************        
        String msg="";  
    
        File dir = new File(path);
        FileFilter fileFilter = new WildcardFileFilter("*.pdf");
        File[] archivos = dir.listFiles(fileFilter);

        boolean error = false;

        if (archivos.length > 0) {
            botones_deshabilitar();
            progreso.setVisible(true);
            progreso.setMaximum(archivos.length);
            progreso.setString(null);
            progreso.setStringPainted(true);
            String mensaje = "";
            for (int i = 0; i < archivos.length; i++) {
                HttpPost httppost = new HttpPost(url);
                FileBody bin = new FileBody(archivos[i]);
                MultipartEntityBuilder param = MultipartEntityBuilder.create();
                for (int index = 0; index < parameter.split("&").length; index++) {
                    param.addTextBody(parameter.split("&")[index].split("=")[0], parameter.split("&")[index].split("=")[1]);
                    System.out.println("Params: " + parameter.split("&")[index].split("=")[0] + " valor: " + parameter.split("&")[index].split("=")[1]);
                }
                HttpEntity reqEntity = param.addPart("archivo", bin).build();

                httppost.setEntity(reqEntity);
                System.out.println("Request " + httppost.getRequestLine());
                         
                response = httpclient.execute(httppost);
               
                System.out.println("************************Archivo " + i + " Nombre : " + archivos[i].getName() + "*********************");
                msg=" Archivo: " + archivos[i].getName();
                System.out.println(response.getStatusLine());
                
                if (response.getStatusLine().getStatusCode() == 200) this.myInfoPanel.SettextArea(msg+" OK");
                   
                HttpEntity resEntity = response.getEntity();

                if (response.getStatusLine().getStatusCode() == 404) {
                    System.err.println("No se encontro la ruta especificada para subir los archivos");
                    this.myInfoPanel.SettextArea("No se encontró la ruta especificada para subir los archivos");
                    mensaje += "No se encontro la ruta especificada para subir los archivos\n";
                    error = true;
                } else if (response.getStatusLine().getStatusCode() == 500) {
                    System.err.println("La ruta de destino tiene un error de codigo");
                    this.myInfoPanel.SettextArea("La ruta de destino tiene un error de código");
                    mensaje += "La ruta de destino tiene un error de codigo\n";
                    error = true;
                } else if (response.getStatusLine().getStatusCode() == 503) {
                    System.err.println("El servidor no se encuentra disponible");
                    this.myInfoPanel.SettextArea("El servidor no se encuentra disponible");
                    mensaje += "El servidor no se encuenthis.myInfoPanel.SettextArea(\"El servidor no se encuentra disponible\");tra disponible\n";
                    error = true;
                } else if (response.getStatusLine().getStatusCode() == 502) {
                    System.err.println("Error de proxy");
                    this.myInfoPanel.SettextArea("Error de proxy");
                    mensaje += "Error de proxy\n";
                    error = true;
                }

                String bodyAsString = EntityUtils.toString(response.getEntity());
                if (bodyAsString != null && bodyAsString.indexOf("\"type\":\"Error\"") > 0) {
                    System.out.println("Response: " + bodyAsString);
                    this.myInfoPanel.SettextArea("Response: " + bodyAsString);
                    System.err.println("Error: No se logro copiar el archivo en el servidor");
                    this.myInfoPanel.SettextArea("Error: No se logro copiar el archivo en el servidor");
                    mensaje += "Error: No se logro copiar el archivo en el servidor\n";
                    error = true;
                } else {
                    System.out.println("Response: " + bodyAsString);
                    if ((bodyAsString != null && bodyAsString.indexOf("\"type\":\"OK\"") > 0)) {
                        if (eliminar_mute(archivos[i].getName())) {
                            System.out.println("El Archivo " + archivos[i].getName() + " Borrado de forma correcta");
                        } else {
                            System.out.println("El Archivo " + archivos[i].getName() + " No se logro Borrar de forma correcta");
                            this.myInfoPanel.SettextArea ("El Archivo " + archivos[i].getName() + " No se logro Borrar de forma correcta");
                            error = true;
                        }
                    } else {
                        error = true;
                        System.out.println("El Archivo " + archivos[i].getName() + " No se logro Subir al servidor de forma correcta");
                        mensaje += "El Archivo " + archivos[i].getName() + " No se logro Subir al servidor de forma correcta\n";
                        this.myInfoPanel.SettextArea("El Archivo " + archivos[i].getName() + " No se logro Subir al servidor de forma correcta");
                    }
                }
                
                progreso.setValue(i);
                try {
                    Thread.sleep(200);
                } catch (InterruptedException ex) {
                    //
                }
            }
            this.mydir.reloadTree();
            progreso.setString("");
            progreso.setValue(0);
            progreso.setVisible(false);
            //this.myInfoPanel.SettextArea("\n\n");
            botones_habilitar();

            if (!error) {
                //try {
                //    getAppletContext().showDocument(new URL("javascript:alert(\"Se subieron los archivos de forma correcta\")"));
                //    getAppletContext().showDocument(new URL("javascript:listarPdf()"));
                //} catch (MalformedURLException me) {
                //    System.err.println("Error: " + me.getMessage());
                //}
            } else {
                //try {
                //    getAppletContext().showDocument(new URL("javascript:alert(\"Algunos archivos no se lograron subir al servidor\")"));
                //    getAppletContext().showDocument(new URL("javascript:listarPdf()"));
                //} catch (MalformedURLException me) {
                //    System.err.println("Error: " + me.getMessage());
                //}
            }
        } else {
            JOptionPane.showMessageDialog(null, "El directorio " + path + " No contiene archivos");
            this.myInfoPanel.SettextArea("El directorio " + path + " No contiene archivos");
        }

    }

        public boolean eliminar_mute(String arhivos) {
        boolean noerror = true;
        for (int i = 0; i < arhivos.split(",").length; i++) {
            System.out.println("El archivo a eliminar: " + arhivos.split(",")[i]);
            
            String filename1 = path+"\\"+FilenameUtils.getBaseName(arhivos.split(",")[i])+".pdf";
            String filename2 = path+"\\"+FilenameUtils.getBaseName(arhivos.split(",")[i])+".jpg";
            
            File archivo = new File(filename1);
            File archivo2 = new File(filename2);
            
            if (archivo.delete()) {
                System.out.println("El archivo " + filename1 + " fue eliminado de forma correta");
            } else {
                System.out.println("El archivo " + filename1 + " no fue eliminado");
                this.myInfoPanel.SettextArea("El archivo " +filename1 + " no fue eliminado");
                noerror = false;
            }
            
            if (archivo2.delete()) {
                System.out.println("El archivo " + filename2 + " fue eliminado de forma correta");
            } else {
                System.out.println("El archivo " + filename2  + " no fue eliminado");
                this.myInfoPanel.SettextArea("El archivo " + filename2  + " no fue eliminado");
                noerror = false;
            }           
        }

         this.mydir.reloadTree();
         return noerror;
    }
    
    public boolean eliminar(String arhivos) {
        boolean error = false;
        for (int i = 0; i < arhivos.split(",").length; i++) {
            System.out.println("El archivo a eliminar: " + arhivos.split(",")[i]);
            
            String filename1 = path+"\\"+FilenameUtils.getBaseName(arhivos.split(",")[i])+".pdf";
            String filename2 = path+"\\"+FilenameUtils.getBaseName(arhivos.split(",")[i])+".jpg";
            
            File archivo = new File(filename1);
            File archivo2 = new File(filename2);
            
            if (archivo.delete()) {
                System.out.println("El archivo " + filename1 + " fue eliminado de forma correta");
            } else {
                System.out.println("El archivo " + filename1 + " no fue eliminado");
                this.myInfoPanel.SettextArea("El archivo " +filename1 + " no fue eliminado");
                error = true;
            }
            
            if (archivo2.delete()) {
                System.out.println("El archivo " + filename2 + " fue eliminado de forma correta");
            } else {
                System.out.println("El archivo " + filename2  + " no fue eliminado");
                this.myInfoPanel.SettextArea("El archivo " + filename2  + " no fue eliminado");
                error = true;
            }           
        }

        if (!error) {
            JOptionPane.showMessageDialog(null, "Los archivos se eliminaron de forma correcta");
            //try {
            //    getAppletContext().showDocument(new URL("javascript:listarPdf()"));
            //} catch (MalformedURLException me) {
            //    System.err.println("Error: " + me.getMessage());
            //}
        } else {
            JOptionPane.showMessageDialog(null, "Ocurrio un error al eliminar algunos de los archivos");
            //try {
            //    getAppletContext().showDocument(new URL("javascript:listarPdf()"));
            //} catch (MalformedURLException me) {
            //    System.err.println("Error: " + me.getMessage());
            //}
        }
         //JOptionPane.showMessageDialog(null, "Actualizando directorio...");
         this.mydir.reloadTree();
         return error;
    }

    public void enviar(final String params, final String url) {
        AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                try {
                    submit(params, url);
                } catch (IOException ex) {
                    System.err.println("Error: " + ex.getMessage());
                    myInfoPanel.SettextArea("Error: " + ex.getMessage());
                    botones_habilitar();
                }
                return null;
            }
        });

    }

    public void borrar(final String archivos) {
        AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                eliminar(archivos);
                return null;
            }
        });

    }
    
  
    public void setFilePan (FileDirectory estedir)
    {
    this.mydir=estedir;
    }  

    public void setInfoPanel (InfoPanel esteinfopanel)
    {
    this.myInfoPanel=esteinfopanel;
    }      
   
    
   public boolean isJPEG(String fileName)  {
       
       boolean canRead = false;
        try {
            ImageInputStream iis = ImageIO.createImageInputStream(new File(fileName ));
            Iterator<ImageReader> readers = ImageIO.getImageReadersByFormatName("jpg");
             
            while (readers.hasNext()) {
                try {
                    ImageReader reader = readers.next();
                    reader.setInput(iis);
                    reader.read(0);
                    canRead = true;
                    break;
                } catch (IOException exp) {
                }
            }
            
          
        } catch (IOException ex) {
            Logger.getLogger(Digitalizacion.class.getName()).log(Level.SEVERE, null, ex);
            return canRead;
        }
       return canRead;
}
    
     public static void main(String[] args) {
 
            JFrame frame = new JFrame("APLICACION DIGITALIZACION SIGEJUPEV2");
            final Digitalizacion scanpanel = new Digitalizacion();
            final FileDirectory filepanel= new FileDirectory("C:\\borrame");
            filepanel.setScanner(scanpanel);
            scanpanel.setFilePan(filepanel);
            
            if (args.length>0) {
                
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
                scanpanel.setIdCarpetaJudicial(args[0].substring(args[0].lastIndexOf("=") + 1));
                scanpanel.setIdActuacion(args[1].substring(args[1].lastIndexOf("=") + 1));
                scanpanel.setDescripcion(args[2].substring(args[2].lastIndexOf("=") + 1));
                scanpanel.setDescJuzgado(args[3].substring(args[3].lastIndexOf("=") + 1));
                scanpanel.setNumero(args[4].substring(args[4].lastIndexOf("=") + 1));
                scanpanel.setAnio(args[5].substring(args[5].lastIndexOf("=") + 1)); 
                scanpanel.setcveDocumento(args[6].substring(args[6].lastIndexOf("=") + 1));
                scanpanel.setIdUsuario(args[7].substring(args[7].lastIndexOf("=") + 1));
                scanpanel.setURL(args[8].substring(args[8].lastIndexOf("=") + 1));
                              
                scanpanel.setPrefix();
                System.out.println("prefijo="+scanpanel.getPrefix());
                
                final InfoPanel infopanel= new InfoPanel(scanpanel.getDescripcion(),
                        scanpanel.getDescJuzgado(),
                        scanpanel.getAnio(),
                        scanpanel.getNumero());
                
                infopanel.SettextArea("params="+scanpanel.getURLSubmit());
                scanpanel.setInfoPanel(infopanel);
                Container cp = frame.getContentPane();
                cp.setLayout(new BorderLayout());
                
                cp.add(infopanel,BorderLayout.NORTH);
                cp.add(filepanel,BorderLayout.WEST);
                cp.add(scanpanel,BorderLayout.CENTER);
                
                frame.pack();
                scanpanel.init();
                frame.setVisible(true);
                frame.setSize(800,730);
                frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
            }
            else {
                final InfoPanel infopanel= new InfoPanel("PROMOCION DEFAULT","JUZGADO DEFAULT","2016", "1");
                infopanel.SettextArea("INFO: arranque sin paramétros.");
                
                scanpanel.setcveDocumento("NOTIFICACION");
                scanpanel.setIdCarpetaJudicial("0");
                scanpanel.setIdActuacion("125");
                scanpanel.setDescripcion("NOTIFICACION TRADICIONAL");
                
                scanpanel.setPrefix();
                System.out.println("prefijo="+scanpanel.getPrefix());
                
                scanpanel.setAnio("");
                //URL prueba de Quetzal para la subida.
                scanpanel.setURL("http://10.22.157.48/sigejupe/fachadas/sigejupe/imagenes/ImagenesFacade.Class.php");
                //scanpanel.setURL("http://10.22.165.204/electronico/controller/imagenes/CargaImagenesController.Class.php");
                scanpanel.setInfoPanel(infopanel);
                infopanel.setPreferredSize(new Dimension(650, 148));
                
                Container cp = frame.getContentPane();
                cp.setLayout(new BorderLayout());
                
                cp.add(infopanel,BorderLayout.NORTH);
                cp.add(filepanel,BorderLayout.WEST);
                cp.add(scanpanel,BorderLayout.CENTER);
                
                
                
                frame.pack();
                scanpanel.init();
                frame.setVisible(true);
                frame.setSize(800,730);
                frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
            }
            
            //scanpanel.ultimoNumero();
            
            //System.out.println("1="+scanpanel.isJPEG("C:\\borrame\\EXP_3_0_0_1.jpg"));
            //System.out.println("2="+scanpanel.isJPEG("C:\\borrame\\EXP_3_0_0_5.jpg"));
            //System.out.println("3="+scanpanel.isJPEG("C:\\borrame\\EXP_3_0_0_4.jpg"));
            
            //scanpanel.importarDesde("C:\\borra");
            
            //scanpanel.convertJPEG("C:\\borrame","EXP_3_0_0_1");
            
            //scanpanel.convertJPEG("C:\\borra2","tmp2");
            System.out.println ("Ultimo="+scanpanel.ultimoConvertido());
            
            if (args.length>0){
                if (args[0]!=null) System.out.println ("0"+args[0]);
                if (args[1]!=null) System.out.println ("1"+args[1]);
                if (args[2]!=null) System.out.println ("2"+args[2]);
                if (args[3]!=null) System.out.println ("3"+args[3]);
                if (args[4]!=null) System.out.println ("4"+args[4]);
                if (args[5]!=null) System.out.println ("5"+args[5]);
                if (args[6]!=null) System.out.println ("6"+args[6]);
                if (args[7]!=null) System.out.println ("7"+args[7]);
                if (args[8]!=null) System.out.println ("8"+args[8]);
                
            }   
       
    } 
}
