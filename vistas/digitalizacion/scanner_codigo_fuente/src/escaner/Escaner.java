package escaner;

import SK.gnome.capabilities.Capability;
import SK.gnome.capabilities.twain.TwainActivity;
import SK.gnome.morena.MorenaConstants;
import SK.gnome.morena.MorenaImage;
import SK.gnome.morena.MorenaSource;
import SK.gnome.twain.TwainException;
import SK.gnome.twain.TwainManager;
import SK.gnome.twain.TwainSource;
import java.awt.Toolkit;
import java.awt.image.BufferedImage;
import java.awt.image.ImageConsumer;
import java.io.File;
import java.io.FileNotFoundException;
import java.util.Vector;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.imageio.ImageIO;
import javax.swing.JOptionPane;

public class Escaner implements MorenaConstants {

    private String path = "";
    public Vector vImages = null;
    public int index = 0;
    public int count = 0;

    private MorenaSource source = null;

    public Escaner() {
        System.out.println("Se creo el objeto para trabajar con el escaner");
    }

    public TwainSource[] listarDispositivos() {
        TwainSource sr[] = null;
        try {
            sr = TwainManager.listSources();
        } catch (TwainException ex) {
            Logger.getLogger(Escaner.class.getName()).log(Level.SEVERE, null, ex);
        }
        try {
            TwainManager.close();
        } catch (TwainException ex) {
            Logger.getLogger(Escaner.class.getName()).log(Level.SEVERE, null, ex);
        }
        return sr;
    }

