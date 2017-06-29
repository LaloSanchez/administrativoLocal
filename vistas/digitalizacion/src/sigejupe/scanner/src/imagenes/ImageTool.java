package imagenes;

import java.awt.Color;
import java.awt.Graphics2D;
import java.awt.Image;
import java.awt.RenderingHints;
import java.awt.image.BufferedImage;
import java.io.BufferedInputStream;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;

import java.io.IOException;
import java.util.Iterator;

import javax.imageio.IIOImage;
import javax.imageio.ImageIO;
import javax.imageio.ImageTypeSpecifier;
import javax.imageio.ImageWriteParam;
import javax.imageio.ImageWriter;
import javax.imageio.stream.ImageOutputStream;

import com.itextpdf.text.Document;
import com.itextpdf.text.DocumentException;
import com.itextpdf.text.Rectangle;
import com.itextpdf.text.io.RandomAccessSourceFactory;
import com.itextpdf.text.pdf.PdfCopy;
import com.itextpdf.text.pdf.RandomAccessFileOrArray;
import com.itextpdf.text.pdf.codec.TiffImage;
import com.sun.media.imageio.plugins.tiff.TIFFImageWriteParam;

/**
 * This class can be used to convert images. Note that all the methods of this
 * class are declared as static. Supports the following image operations
 * <ul>
 * <li>Convert between Image and BufferedImage</li>
 * <li>Split images</li>
 * <li>Resize image</li>
 * <li>Create tiled image</li>
 * <li>Create empty transparent image</li>
 * <li>Create a colored image</li>
 * <li>Flip image horizontally</li>
 * <li>Flip image vertically</li>
 * <li>Clone image</li>
 * <li>Rotate image</li>
 * </ul>
 * 
 * @author Sri Harsha Chilakapati
 */
public abstract class ImageTool {

	public static final int CARTA_WIDTH = 615;
	public static final int CARTA_HEIGHT = 800;
	public static final int OFICIO_WIDTH = 615;
	public static final int OFICIO_HEIGHT = 1000;

	
    private ImageTool() {
    }

    /**
     * Converts a given Image into a BufferedImage
     * 
     * @param img The Image to be converted
     * @return The converted BufferedImage
     */
    public static BufferedImage toBufferedImage(Image img){
        if (img instanceof BufferedImage) {
            return (BufferedImage) img;
        }
        // Create a buffered image with transparency
        BufferedImage bimage = new BufferedImage(img.getWidth(null), img.getHeight(null), BufferedImage.TYPE_INT_ARGB);
        // Draw the image on to the buffered image
        Graphics2D bGr = bimage.createGraphics();
        bGr.drawImage(img, 0, 0, null);
        bGr.dispose();
        // Return the buffered image
        return bimage;
    }

    /**
     * Splits an image into a number of rows and columns
     * 
     * @param img The image to be split
     * @param rows The number of rows
     * @param cols The number of columns
     * @return The array of split images in the vertical order
     */
    public static BufferedImage[] splitImage(Image img, int rows, int cols){
        // Determine the width of each part
        int w = img.getWidth(null) / cols;
        // Determine the height of each part
        int h = img.getHeight(null) / rows;
        // Determine the number of BufferedImages to be created
        int num = rows * cols;
        // The count of images we'll use in looping
        int count = 0;
        // Create the BufferedImage array
        BufferedImage[] imgs = new BufferedImage[num];
        // Start looping and creating images [splitting]
        for (int x = 0; x < rows; x++) {
            for (int y = 0; y < cols; y++) {
                // The BITMASK type allows us to use bmp images with coloured
                // text and any background
                imgs[count] = new BufferedImage(w, h, BufferedImage.BITMASK);
                // Get the Graphics2D object of the split part of the image
                Graphics2D g = imgs[count++].createGraphics();
                // Draw only the required portion of the main image on to the
                // split image
                g.drawImage(img, 0, 0, w, h, w * y, h * x, w * y + w, h * x + h, null);
                // Now Dispose the Graphics2D class
                g.dispose();
            }
        }
        return imgs;
    }

    /**
     * Converts a given BufferedImage into an Image
     * 
     * @param bimage The BufferedImage to be converted
     * @return The converted Image
     */
    public static Image toImage(BufferedImage bimage){
        // Casting is enough to convert from BufferedImage to Image
        Image img = (Image) bimage;
        return img;
    }

    /**
     * Resizes a given image to given width and height
     * 
     * @param img The image to be resized
     * @param width The new width
     * @param height The new height
     * @return The resized image
     */
    public static Image resize(Image img, int width, int height){
        // Create a null image
        Image image = null;
        // Resize into a BufferedImage
        BufferedImage bimg = new BufferedImage(width, height, BufferedImage.TYPE_INT_ARGB);
        Graphics2D bGr = bimg.createGraphics();
        bGr.drawImage(img, 0, 0, width, height, null);
        bGr.dispose();
        // Convert to Image and return it
        image = toImage(bimg);
        return image;
    }

