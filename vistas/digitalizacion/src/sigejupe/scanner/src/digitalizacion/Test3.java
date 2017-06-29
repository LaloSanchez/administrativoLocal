/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package digitalizacion;
 
import java.io.BufferedReader;
import java.io.File;
import java.io.IOException;
import java.io.InputStreamReader;
import javax.swing.JFileChooser;

import javax.swing.JFileChooser;

public class Test3 {
  public static void main(String s[]) throws IOException, InterruptedException {
     
ProcessBuilder   ps=new ProcessBuilder("c:\\digital\\conv.bat", "EXP_3_0_0_73");
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
System.exit(0);
  }
}
