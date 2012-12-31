package com.tinylauncher.tasks;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.List;
import java.util.jar.JarEntry;
import java.util.jar.JarFile;

import com.tinylauncher.App;
import com.tinylauncher.Util;

public class GameUpdate {

    public static void taskStart() {

        App.setTask("Updating Minecraft");

        List<URL> jarURLs = new ArrayList<URL>();

        /* Build the URL list */
        App.setAction("Initializing");
        try {
            URL mojangURL = new URL("http://s3.amazonaws.com/MinecraftDownload/");
            jarURLs.add(new URL(mojangURL, "minecraft.jar"));
            jarURLs.add(new URL(mojangURL, "lwjgl_util.jar"));
            jarURLs.add(new URL(mojangURL, "jinput.jar"));
            jarURLs.add(new URL(mojangURL, "lwjgl.jar"));
            jarURLs.add(new URL(mojangURL, nativeJar()));

        } catch (MalformedURLException e) {
            App.fatalError(e.getMessage());
        }

        App.setAction("Creating directories");
        File minecraftBin = new File(App.getMinecraftBin());
        minecraftBin.mkdirs();

        App.setAction("Downloading packages");
        for (URL jarURL : jarURLs) {
            File file = new File(minecraftBin, Util.getFileName(jarURL));
            Util.download(jarURL, file);
        }

        App.setAction("Extracting natives");
        try {
            Util.extractJar(new JarFile(new File(minecraftBin, nativeJar())), new File(minecraftBin, "/natives"));
        } catch (IOException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }

    }

    private static String nativeJar() {
        String osName = System.getProperty("os.name");
        String natives = null;
        if (osName.startsWith("Win")) {
            natives = "windows_natives.jar";
        } else if (osName.startsWith("Linux") || osName.startsWith("FreeBSD")) {
            natives = "linux_natives.jar";
        } else if (osName.startsWith("Mac")) {
            natives = "macosx_natives.jar";
        } else if (osName.startsWith("Solaris") || osName.startsWith("SunOS")) {
            natives = "solaris_natives.jar";
        } else {
            App.fatalError("OS (" + osName + ") not supported");
        }

        return natives;
    }
}