    /**
     * Creates a tiled image with an image upto given width and height
     * 
     * @param img The source image
     * @param width The width of image to be created
     * @param height The height of the image to be created
     * @return The created image
     */
    public static Image createTiledImage(Image img, int width, int height){
        // Create a null image
        Image image = null;
        BufferedImage bimg = new BufferedImage(width, height, BufferedImage.TYPE_INT_ARGB);
        // The width and height of the given image
        int imageWidth = img.getWidth(null);
        int imageHeight = img.getHeight(null);
        // Start the counting
        int numX = (width / imageWidth) + 2;
        int numY = (height / imageHeight) + 2;
        // Create the graphics context
        Graphics2D bGr = bimg.createGraphics();
        for (int y = 0; y < numY; y++) {
            for (int x = 0; x < numX; x++) {
                bGr.drawImage(img, x * imageWidth, y * imageHeight, null);
            }
        }
        // Convert and return the image
        image = toImage(bimg);
        return image;
    }

    /**
     * Creates an empty image with transparency
     * 
     * @param width The width of required image
     * @param height The height of required image
     * @return The created image
     */
    public static Image getEmptyImage(int width, int height){
        BufferedImage img = new BufferedImage(width, height, BufferedImage.TYPE_INT_ARGB);
        return toImage(img);
    }

    /**
     * Creates a colored image with a specified color
     * 
     * @param color The color to be filled with
     * @param width The width of the required image
     * @param height The height of the required image
     * @return The created image
     */
    public static Image getColoredImage(Color color, int width, int height){
        BufferedImage img = toBufferedImage(getEmptyImage(width, height));
        Graphics2D g = img.createGraphics();
        g.setColor(color);
        g.fillRect(0, 0, width, height);
        g.dispose();
        return img;
    }

    /**
     * Flips an image horizontally. (Mirrors it)
     * 
     * @param img The source image
     * @return The image after flip
     */
    public static Image flipImageHorizontally(Image img){
        int w = img.getWidth(null);
        int h = img.getHeight(null);
        BufferedImage bimg = toBufferedImage(getEmptyImage(w, h));
        Graphics2D g = bimg.createGraphics();
        g.drawImage(img, 0, 0, w, h, w, 0, 0, h, null);
        g.dispose();
        return toImage(bimg);
    }

    /**
     * Flips an image vertically. (Mirrors it)
     * 
     * @param img The source image
     * @return The image after flip
     */
    public static Image flipImageVertically(Image img){
        int w = img.getWidth(null);
        int h = img.getHeight(null);
        BufferedImage bimg = toBufferedImage(getEmptyImage(w, h));
        Graphics2D g = bimg.createGraphics();
        g.drawImage(img, 0, 0, w, h, 0, h, w, 0, null);
        g.dispose();
        return toImage(bimg);
    }

    /**
     * Clones an image. After cloning, a copy of the image is returned.
     * 
     * @param img The image to be cloned
     * @return The clone of the given image
     */
    public static Image clone(Image img){
        BufferedImage bimg = toBufferedImage(getEmptyImage(img.getWidth(null), img.getHeight(null)));
        Graphics2D g = bimg.createGraphics();
        g.drawImage(img, 0, 0, null);
        g.dispose();
        return toImage(bimg);
    }

    /**
     * Rotates an image. Actually rotates a new copy of the image.
     * 
     * @param img The image to be rotated
     * @param angle The angle in degrees
     * @return The rotated image
     */
    public static Image rotate(Image img, double angle){

        double sin = Math.abs(Math.sin(Math.toRadians(angle))), cos = Math.abs(Math.cos(Math.toRadians(angle)));
        int w = img.getWidth(null), h = img.getHeight(null);
        int neww = (int) Math.floor(w * cos + h * sin), newh = (int) Math.floor(h
                * cos + w * sin);
        BufferedImage bimg = toBufferedImage(getEmptyImage(neww, newh));
        Graphics2D g = bimg.createGraphics();
        g.setRenderingHint(RenderingHints.KEY_INTERPOLATION, RenderingHints.VALUE_INTERPOLATION_BILINEAR);
        g.translate((neww - w) / 2, (newh - h) / 2);
        g.rotate(Math.toRadians(angle), w / 2, h / 2);
        g.drawRenderedImage(toBufferedImage(img), null);
        g.dispose();
        return toImage(bimg);
    }
    
