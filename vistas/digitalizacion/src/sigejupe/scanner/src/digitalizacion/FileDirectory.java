package digitalizacion;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Container;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.event.KeyEvent;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JFrame;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTree;
import javax.swing.event.TreeSelectionEvent;
import javax.swing.event.TreeSelectionListener;
import javax.swing.tree.DefaultMutableTreeNode;
import javax.swing.tree.DefaultTreeModel;
import javax.swing.tree.TreePath;
import javax.swing.tree.TreeSelectionModel;
import org.apache.commons.io.FilenameUtils;


public final class FileDirectory extends JPanel {
    
    public JTree tree;
    public DefaultMutableTreeNode treenode;
    public DefaultTreeModel model;
    
    digitalizacion.Digitalizacion myscanner;
    public BorderLayout layout;
    
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

    public void setScanner (Digitalizacion estescanner)
    {
    this.myscanner=estescanner;
    }        
    
   private void tree_KeyReleased(KeyEvent e) {
      try {
      String filename="";    
      int keyCode = e.getKeyCode();
      int noselect, row,pos;
 
      TreePath selPath = tree.getSelectionPath();
      model = (DefaultTreeModel) (tree.getModel());
      
      if ((keyCode == e.VK_DELETE)&&(selPath!=null)) { 
          noselect=(int) tree.getSelectionRows()[0];
    
          if (noselect!=0)
          {   
             TreePath[] nodes = tree.getSelectionPaths ();
            if (nodes.length>1)
            {          
               for (int i = 0; i < nodes.length; i ++)
                {
                 TreePath treePath = nodes[i];

                 DefaultMutableTreeNode node =(DefaultMutableTreeNode)treePath.getLastPathComponent ();
                 Object Obj =  node.getUserObject ();

                    System.out.println ("c="+Obj.toString());
                 
                    if (i==0) {filename+="C:\\borrame\\"+Obj.toString();}
                    else {filename+=",C:\\borrame\\"+Obj.toString();}
                }
            }
            else
            {
                         
          System.out.println("Borrar ->"+selPath+"-"+noselect);   
          Object elements[] = selPath.getPath();
          filename=elements[0]+"\\"+elements[1];
  
          System.out.println("archivos="+filename);        
          }
          Integer totalnodos=tree.getModel().getChildCount(tree.getModel().getRoot());
          System.out.println("borra"+filename);  
          myscanner.borrar(filename); //Preguntar antes.  
         
          System.out.println("nodos="+totalnodos);
          System.out.println("seleccion="+noselect);
          
             if (totalnodos<=noselect)  tree.setSelectionRow(totalnodos-1);
             else tree.setSelectionRow(noselect);
          
      } 
     }
      if ((keyCode == e.VK_ESCAPE))
              {          
              System.out.println ("Escape=recargar");  
              reloadTree();
              }     
      
      } catch (NullPointerException ex) {
      //System.out.println("Null");
      }
   }
   
 public void reloadDir(File dir)
 {
  DefaultTreeModel model = (DefaultTreeModel)tree.getModel();
  DefaultMutableTreeNode root = (DefaultMutableTreeNode)model.getRoot();
  //root.add(new DefaultMutableTreeNode("chamoy"));
  //root.add(new DefaultMutableTreeNode("chamoy2"));
       
  ArrayList files = new ArrayList();
   
    String[] tmp = dir.list();
    for (int i = 0; i < tmp.length; i++) {
         if ((tmp[i].indexOf(".pdf")>0)&&(validname(tmp[i]))) files.add(tmp[i]);
    }
       
    Collections.sort(files, new Comparator <String>() {
        public int compare(String o1, String o2) {
            return extractInt(o1) - extractInt(o2);
        }
        int extractInt(String s) {
            String num = s.replaceAll("\\D", "");
            return num.isEmpty() ? 0 : Integer.parseInt(num);
        } });
    
    for (int fnum = 0; fnum < files.size(); fnum++)
    root.add(new DefaultMutableTreeNode(files.get(fnum)));
   
    model.reload(root);
 }        
    