    public void escanear(String p, int n,boolean duplex) throws TwainException, FileNotFoundException {
        try {
            path = p + "\\";
            index = n;

            System.out.println("Path: " + path);
            System.out.println("Numero: " + index);
            TwainSource source;
//          TwainActivity twainActivity = null;
//            try {
//                twainActivity = new TwainActivity(null,dispositivo );
//            } catch (Exception ex) {
//                Logger.getLogger(Escaner.class.getName()).log(Level.SEVERE, null, ex);
//            }
            source = TwainManager.selectSource(null, null);

            System.err.println("Selected source is " + source);
            if (source != null) {

                try {
                    source.setFeederEnabled(true);
                } catch (Exception error) {
                    System.err.println("Error: " + error.getMessage());
                }
                source.setAutoFeed(true);
                source.setAutoScan(true);
                source.setBitDepthReduction(1);
                if(duplex==true){
                 source.setDuplexEnabled(true);
                }
                source.setAutomaticBorderDetection(true);
                source.setUndefinedImageSize(true);
                source.setVisible(false);
                source.setResolution(200.0D);
                source.maskUnsupportedCapabilityException(false);
                source.maskBadValueException(false);
                source.setRotation(0);
                count = 0;
                try {
                    do {
                        MorenaImage imagen = new MorenaImage(source);

                        if (imagen.getStatus() != ImageConsumer.IMAGEERROR) {
                            java.awt.Image image = Toolkit.getDefaultToolkit().createImage(imagen);
                            try {
                                BufferedImage bimg = new BufferedImage(image.getWidth(null), image.getHeight(null), BufferedImage.TYPE_INT_RGB);
                                bimg.createGraphics().drawImage(image, 0, 0, null);

                                this.index = this.index + 1;
                                String ruta = path + "tmp" + index;
                                count++;
                                if (this.count == 1) {
                                    vImages = new Vector();
                                    vImages.add(ruta);
                                    System.out.println("Adjuntando imagen " + count);
                                } else {
                                    vImages.addElement(ruta);
                                    System.out.println("Adjuntando imagen " + count);
                                }

                                System.out.println(ruta);

                                System.err.println("Size of acquired image is "
                                        + imagen.getWidth() + " x "
                                        + imagen.getHeight() + " x "
                                        + imagen.getPixelSize());
                                count++;
                                try {

                                    ImageIO.write(bimg, "jpg", new File(ruta + ".jpg"));
                                    bimg.flush();
                                } catch (Exception e) {
                                    System.out.println("Error al guardar al archivo: " + e.getMessage());
                                }
                            } catch (Exception ee) {
                                System.out.println("Error: " + ee.getMessage());
                            }
                        } else {
                            TwainActivity twainActivity = null;
                            try {
                                twainActivity = new TwainActivity(null, source.toString());
                                Capability capability = twainActivity.getCapability("FeederEnabled");
                                String cabezal = String.valueOf(capability.getValue());
                                System.out.println(cabezal);
                                if (cabezal.equals("true")) {
                                    source.setFeederEnabled(false);
                                }
                            } catch (Exception ex) {
                                //
                            }
                        }
                    } while (source.hasMoreImages());
                    if (count == 0) {
                        do {
                            MorenaImage imagen = new MorenaImage(source);

                            if (imagen.getStatus() != ImageConsumer.IMAGEERROR) {
                                java.awt.Image image = Toolkit.getDefaultToolkit().createImage(imagen);
                                try {
                                    BufferedImage bimg = new BufferedImage(image.getWidth(null), image.getHeight(null), BufferedImage.TYPE_INT_RGB);
                                    bimg.createGraphics().drawImage(image, 0, 0, null);

                                    this.index = this.index + 1;
                                    String ruta = path + "tmp" + index;
                                    count++;
                                    if (this.count == 1) {
                                        vImages = new Vector();
                                        vImages.add(ruta);
                                        System.out.println("Adjuntando imagen " + count);
                                    } else {
                                        vImages.addElement(ruta);
                                        System.out.println("Adjuntando imagen " + count);
                                    }

                                    System.out.println(ruta);

                                    System.err.println("Size of acquired image is "
                                            + imagen.getWidth() + " x "
                                            + imagen.getHeight() + " x "
                                            + imagen.getPixelSize());
                                    count++;
                                    try {

                                        ImageIO.write(bimg, "jpg", new File(ruta + ".jpg"));
                                        bimg.flush();
                                    } catch (Exception e) {
                                        System.out.println("Error al guardar al archivo: " + e.getMessage());
                                    }
                                } catch (Exception ee) {
                                    System.out.println("Error: " + ee.getMessage());
                                }
                            } else {
                                TwainActivity twainActivity = null;
                                try {
                                    twainActivity = new TwainActivity(null, source.toString());
                                    Capability capability = twainActivity.getCapability("FeederEnabled");
                                    String cabezal = String.valueOf(capability.getValue());
                                    System.out.println(cabezal);
                                    if (cabezal.equals("true")) {
                                        source.setFeederEnabled(false);
                                    }
                                } catch (Exception ex) {
                                    //
                                }
                            }
                        } while (source.hasMoreImages());
                    }

                } catch (TwainException ee) {
                    System.out.println("Error: No se logro accesar a las imagenes" + ee.getMessage());
                }
            }
            TwainManager.close();
            Object[] options = {"Si",
                "No"};
            int opcion = JOptionPane.showOptionDialog(null,
                    "Se termino de escanear ¿Desea seguir escaneando?",
                    "Advertencia",
                    JOptionPane.YES_NO_OPTION,
                    JOptionPane.QUESTION_MESSAGE,
                    null,
                    options,
                    options[1]);
            System.out.println("Opcion: " + n);
            if (opcion == 0) {
                this.escanear(p, index,duplex);
            }
        } catch (TwainException ee) {
            System.err.println("Error: No se logro escanear");
            Object[] options = {"Si",
                "No"};
            int opcion = JOptionPane.showOptionDialog(null,
                    "No se logro accesar al escaner ¿Desea intentar de nuevo?",
                    "Advertencia",
                    JOptionPane.YES_NO_OPTION,
                    JOptionPane.QUESTION_MESSAGE,
                    null,
                    options,
                    options[1]);
            System.out.println("Opcion: " + n);
        }
    }
}