    /**
     * Makes a color in an Image transparent.
     */
    public static Image mask(Image img, Color color){
        BufferedImage bimg = toBufferedImage(getEmptyImage(img.getWidth(null), img.getHeight(null)));
        Graphics2D g = bimg.createGraphics();
        g.drawImage(img, 0, 0, null);
        g.dispose();
        for (int y=0; y<bimg.getHeight(); y++){
            for (int x=0; x<bimg.getWidth(); x++){
                int col = bimg.getRGB(x, y);
                if (col==color.getRGB()){
                    bimg.setRGB(x, y, col & 0x00ffffff);
                }
            }
        }
        return toImage(bimg);
    }
	
	public static byte[] getBytes(Image img) throws IOException
	{
//		 	img = img.getScaledInstance(600, -1, Image.SCALE_SMOOTH);
			BufferedImage bim = new BufferedImage(img.getWidth(null), img.getHeight(null), BufferedImage.TYPE_INT_RGB);
			Graphics2D bGr = bim.createGraphics();
		    bGr.drawImage(img, 0, 0, null);
		    bGr.dispose();
			//Identificar el tamaño de las hojas
			ByteArrayOutputStream baos=null;
			try
			{
				baos=new ByteArrayOutputStream();
				ImageIO.write(bim, "jpg", baos );
				return baos.toByteArray();
			}
			finally
			{
				if(baos != null)
				{
					baos.close();
				}
			}
	}
	
//	public static byte[] getBytesTIFF(Image img, float factorCompresion)throws IOException
	public static byte[] getBytesTIFF(Image img2, float factorCompresion)throws IOException
	{
//		int alto = img.getHeight(null);
//		int ancho = img.getWidth(null);
//		int nuevoAlto = (int) (alto * factorCompresion);
		
//		Image img2 = img.getScaledInstance(-1, nuevoAlto, Image.SCALE_SMOOTH);
//		;

		if (img2 != null)
		{
			byte[] barr = null;
			ByteArrayOutputStream baos = null;
			ImageOutputStream ios = null;
			baos = new ByteArrayOutputStream();
			ios = ImageIO.createImageOutputStream(baos);
			
			boolean foundWriter = false;

			BufferedImage bimg = new BufferedImage(img2.getWidth(null),
					img2.getHeight(null), BufferedImage.TYPE_BYTE_BINARY);
			bimg.createGraphics().drawImage(img2, 0, 0, null);
			int numImage = 0;
			for (Iterator<ImageWriter> writerIter = ImageIO.getImageWritersByFormatName("tif");
					writerIter.hasNext() && !foundWriter;)
			{
				foundWriter = true;
				ImageWriter writer = writerIter.next();
				writer.setOutput(ios);
				TIFFImageWriteParam writeParam = (TIFFImageWriteParam) writer
						.getDefaultWriteParam();
				writeParam.setCompressionMode(ImageWriteParam.MODE_EXPLICIT);
				writeParam.setCompressionType("CCITT T.4");
				writer.prepareWriteSequence(null);
				ImageTypeSpecifier spec = ImageTypeSpecifier.createFromRenderedImage(bimg);
				javax.imageio.metadata.IIOMetadata metadata = writer.getDefaultImageMetadata(spec, writeParam);
				IIOImage iioImage = new IIOImage(bimg, null, metadata);
				writer.writeToSequence(iioImage, writeParam);
				bimg.flush();
				writer.endWriteSequence();
				ios.flush();
				writer.dispose();
				ios.close();
				barr = baos.toByteArray();
	        
//	        double actualSize= (double) barr.length/1024;
//			System.out.println("File Size: " + String.format("%.2f",actualSize) + "kb");
	        
//	        tiffToJPeg(barr);

				baos.close();
	        
//	        double actualSize= (double) barr.length/1024;
//			System.out.println("File Size: " + String.format("%.2f",actualSize) + "kb");
//			File f = new File("C:\\PruebasPdf\\", "gabriel" + numImage++ + ".tif");
//			f.deleteOnExit();
//			FileOutputStream fous = new FileOutputStream( f );
//			fous.write(barr, 0, barr.length);
//			fous.close();	
//			f.delete();
	        
//	        createPdf(f.getPath());

			}
			return barr;
		}
		return null;
	}

	public static  byte[] cargarArchivo(File file)
			throws FileNotFoundException, IOException
	{
		BufferedInputStream bis = null;
		FileInputStream fileInputStream = null;
		try
		{
			fileInputStream = new FileInputStream(file);
			bis = new BufferedInputStream(fileInputStream);
			int b = 0;
			byte[] fileBytes = new byte[(int) file.length()];
			int index = 0;
			while((b = bis.read()) != -1)
			{
				fileBytes[index] = (byte) b;
				index++;
			}
			return fileBytes;
		}
		finally
		{
//			file.deleteOnExit();
			if(bis != null)
			{
				bis.close();
			}
			if(fileInputStream != null)
			{
				fileInputStream.close();
			}
//			file.delete();
		}
	}
	