 public void reloadTree()
 {        
  removeAllnodes();
  reloadDir(new File("C:\\borrame"));
 }

 public void removeAllnodes()
 {        
 DefaultTreeModel model = (DefaultTreeModel)tree.getModel();
 DefaultMutableTreeNode root = (DefaultMutableTreeNode)model.getRoot();
 
root.removeAllChildren();
model.reload(root);
 }
 
 public FileDirectory(String dirtmp) {
    File dir;
        
    createDir(dirtmp);
    dir = new File(dirtmp);
    layout= new BorderLayout();
    setLayout(layout);

    tree = new JTree(addNodes(null, dir));
    tree.getSelectionModel().setSelectionMode(TreeSelectionModel.DISCONTIGUOUS_TREE_SELECTION);
          
    tree.addTreeSelectionListener(new TreeSelectionListener() {
      public void valueChanged(TreeSelectionEvent e) {
        DefaultMutableTreeNode node = (DefaultMutableTreeNode) e.getPath().getLastPathComponent();
        if (!node.isRoot())
        { 
            System.out.println("Visualizar el PDF " + node); 
            try {
                
               myscanner.cargarJPG("C:\\borrame\\"+FilenameUtils.getBaseName(node.toString())+".jpg");   
            } catch (IOException ex) {
                Logger.getLogger(FileDirectory.class.getName()).log(Level.SEVERE, null, ex);
            }
            
        }
      }
    });
    
     tree.addKeyListener(new java.awt.event.KeyAdapter() {
         public void keyPressed(KeyEvent e) {
            tree_KeyReleased(e);
         }
      });
     
     final Font currentFont = tree.getFont();
     final Font bFont = new Font(currentFont.getName(), currentFont.getStyle(), currentFont.getSize() - 3);
     tree.setFont(bFont);
        
    JScrollPane scrollpane = new JScrollPane();
    scrollpane.getViewport().add(tree);
    add(BorderLayout.CENTER, scrollpane);
  }
boolean validname(String filename)
{
 int pos1, pos2;
 pos1=filename.indexOf(".");
 pos2=filename.lastIndexOf(".");
 
 if (pos1!=pos2) return false;
 if (filename.indexOf(".pdf")<0) return false;
         
 return true; 
}          
  DefaultMutableTreeNode addNodes(DefaultMutableTreeNode curTop, File dir) {
    ArrayList files = new ArrayList();
    String curPath = "C:\\borrame";  
    DefaultMutableTreeNode curDir = new DefaultMutableTreeNode(curPath);
    
    if (curTop != null) curTop.add(curDir);
     
    String[] tmp = dir.list();
    for (int i = 0; i < tmp.length; i++) {
        System.out.println(tmp[i]+"="+validname(tmp[i]));
        if (validname(tmp[i])) files.add(tmp[i]); 
    }
    
    Collections.sort(files, new Comparator <String>() {
        public int compare(String o1, String o2) {
            return extractInt(o1) - extractInt(o2);
        }
        int extractInt(String s) {
            String num = s.replaceAll("\\D", "");
            return num.isEmpty() ? 0 : Integer.parseInt(num);
        } });
   
    for (int fnum = 0; fnum < files.size(); fnum++)
      curDir.add(new DefaultMutableTreeNode(files.get(fnum)));
    return curDir;
  }

  public Dimension getMinimumSize() {
    return new Dimension(150, 650);
  }

  public Dimension getPreferredSize() {
    return new Dimension(150, 650);
  }

  public static void main(String[] av) {

    JFrame frame = new JFrame("[PDFS]");
    FileDirectory filepanel= new FileDirectory("C:\\borrame");
  
    Container cp = frame.getContentPane();
    cp.add(filepanel);
  
    frame.pack();
    frame.setVisible(true);
    frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
     
  }
}