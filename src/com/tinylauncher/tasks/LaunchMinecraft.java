package com.tinylauncher.tasks;

import java.applet.Applet;
import java.io.File;
import java.io.IOException;
import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Modifier;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLClassLoader;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.zip.ZipEntry;
import java.util.zip.ZipException;
import java.util.zip.ZipFile;

import com.tinylauncher.App;
import com.tinylauncher.gui.MinecraftFrame;

public class LaunchMinecraft {

    public static void taskStart() {

        // TODO what is this?
        boolean compatMode = false;

        App.setTask("Launching Minecraft");

        File userDir = new File(System.getProperty("user.dir"));
        String encoding = System.getProperty("file.encoding");

        App.setAction("Loading jars");
        String[] jarFiles = new String[] { "minecraft.jar", "lwjgl.jar", "lwjgl_util.jar", "jinput.jar" };
        URL[] urls = new URL[jarFiles.length];

        for (int i = 0; i < urls.length; i++) {
            try {
                File f = new File(App.getMinecraftBin(), jarFiles[i]);
                urls[i] = f.toURI().toURL();
                App.setProgress("Loading URL: " + urls[i].toString(), 0);
            } catch (MalformedURLException e) {
                App.fatalError("Malformed URL Exception " + e.toString());
            }
        }

        /* Load natives into library path */
        App.setAction("Loading natives");
        String nativesDir = new File(userDir, App.getMinecraftBin() + File.separator + "natives").toString();
        System.setProperty("org.lwjgl.librarypath", nativesDir);
        System.setProperty("net.java.games.input.librarypath", nativesDir);

        /* Get the minecraft class */
        App.setAction("Loading Minecraft");
        URLClassLoader cl = new URLClassLoader(urls, App.class.getClassLoader());
        Class<?> mc = null;
        try {
            mc = cl.loadClass("net.minecraft.client.Minecraft");
            Field f = getMCPathField(mc);

            if (f == null) {
                App.fatalError("Could not find Minecraft path field. Launch failed.");
            }

            f.setAccessible(true);
            f.set(null, userDir);
            App.setProgress("Fixed Minecraft Path: Field was " + f.toString(), 100);
        } catch (ClassNotFoundException e) {
            App.setAction("Can't find main class, Searching...");

            /* Look for any class that looks like the main class. */
            File mcJar = new File(App.getMinecraftBin(), "minecraft.jar");
            ZipFile zip = null;
            try {
                zip = new ZipFile(mcJar);
            } catch (ZipException e1) {
                App.fatalError("Search failed.");
            } catch (IOException e1) {
                App.fatalError("Search failed.");
            }

            Enumeration<? extends ZipEntry> entries = zip.entries();
            ArrayList<String> classes = new ArrayList<String>();

            while (entries.hasMoreElements()) {
                ZipEntry entry = entries.nextElement();
                if (entry.getName().endsWith(".class")) {
                    String entryName = entry.getName().substring(0, entry.getName().lastIndexOf('.'));
                    entryName = entryName.replace('/', '.');
                    App.setProgress("Found class: " + entryName, -1);
                    classes.add(entryName);
                }
            }

            for (String clsName : classes) {
                try {
                    Class<?> cls = cl.loadClass(clsName);
                    if (!Runnable.class.isAssignableFrom(cls)) {
                        continue;
                    } else {
                        App.setProgress("Found class implementing runnable: " + cls.getName(), -1);
                    }

                    if (getMCPathField(cls) == null) {
                        continue;
                    } else {
                        App.setProgress("Found class implementing runnable " + "with mcpath field: " + cls.getName(), -1);
                    }

                    mc = cls;
                    cl.close();
                    break;
                } catch (ClassNotFoundException e1) {
                    // Ignore
                    continue;
                } catch (IOException e1) {
                    // TODO Auto-generated catch block
                    e1.printStackTrace();
                }
            }
        } catch (IllegalArgumentException e) {
            App.fatalError("Illegal Arguments " + e.toString());
            e.printStackTrace();
        } catch (IllegalAccessException e) {
            App.fatalError("Illegal Access " + e.toString());
            e.printStackTrace();
        }

        /* Lets get down to actually launching the game */
        System.setProperty("minecraft.applet.TargetDirectory", userDir.getAbsolutePath());

        String[] mcArgs = new String[2];
        mcArgs[0] = App.getUsername();
        mcArgs[1] = App.getSessionID();

        try {
            if (compatMode) {
                App.setAction("Launching in compatibility mode");
                mc.getMethod("main", String[].class).invoke(null, (Object) mcArgs);
            } else {
                App.setAction("Launching with applet wrapper");
                try {
                    Class<?> MCAppletClass = cl.loadClass("net.minecraft.client.MinecraftApplet");
                    Applet mcappl = (Applet) MCAppletClass.newInstance();
                    MinecraftFrame mcWindow = new MinecraftFrame(App.getWindowName());
                    mcWindow.start(mcappl, App.getUsername(), App.getSessionID(), App.getWindowSize(), App.runMaximized());
                } catch (InstantiationException e) {
                    App.setAction("Applet wrapper failed! Falling back to compatibility mode.");
                    mc.getMethod("main", String[].class).invoke(null, (Object) mcArgs);
                } catch (ClassNotFoundException e) {
                    App.fatalError("Class not found " + e.toString());
                    e.printStackTrace();
                }
            }
        } catch (IllegalAccessException e) {
            App.fatalError("Illegal Access " + e.toString());
            e.printStackTrace();
        } catch (InvocationTargetException e) {
            App.fatalError("Invocation Target Exception " + e.toString());
            e.printStackTrace();
        } catch (NoSuchMethodException e) {
            App.fatalError("No Such method " + e.toString());
            e.printStackTrace();
        } catch (SecurityException e) {
            App.fatalError("Security error " + e.toString());
            e.printStackTrace();
        }

    }

    /**
     * 
     * @param mc
     * @return
     */
    public static Field getMCPathField(Class<?> mc) {
        Field[] fields = mc.getDeclaredFields();

        for (int i = 0; i < fields.length; i++) {
            Field f = fields[i];
            if (f.getType() != File.class) {
                // Has to be File
                continue;
            }
            if (f.getModifiers() != (Modifier.PRIVATE + Modifier.STATIC)) {
                // And Private Static.
                continue;
            }
            return f;
        }
        return null;
    }

}
