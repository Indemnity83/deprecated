package com.tinylauncher;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.URL;
import java.util.Enumeration;
import java.util.jar.JarEntry;
import java.util.jar.JarFile;

public class Util {

    /**
     * @param args
     */
    public static void main(String[] args) {
        // TODO Auto-generated method stub

    }
    
    /**
     * Get file name portion of URL.
     * 
     * @param url
     *            Get file name from this url
     * @return file name as string
     */
    public static String getFileName(URL url) {
        String fileName = url.getFile();
        return fileName.substring(fileName.lastIndexOf('/') + 1);
    }

    /**
     * Get jar name from URL.
     * 
     * @param url
     *            Get jar file name from this url
     * @return file name as string
     */
    public static String getJarName(URL url) {
        String fileName = url.getFile();

        if (fileName.endsWith(".pack.lzma")) {
            fileName = fileName.replaceAll(".pack.lzma", "");
        } else if (fileName.endsWith(".pack")) {
            fileName = fileName.replaceAll(".pack", "");
        } else if (fileName.endsWith(".lzma")) {
            fileName = fileName.replaceAll(".lzma", "");
        }

        return fileName.substring(fileName.lastIndexOf('/') + 1);
    }
    
    /**
     * 
     * @param u
     * @param f
     * @throws Exception
     */
    public static void download(URL u, File f)  {
        final Downloader d = new Downloader(u, f);

        new Thread(d).start();

        while (true) {
            if (d.isProgressUpdated()) {
                final int percent = d.getProgressPercent();

                App.setProgress(String.format(d.getProgressString() + ": %s %s", getFileName(u), (percent >= 0) ? String.format("(%d%%)", percent) : ""), percent);
            }

            if (d.isCompleted()) {
                try {
                    d.waitUntilCompleted();
                } catch (Exception e) {
                    // TODO Auto-generated catch block
                    e.printStackTrace();
                }
                break;
            }

            try {
                Thread.sleep(500L);
            } catch (InterruptedException e) {
                // TODO Auto-generated catch block
                e.printStackTrace();
            }
        }
    }
    
    public static void extractJar(JarFile jarFile, File path) throws FileNotFoundException, IOException {
        
        int percent = 0;
        int totalSizeExtract = 0;
        int currentSizeExtract = 0;
        int bufferSize;
        byte buffer[] = new byte[65536];
        
        /* Make sure our destination path exists */
        if(!path.exists()) {
            path.mkdirs();
        }
        
        /* Get list of files in jar */
        Enumeration<JarEntry> entities = jarFile.entries();
        
        /* Calculate the size of all files for progress bar */
        while( entities.hasMoreElements() ) {
            JarEntry entry = (JarEntry) entities.nextElement();
            totalSizeExtract += entry.getSize();
        }
        
        /* Reset list */
        entities = jarFile.entries();
        
        /* Extract all files */
        while( entities.hasMoreElements() ) {
            JarEntry entry = (JarEntry) entities.nextElement();
            File f = new File(path + File.separator + entry.getName());
            
            /* If file exists, attempt removal */
            if( f.exists() ) {
                if(!f.delete()) {
                    /* Unable to delete, skip file */
                    continue;
                }
            }
            
            /* If its a directory, create it */
            if(entry.isDirectory() || entry.getName().indexOf('/') != -1) {
                f.mkdir();
                continue;
            }
            
            InputStream in = jarFile.getInputStream(jarFile.getEntry(entry.getName()));
            OutputStream out = new FileOutputStream(path + File.separator + entry.getName());
            
            while((bufferSize = in.read(buffer, 0, buffer.length))!= -1) {
               out.write(buffer, 0, bufferSize);
               currentSizeExtract += bufferSize;
               
               percent = (currentSizeExtract) / totalSizeExtract;
               App.setProgress(String.format("Extracting: %s %s", entry.getName(), (percent >= 0) ? String.format("(%d%%)", percent) : ""), percent);
            }
            
            in.close();
            out.close();
        }
        
        jarFile.close();
        App.resetProgress();
    }
    
    

}