	public static void writeTif(byte[] bytes, PdfCopy writer) throws DocumentException, IOException
	{
		RandomAccessSourceFactory fact = new RandomAccessSourceFactory();
		RandomAccessFileOrArray ra = null;
		try
		{
			ra = new RandomAccessFileOrArray(fact.createSource(bytes));
			int n = TiffImage.getNumberOfPages(ra);
			com.itextpdf.text.Image img;
			for (int i = 1; i <= n; i++)
			{
				img = TiffImage.getTiffImage(ra, i);
	
				float height = img.getHeight();
				float width = img.getWidth();
				float newHeight = 800;
				img.scaleToFit(newHeight*width/height, newHeight);
				img.setCompressionLevel(9);
				//img.setDpi(200, 200);
				System.out.println("Hoja agregada: " + writer.addDirectImageSimple(img));
			}
		}
		finally
		{
			if(ra != null)
			{
				ra.close();
			}
		}
	}
	public static Rectangle recActual = null;
	
	public static com.itextpdf.text.Image addTif(byte[] bytes, double resolScan) throws DocumentException, IOException
	{
		RandomAccessSourceFactory fact = new RandomAccessSourceFactory();
		RandomAccessFileOrArray ra = null;
		com.itextpdf.text.Image img = null;
		try
		{
			ra = new RandomAccessFileOrArray(fact.createSource(bytes));
			int n = TiffImage.getNumberOfPages(ra);
			
			for (int i = 1; i <= n; i++)
			{
				img = TiffImage.getTiffImage(ra, i);
	
				float height = img.getHeight();
				float width = img.getWidth();
				
				System.out.println("width: " + width + "height:" + height);
				
//				float newHeight = 800;
//				img.scaleToFit(newHeight*width/height, newHeight);
				img.setCompressionLevel(9);
				//img.setDpi(200, 200);
				
//				float division = width / height;
				
				
//				if(height > 1800)
				
				recActual = calculaRectangle(img, resolScan);
//				if(height > 2200)
//				{
//					recActual = new Rectangle(ImageTool.OFICIO_WIDTH, ImageTool.OFICIO_HEIGHT);
//					System.out.println("Hoja Oficio");
//				}
//				else
//				{
//					recActual = new Rectangle(ImageTool.CARTA_WIDTH, ImageTool.CARTA_HEIGHT);
//					System.out.println("Hoja carta");
//				}
				img.scaleToFit(recActual);
				
				height = img.getHeight();
				width = img.getWidth();
				
				System.out.println("Después de escalar width: " + width + "height:" + height);

				
			}
			return img;
		}
		finally
		{
			if(ra != null)
			{
				ra.close();
			}
		}
	}
	
	
	
	private static Rectangle calculaRectangle(com.itextpdf.text.Image img, double resolScan)
	{
		float width = img.getWidth();
		float height = img.getHeight();
//		float resolucion = 200;
		
		float widthPulg = width / new Double(resolScan).floatValue();
		float heightPulg = height / new Double(resolScan).floatValue();
		
		//Rectangle recibe puntos, una pulgada tiene 72 puntos, segun google
		
		return new Rectangle(widthPulg * 72, heightPulg * 72 );
		
	}

	public static Rectangle addTif(Document document, byte[] bytes) throws DocumentException, IOException
	{
		RandomAccessSourceFactory fact = new RandomAccessSourceFactory();
		RandomAccessFileOrArray ra = null;
		try
		{
			Rectangle rec = null;
			ra = new RandomAccessFileOrArray(fact.createSource(bytes));
			int n = TiffImage.getNumberOfPages(ra);
			com.itextpdf.text.Image img;
			for (int i = 1; i <= n; i++)
			{
				img = TiffImage.getTiffImage(ra, i);
	
				float height = img.getHeight();
				float width = img.getWidth();
				float newHeight = 800;
//				img.scaleToFit(newHeight*width/height, newHeight);
				img.setCompressionLevel(9);
				//img.setDpi(200, 200);
				
				float division = width / height;
				
				
				if(height > 1800)
				{
					rec = new Rectangle(ImageTool.OFICIO_WIDTH, ImageTool.OFICIO_HEIGHT);
				}
				else
				{
					rec = new Rectangle(ImageTool.CARTA_WIDTH, ImageTool.CARTA_HEIGHT);
				}
				img.scaleToFit(rec);
				System.out.println("width: " + width + "height:" + height);
				System.out.println("Hoja agregada: " + document.add(img));
			}
			return rec;
		}
		finally
		{
			if(ra != null)
			{
				ra.close();
			}
		}
	}
}