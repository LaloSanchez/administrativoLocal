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
import java.awt.Graphics2D;
import java.awt.GridLayout;
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
import java.io.FileFilter;
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
    TextArea textArea=null;
    JProgressBar progreso = null;
    JLabel pAccion = null;
    JCheckBox chcDoble = null;

    JLabel lblDispositivos = null;

    String path = "C:\\borrame";
    private Vector vImages = null;
    private File files = null;

    private int numero = 0;
    public boolean duplex; 

//    private TwainSource sr[];
    BufferedImage imagen = null;
    private String archivoActual = "";
    private CloseableHttpResponse response;
     
    public FileDirectory mydir;
    public InfoPanel myInfoPanel;
    
    public String idCarpeta;
    public String idActuacion;
    public String Descripcion;
    public String descJuzgado;
    public String Numero;
    public String anio;
    public String cveDocumento;
    public String idUsuario;
    public String URL;

 boolean validname(String filename)
{
 int pos1, pos2;
 pos1=filename.indexOf(".");
 pos2=filename.lastIndexOf(".");
 
 if (pos1!=pos2) return false;
 if (filename.length()>12) return false;
 if ((filename.indexOf(".pdf")>-1)||(filename.indexOf(".jpg")>-1)) return true;
 else return false; 
 
}   
    public String getURLSubmit()
    {
    String urlprm;
   
    if (this.getAnio().length()>1)
    {    
    urlprm="";
    urlprm="idCarpetaJudicial="+this.getIdCarpeta()+"&"; 
    urlprm=urlprm+"idActuacion="+this.getIdActuacion()+"&";
    urlprm=urlprm+"Numero="+this.getNumero()+"&";
    urlprm=urlprm+"IdUsuario="+this.getIdUsuario()+"&";
    urlprm=urlprm+"Descripcion="+this.getDescripcion()+"&";
    urlprm=urlprm+"DescJuzgado="+this.getDescJuzgado()+"&";
    urlprm=urlprm+"anio="+this.getAnio()+"&";
    urlprm=urlprm+"cveTipoDocumento="+this.getCveDocumento()+"&";
    urlprm=urlprm+"accion=guardar";
    
    return urlprm;
    }
    else {
         return "";
         //return ("idCarpetaJudicial=0&idActuacion=125&Numero=1&IdUsuario=80&Descripcion=NOTIFICACION TRADICIONAL&DescJuzgado=JUZGADO DE CONTROL DE TOLUCA&anio=2016&cveTipoDocumento=22&accion=guardar");
         } 
    }        
   
    public String getIdCarpeta() {
        return idCarpeta;
    }

    public void setIdCarpeta(String idCarpeta) {
        this.idCarpeta = idCarpeta;
    }

    public String getIdActuacion() {
        return idActuacion;
    }

    public void setIdActuacion(String idActuacion) {
        this.idActuacion = idActuacion;
    }

    public String getDescripcion() {
        return Descripcion;
    }

    public void setDescripcion(String Descripcion) {
        this.Descripcion = Descripcion;
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

    public String getCveDocumento() {
        return cveDocumento;
    }

    public void setCveDocumento(String cveDocumento) {
        this.cveDocumento = cveDocumento;
    }

    public String getIdUsuario() {
        return idUsuario;
    }

    public void setIdUsuario(String idUsuario) {
        this.idUsuario = idUsuario;
    }
    
    public void botones_scanneando()
    {        
        btnRefrescar.setEnabled(false);
        btnAumentar.setEnabled(false);
        btnDisminuir.setEnabled(false);
        btnEscanear.setEnabled(true);
        btnSubir.setEnabled(false);
        chcDoble.setEnabled(false);
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
        progreso.setVisible(true);
    }
    
    public void init() {
        cuadro = new CuadroImagen();
        scroll = new JScrollPane(cuadro, JScrollPane.VERTICAL_SCROLLBAR_ALWAYS, JScrollPane.HORIZONTAL_SCROLLBAR_ALWAYS);
        getContentPane().add(scroll, BorderLayout.CENTER);

        pOpciones = new JPanel();
        btnRefrescar = new JButton("REFRESCAR");
        btnAumentar = new JButton("+");
        btnDisminuir = new JButton("-");
        btnRotar = new JButton("Rotar");
        btnEscanear = new JButton("Escanear");
        btnExaminar = new JButton("Examinar...");
        btnSubir = new JButton("Subir");
        
        lblDispositivos = new JLabel("Dispositivos");
        chcDoble = new JCheckBox("Doble cara",true);

        pOpciones.add(btnRefrescar);
        pOpciones.add(btnAumentar);
        pOpciones.add(btnDisminuir);
        pOpciones.add(btnEscanear);
        pOpciones.add(chcDoble);
        pOpciones.add(btnSubir);
        duplex = true;
        pAcciones = new JPanel();
        pAcciones.add(pOpciones, BorderLayout.CENTER);
        progreso = new JProgressBar();
        progreso.setSize(this.getWidth(), 200);
        progreso.setVisible(true);
        progreso.setEnabled(true);
        pAcciones.add(progreso, BorderLayout.SOUTH);

        getContentPane().add(pAcciones, BorderLayout.NORTH);
        
        btnRefrescar.addActionListener(this);
        btnAumentar.addActionListener(this);
        btnDisminuir.addActionListener(this);
        btnEscanear.addActionListener(this);
        btnExaminar.addActionListener(this);
        btnSubir.addActionListener(this);
        btnRotar.addActionListener(this);
        chcDoble.addActionListener(this);

        getContentPane().setSize(650, 650);
        if (createDir() == false) {
            JOptionPane.showMessageDialog(this, "El directorio " + path + " No existe ");
             this.myInfoPanel.SettextArea("El directorio " + path + " No existe .");
        }
    }

    private String browseButton() {
        try {
            JFileChooser browser = new JFileChooser();
            File f = new File(path);
            File ruta = null;
            browser.setDialogTitle("Guardar en....");
            browser.setCurrentDirectory(f);
            browser.setFileSelectionMode(JFileChooser.DIRECTORIES_ONLY);
            int seleccion = browser.showSaveDialog(null);
            if (seleccion == browser.APPROVE_OPTION) {
                ruta = browser.getSelectedFile();
            }
            if (seleccion == browser.ABORT || seleccion == browser.CANCEL_OPTION || seleccion == browser.ERROR_OPTION) {
                System.out.println("El usuario cancelo");
                this.myInfoPanel.SettextArea("El usuario canceló.");
                ruta = new File(path);
            }

            System.out.println("Path: " + ruta.toString());
            return ruta.toString();
        } catch (Exception e) {
            System.out.println("Error: al seleccionar la ruta" + e.toString());
            return "";
        }
    }

    private void ultimoNumero() {
        File[] ficheros = getListDir();
        Integer nodos = ficheros.length;
        for (int index = 0; index < nodos; index++) {
            if (isDirectorio(ficheros[index].getName()) == false) {
                if (ficheros[index].getName().substring((ficheros[index].getName().length() - 4), ficheros[index].getName().length()).equals(".pdf")) {
                    if (ficheros[index].getName().substring(0, 3) == null ? "tmp" == null : ficheros[index].getName().substring(0, 3).equals("tmp")) {
                        if (numero < Integer.parseInt(ficheros[index].getName().substring(3, (ficheros[index].getName().length() - 4)))) {
                            numero = Integer.parseInt(ficheros[index].getName().substring(3, (ficheros[index].getName().length() - 4)));
                            System.out.println("Numero: " + Integer.toString(numero));
                        }
                    }
                }
            }
        }
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
                    if (numero == 0) {
                        ultimoNumero();
                    }
                    try {
                        escaner.escanear(path, numero,duplex);
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

    public void brows() {
        AccessController.doPrivileged(new PrivilegedAction() {

            public Object run() {
                path = browseButton();
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
        
        if (e.getSource() == btnRefrescar)
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
                if (numero == 0) {
                    ultimoNumero();
                }
                try {
                    escaner.escanear(path, numero,duplex);
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
            path = browseButton();

        } else if (e.getSource() == btnSubir) {
           
            botones_subiendo();
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

    public void com(Escaner escaner) {
        int contador = 1;
        try {
            File lista[] = getListDir();
            this.botones_deshabilitar();
            progreso.setMaximum((int) lista.length);
            progreso.setVisible(true);
            progreso.setStringPainted(true);
            
            for (int j = 0; j < lista.length; j++) {
                if (lista[j].getName().substring((lista[j].getName().length() - 4), lista[j].getName().length()).equals(".jpg")) {
                    String cadena = "";
                    try {
                        cadena = path + "\\" + lista[j].getName().substring(0, (lista[j].getName().length() - 4));
                        System.out.println("Archivo: " + cadena);
                        BufferedImage bfImg;
                        bfImg = ImageIO.read(new File(cadena + ".jpg"));
                        /*Color colorAux = null;
                        int mediaPixel, colorSRGB;
                        for (int x = 0; x < bfImg.getWidth(); x++) {
                            for (int y = 0; y < bfImg.getHeight(); y++) {
                                colorAux = new Color(bfImg.getRGB(x, y));
                                mediaPixel = (int) ((colorAux.getRed() + colorAux.getGreen() + colorAux.getBlue()) / 3);
                                colorSRGB = (0 << 16) | (0 << 8) | 0;

                                if (mediaPixel == 255) {
                                    colorSRGB = (255 << 16) | (255 << 8) | 255;
                                    bfImg.setRGB(x, y, colorSRGB);
                                }
                                if (mediaPixel >= 87 && mediaPixel <= 215) {
                                    colorSRGB = (0 << 16) | (0 << 8) | 0;
                                    bfImg.setRGB(x, y, colorSRGB);
                                }
                            }
                        }*/
                        byte[] bytes = ImageTool.getBytesTIFF(bfImg, .8f);

                        double actualSize = (double) bytes.length / 1024;
                        System.out.println("File Size: " + String.format("%.2f", actualSize) + "kb");
                        System.out.println("Archivo: " + cadena + "");
                        System.out.println("Alto: " + bfImg.getHeight() + "");
                        System.out.println("Ancho: " + bfImg.getWidth() + "");
//
                        com.itextpdf.text.Image imgt = ImageTool.addTif(bytes, 200);
                        Document document = null;
                        if ((bfImg.getWidth() / 200) <= 8.5) {//Vertical
                            if ((bfImg.getHeight() / 200) <= 11) {//Carta
                                document = new Document(new Rectangle((float) (8.5 * 72), (float) (11 * 72)));
                                document.setMargins(0, 0, 0, 0);
                            } else {//Oficio
                                document = new Document(new Rectangle((float) (8.5 * 72), (float) (13.34 * 72)));
                                document.setMargins(0, 0, -2, 0);
                            }
                        } else {//Horizontal
                            if ((bfImg.getWidth() / 200) <= 11) {//Carta
                                document = new Document(new Rectangle((float) (11 * 72), (float) (8.5 * 72)));
                                document.setMargins(0, 0, 0, 0);
                            } else {//Oficio
                                document = new Document(new Rectangle((float) (13.34 * 72), (float) (8.5 * 72)));
                                document.setMargins(0, 0, -2, 0);
                            }
                        }
                        PdfWriter writer = PdfWriter.getInstance(document, new FileOutputStream(cadena + ".pdf"));
                        writer.setFullCompression();

                        document.open();

                        imgt.setCompressionLevel(0);
                        document.add(imgt);
                        document.close();
                        bfImg.flush();
                        File fichero = new File(cadena + ".jpg");
                        if (fichero.delete()) {
                            System.out.println("El fichero ha sido borrado satisfactoriamente");
                        } else {
                            System.out.println("El fichero no puede ser borrado");
                        }
                    } catch (IOException ee) {
                        System.err.println("Error: " + ee.getMessage());
                    }

                    if (contador == 1) {
                        cargarPDF(cadena + ".pdf");
                    }

                    contador += 1;
                    progreso.setValue(contador);
                    progreso.setString("Convirtiendo JPG->PDF...");
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
        File[] archivos = getListDir();
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
                        if (archivos[i].delete()) {
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

    public void eliminar(String arhivos) {
        boolean error = false;
        for (int i = 0; i < arhivos.split(",").length; i++) {
            File archivo = new File(arhivos.split(",")[i]);
            System.out.println("El archivo a eliminar: " + arhivos.split(",")[i]);
            if (archivo.delete()) {
                System.out.println("El archivo " + arhivos.split(",")[i] + " fue eliminado de forma correta");
            } else {
                System.out.println("El archivo " + arhivos.split(",")[i] + " no fue eliminado");
                this.myInfoPanel.SettextArea("El archivo " + arhivos.split(",")[i] + " no fue eliminado");
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
    
     public static void main(String[] args) {
    
    JFrame frame = new JFrame("Aplicación de Scaneo de Expedientes");
    final Digitalizacion scanpanel = new Digitalizacion();
    final FileDirectory filepanel= new FileDirectory("C:\\borrame");
    filepanel.setScanner(scanpanel);
    scanpanel.setFilePan(filepanel);
    
    if (args.length>0) {
        
        scanpanel.setIdCarpeta(args[0].substring(args[0].lastIndexOf("=") + 1));
        scanpanel.setIdActuacion(args[1].substring(args[1].lastIndexOf("=") + 1));
        scanpanel.setNumero(args[4].substring(args[4].lastIndexOf("=") + 1));
        scanpanel.setIdUsuario(args[7].substring(args[7].lastIndexOf("=") + 1));
        
        scanpanel.setDescripcion(args[2].substring(args[2].lastIndexOf("=") + 1));
        scanpanel.setDescJuzgado(args[3].substring(args[3].lastIndexOf("=") + 1));
        scanpanel.setAnio(args[5].substring(args[5].lastIndexOf("=") + 1));
        scanpanel.setCveDocumento(args[6].substring(args[6].lastIndexOf("=") + 1));
        scanpanel.setURL(args[8].substring(args[8].lastIndexOf("=") + 1));
        
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
        final InfoPanel infopanel= new InfoPanel("DESCRIPCION DEFAULT","JUZGADO DEFAULT","2016", "999");
        infopanel.SettextArea("INFO: arranque sin paramétros.");
        scanpanel.setAnio("");
         //URL prueba de Quetzal para la subida.
        //scanpanel.setURL("http://10.22.157.48/sigejupe/fachadas/sigejupe/imagenes/ImagenesFacade.Class.php");
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
 
    if (args.length>0){
    if (args[0]!=null) System.out.println ("1"+args[0]);       
    if (args[1]!=null) System.out.println ("2"+args[1]);
    if (args[2]!=null) System.out.println ("3"+args[2]); 
    if (args[3]!=null) System.out.println ("4"+args[3]); 
    if (args[4]!=null) System.out.println ("5"+args[4]); 
    if (args[5]!=null) System.out.println ("6"+args[5]); 
    if (args[6]!=null) System.out.println ("7"+args[6]);
    if (args[7]!=null) System.out.println ("8"+args[7]);
    if (args[8]!=null) System.out.println ("9"+args[8]);
  
    }
       
    }  
    
}
