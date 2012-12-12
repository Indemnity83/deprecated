package launcher;

import javax.swing.*;
import javax.swing.text.DefaultCaret;

import java.applet.Applet;
import java.awt.*;
import java.io.*;
import java.lang.reflect.Constructor;
import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Modifier;
import java.net.*;
import java.nio.channels.FileChannel;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.StringTokenizer;
import java.util.jar.JarEntry;
import java.util.jar.JarFile;
import java.util.jar.JarOutputStream;
import java.util.jar.Pack200;
import java.util.zip.ZipEntry;
import java.util.zip.ZipException;
import java.util.zip.ZipFile;

public class TinyLauncher {
    /***************************************************************************
     * Constants - TODO: Temporary constants until loadConfig is complete.
     **************************************************************************/
    public static String VERSION_FORGE     = "1.4.5-6.4.1.436";
    public static String VERSION_MINECRAFT = "1.4.5";
    public static String WINDOW_NAME       = "MinecraftForge";
    public static String WINDOW_SIZE       = "max";
    
    /***************************************************************************
     * Properties
     **************************************************************************/
    protected static TinyLauncher launcher;

    protected String[]      session           = new String[4];
    protected JFrame        frame;
    protected String        OS                = System.getProperty("os.name").toLowerCase();
    protected JTextArea     console           = new JTextArea();

    /** Whether a fatal error occurred */           protected boolean       fatalError;
    /** Fatal error that occurred */                protected String        fatalErrorDescription;
    /** Current size of download in bytes */        protected int           currentSizeDownload;
    /** Total size of download in bytes */          protected int           totalSizeDownload;
    /** Current size of extracted in bytes */       protected int           currentSizeExtract;
    /** Total size of extracted in bytes */         protected int           totalSizeExtract;
    /** Used to calculate length of progress bar */ protected int           percentage;
    /** String to display as a subtask */           protected static String subtaskMessage = "";

    /*--------------------------------------------*/
    /* Paths for various Minecraft/Forge elements */
    /*--------------------------------------------*/
    protected static File minecraftBin;
    protected static File modsDir;
    protected static File texturesDir;
    protected static File coremodsDir;
    protected static File configsDir;
    protected static File savesDir;
    protected static File userDir;

    /***************************************************************************
     * Constructor
     * 
     * @param title
     */
    protected TinyLauncher(String title) {
        frame = new JFrame(title);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setSize(600, 300);
        frame.setLocationRelativeTo(null);

        // console is used to display output on the main frame
        console.setLineWrap(true);
        console.setFont(new Font("Monospaced", Font.PLAIN, 12));
        console.setBackground(Color.black);
        console.setForeground(Color.green);
        console.setEditable(false);
    }

    /**
     * Application main
     * 
     * @param args
     * @throws IOException
     */
    public static void main(String[] args) throws IOException {
        launcher                   = new TinyLauncher("Tiny Launcher");
        JScrollPane  scrollConsole = new JScrollPane(launcher.console);
        DefaultCaret caret         = (DefaultCaret) launcher.console.getCaret();
        String       path          = System.getProperty("user.dir").toString().concat("/bin/");
        Boolean      isSessionOK   = null;

        minecraftBin  = new File(path);
        caret.setUpdatePolicy(DefaultCaret.ALWAYS_UPDATE);

        launcher.frame.add(scrollConsole);
        launcher.frame.setVisible(true);

        // Visible startup
        launcher.frame.setVisible(true);

        launcher.setupPaths(".minecraft");

        // Ask the user for credentials until the session is validated or they
        // click cancel
        do {
            isSessionOK = launcher.getUserSession();
        } while (isSessionOK != null && !isSessionOK);

        if (isSessionOK != null && isSessionOK) {
            launcher.loadMinecraft(VERSION_MINECRAFT);
            launcher.loadForge(VERSION_FORGE);
            launcher.launchGame();
        }
    }
    
    /**
     * Launches the game!
     * 
     * @param session
     */
    protected void launch(String[] session) {
        if (session.length < 3)
        {
          System.out.println("Not enough arguments.");
          System.exit(-1);
        }
        String userName    = session[0];
        String sessionId   = session[1];
        String windowtitle = session[2];
        userDir            = new File(System.getProperty("user.dir"));
        String encoding    = System.getProperty("file.encoding");
        System.out.println("File encoding: " + encoding);
        
        Dimension winSize = new Dimension(854, 480);
        boolean maximize = false;
        boolean compatMode = false;
        
        if (session.length >= 4)
        {
            String windowParams = session[3];
            String[] dimStrings = windowParams.split("x");
            
            if (windowParams.equalsIgnoreCase("compatmode"))
            {
              compatMode = true;
            }
            else if (windowParams.equalsIgnoreCase("max"))
            {
              maximize = true;
            }
            else if (dimStrings.length == 2)
            {
              try
              {
                winSize = new Dimension(Integer.parseInt(dimStrings[0]),
                    Integer.parseInt(dimStrings[1]));
              }
              catch (NumberFormatException e)
              {
                System.out.println("Invalid Window size argument, " +
                    "using default.");
              }
            }
            else
            {
              System.out.println("Invalid Window size argument, " +
                  "using default.");
            }
        }
        
        try {
            System.out.println("Loading jars...");
            String[] jarFiles = new String[] {
              "minecraft.jar", "lwjgl.jar", "lwjgl_util.jar", "jinput.jar"
              };
            
            URL[] urls = new URL[jarFiles.length];
            
            for (int i = 0; i < urls.length; i++) {
                try {
                    File f = new File(minecraftBin, jarFiles[i]);
                    urls[i] = f.toURI().toURL();
                    System.out.println("Loading URL: " + urls[i].toString());
                } catch (MalformedURLException e) {
          //          e.printStackTrace();
                    System.err.println("MalformedURLException, " + e.toString());
                    System.exit(5);
                }
            }
            
            System.out.println("Loading natives...");
            String nativesDir = new File(minecraftBin, "natives").toString();
            
            System.setProperty("org.lwjgl.librarypath", nativesDir);
            System.setProperty("net.java.games.input.librarypath", nativesDir);
      
            URLClassLoader cl = 
                new URLClassLoader(urls, TinyLauncher.class.getClassLoader());
            
            // Get the Minecraft Class.
            Class<?> mc = null;
            try {
                mc = cl.loadClass("net.minecraft.client.Minecraft");
                
                Field f = getMCPathField(mc);
                
                if (f == null) {
                  System.err.println("Could not find Minecraft path field. Launch failed.");
                  System.exit(-1);
                }
                
                f.setAccessible(true);
                f.set(null, userDir);
                // And set it.
                System.out.println("Fixed Minecraft Path: Field was " + f.toString());
            }
            catch (ClassNotFoundException e) {
                System.err.println("Can't find main class. Searching...");
                
                // Look for any class that looks like the main class.
                File mcJar = new File(minecraftBin, "minecraft.jar");
                ZipFile zip = null;
                try {
                    zip = new ZipFile(mcJar);
                } catch (ZipException e1) {
                    e1.printStackTrace();
                    System.err.println("Search failed.");
                    System.exit(-1);
                } catch (IOException e1) {
                    e1.printStackTrace();
                    System.err.println("Search failed.");
                    System.exit(-1);
                }
                
                Enumeration<? extends ZipEntry> entries = zip.entries();
                ArrayList<String> classes = new ArrayList<String>();
                
                while (entries.hasMoreElements()) {
                    ZipEntry entry = entries.nextElement();
                    if (entry.getName().endsWith(".class")) {
                        String entryName = entry.getName().substring(0, entry.getName().lastIndexOf('.'));
                        entryName = entryName.replace('/', '.');
                        System.out.println("Found class: " + entryName);
                        classes.add(entryName);
                  }
                }
                
                for (String clsName : classes) {
                    try {
                        Class<?> cls = cl.loadClass(clsName);
                        if (!Runnable.class.isAssignableFrom(cls)) {
                          continue;
                        }
                        else {
                            System.out.println("Found class implementing runnable: " + 
                                cls.getName());
                        }
                        
                        if (getMCPathField(cls) == null) {
                            continue;
                        }
                        else {
                            System.out.println("Found class implementing runnable " +
                                "with mcpath field: " + cls.getName());
                        }
                        
                        mc = cls;
                        cl.close();
                        break;
                    }
                    catch (ClassNotFoundException e1) {
                        // Ignore
                        continue;
                    }
                    catch (IOException e1) {
                        // TODO Auto-generated catch block
                        e1.printStackTrace();
                    }
                }
                
                if (mc == null) {
                    System.err.println("Failed to find Minecraft main class.");
                    System.exit(-1);
                }
                else {
                    System.out.println("Found main class: " + mc.getName());
                }
            }
            
            System.setProperty("minecraft.applet.TargetDirectory", userDir.getAbsolutePath());
            
            String[] mcArgs = new String[2];
            mcArgs[0] = userName;
            mcArgs[1] = sessionId;
      
            // this is bogus, the method is never used for anything after we set the field
            /*
            String mcDir =  mc.getMethod("a", String.class).invoke(null, (Object) "minecraft").toString();
      
            System.out.println("MCDIR: " + mcDir);
            */
            
            if (compatMode) {
                System.out.println("Launching in compatibility mode...");
                mc.getMethod("main", String[].class).invoke(null, (Object) mcArgs);
            }
            else {
                System.out.println("Launching with applet wrapper...");
                try {
                    Class<?> MCAppletClass = cl.loadClass(
                        "net.minecraft.client.MinecraftApplet");
                    Applet mcappl = (Applet) MCAppletClass.newInstance();
                    MCFrame mcWindow = new MCFrame(windowtitle);
                    mcWindow.start(mcappl, userName, sessionId, winSize, maximize);
                } catch (InstantiationException e) {
                    System.out.println("Applet wrapper failed! Falling back " +
                        "to compatibility mode.");
                    mc.getMethod("main", String[].class).invoke(null, (Object) mcArgs);
                }
            }
          } catch (ClassNotFoundException e) {
              e.printStackTrace();
              System.exit(1);
          } catch (IllegalArgumentException e) {
              e.printStackTrace();
              System.exit(2);
          } catch (IllegalAccessException e) {
              e.printStackTrace();
              System.exit(2);
          } catch (InvocationTargetException e) {
              e.printStackTrace();
              System.exit(3);
          } catch (NoSuchMethodException e) {
              e.printStackTrace();
              System.exit(3);
          } catch (SecurityException e) {
              e.printStackTrace();
              System.exit(4);
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
    
    /**
     * Setup folders used by the Minecraft and Forge systems
     * 
     * @param root
     *            the root path to use
     */
    public void setupPaths(String root) {
        console.append("Setting up directories under " + root + " ... ");

        minecraftBin = new File(root + File.separator + "bin" + File.separator);
        modsDir = new File(root + File.separator + "mods" + File.separator);
        texturesDir = new File(root + File.separator + "texturepacks" + File.separator);
        coremodsDir = new File(root + File.separator + "coremods" + File.separator);
        configsDir = new File(root + File.separator + "configs" + File.separator);
        savesDir = new File(root + File.separator + "saves" + File.separator);

        minecraftBin.mkdirs();
        modsDir.mkdirs();
        texturesDir.mkdirs();
        coremodsDir.mkdirs();
        configsDir.mkdirs();
        savesDir.mkdirs();

        console.append("Done \n");
    }
    
    /**
     * Opens a dialog for the user to enter credentials. Validates with the
     * server and checks versions.
     * 
     * @return Results. true=success, false=fail, null=cancel
     * @throws IOException
     */
    public Boolean getUserSession() throws IOException {
        console.append("Requesting users credentials ... ");

        // Prompt user for credentials
        JPanel panel = new JPanel();
        String loginReturn = null;
        panel.setLayout(new GridLayout(4, 1));

        JLabel username = new JLabel("Username");
        JLabel password = new JLabel("Password");
        JTextField userField = new JTextField(12);
        JPasswordField passField = new JPasswordField(12);

        passField.setEchoChar('*');

        panel.add(username);
        panel.add(userField);
        panel.add(password);
        panel.add(passField);

        int a = JOptionPane.showConfirmDialog(frame, panel, "Login to Minecraft", JOptionPane.OK_CANCEL_OPTION, JOptionPane.QUESTION_MESSAGE);

        /*
         * Validate credentials with login server
         */
        if (a == JOptionPane.OK_OPTION) {
            // Get a session from Minecraft login servers
            session[0] = userField.getText();
            URL url = null;
            InputStream is = null;

            try {
                url = new URL(String.format("https://login.minecraft.net/?user=%s&password=%s&version=14", userField.getText(),
                        new String(passField.getPassword())));

                // Read the result
                is = url.openStream();
                loginReturn = new BufferedReader(new InputStreamReader(is)).readLine();
            } catch (MalformedURLException e) {
                JOptionPane.showMessageDialog(frame, "URL Panic (Malformed URL)! " + e.getMessage());
                return null;
            } catch (IOException e) {
                JOptionPane.showMessageDialog(frame, "URL Panic (IO Exception)! " + e.getMessage());
                return null;
            } finally {
                // close the steam
                if (is != null) {
                    is.close();
                }
            }

            /*
             * Check if we got a session string back
             */
            if (loginReturn.split(":").length == 5) {
                // Yay! valid session
                console.append("Valid Session \n");
                return true;
            }

            /*
             * No valid session, tell the user why
             */
            if (loginReturn.toLowerCase() == "bad login") {
                console.append("Bad login \n");
                JOptionPane.showMessageDialog(frame, "Invalid username or password.");
                return false;
            } else if (loginReturn.toLowerCase() == "old version") {
                console.append("Old version \n");
                JOptionPane.showMessageDialog(frame, "Launcher outdated, please update.");
                return false;
            } else {
                console.append("Unexpected error \n");
                JOptionPane.showMessageDialog(frame, "Login failed!: " + loginReturn);
                return false;
            }
        }

        /*
         * The user clicked cancel, or something funky happened
         */
        console.append("Canceled \n");
        return null;

    }    
    
    /**
     * Get the required files to run Minecraft. TODO: Verify we're running the
     * expected versions
     */
    public void loadMinecraft(String ver) {

        // jars to load
        String jarList = "lwjgl.jar, jinput.jar, lwjgl_util.jar, minecraft.jar";
        StringTokenizer jar = new StringTokenizer(jarList, ", ");
        int jarCount = jar.countTokens() + 1;
        URL[] urlList = new URL[jarCount];

        // native jar url
        String osName = System.getProperty("os.name");
        String nativeJar = null;

        if (osName.startsWith("Win")) {
            nativeJar = "windows_natives.jar.lzma";
        } else if (osName.startsWith("Linux") || osName.startsWith("FreeBSD")) {
            nativeJar = "linux_natives.jar.lzma";
        } else if (osName.startsWith("Mac")) {
            nativeJar = "macosx_natives.jar.lzma";
        } else if (osName.startsWith("Solaris") || osName.startsWith("SunOS")) {
            nativeJar = "solaris_natives.jar.lzma";
        } else {
            fatalErrorOccured("OS (" + osName + ") not supported");
        }

        URL urlBase;
        try {
            // set jars urls
            urlBase = new URL("https://s3.amazonaws.com/MinecraftDownload/");

            for (int i = 0; i < jarCount - 1; i++) {
                urlList[i] = new URL(urlBase, jar.nextToken());
            }

            if (nativeJar == null) {
                fatalErrorOccured("no lwjgl natives files found");
            } else {
                urlList[jarCount - 1] = new URL(urlBase, nativeJar);
            }

            downloadFiles(urlList, minecraftBin.getPath() + File.separator);
            unpackFiles(urlList, minecraftBin.getPath() + File.separator);

            // Extract the natives, it'll be the last file in the list
            File natives = new File(minecraftBin.getPath() + File.separator + getJarName(urlList[jarCount - 1]));
            File nativeDir = new File(minecraftBin.getPath() + File.separator + "natives" + File.separator);
            extractJar(natives, nativeDir);
            natives.delete();

        } catch (MalformedURLException e) {
            // TODO Auto-generated catch block
            fatalErrorOccured("URL error: " + e.getMessage());
        } catch (Exception e) {
            // TODO Auto-generated catch block
            fatalErrorOccured("Fatal error: " + e.getMessage());
            e.printStackTrace();
        }

    }

    public void loadForge(String ver) {

        URL[] urlList = new URL[1];
        try {
            urlList[0] = new URL("http://files.minecraftforge.net/minecraftforge/minecraftforge-universal-" + ver + ".zip");
            downloadFiles(urlList, minecraftBin.getPath() + File.separator);
        } catch (MalformedURLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        } catch (Exception e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }

    }

    /**
     * Launch Minecraft
     */
    public void launchGame() {
        // Lets do it!
        console.append("Launching Minecraft ... \n");
        session[2] = WINDOW_NAME;
        session[3] = WINDOW_SIZE;
        launch(session);
    }   

    /**
     * Will download the jars from the server using the list of urls in urlList,
     * while at the same time updating progress bar
     * 
     * @param path
     *            location of the directory to save to
     * @throws Exception
     *             if download fails
     */
    protected void downloadFiles(URL[] urlList, String path) throws Exception {

        // state = STATE_DOWNLOADING;

        URLConnection urlconnection;

        // calculate total size of jars to download
        for (int i = 0; i < urlList.length; i++) {
            urlconnection = urlList[i].openConnection();
            urlconnection.setDefaultUseCaches(false);
            totalSizeDownload += urlconnection.getContentLength();
        }

        int initialPercentage = percentage = 10;

        // download each jar
        byte buffer[] = new byte[65536];
        for (int i = 0; i < urlList.length; i++) {

            urlconnection = urlList[i].openConnection();

            String currentFile = getFileName(urlList[i]);
            InputStream inputstream = getJarInputStream(currentFile, urlconnection);
            FileOutputStream fos = new FileOutputStream(path + currentFile);

            int bufferSize;
            long downloadStartTime = System.currentTimeMillis();
            String downloadSpeedMessage = "";

            int cpos = console.getCaretPosition();
            console.append("Retrieving: " + currentFile + " ");
            while ((bufferSize = inputstream.read(buffer, 0, buffer.length)) != -1) {

                fos.write(buffer, 0, bufferSize);
                currentSizeDownload += bufferSize;
                percentage = initialPercentage + ((currentSizeDownload * 45) / totalSizeDownload);
                subtaskMessage = "Retrieving: " + currentFile + " " + ((currentSizeDownload * 100) / totalSizeDownload) + "%";

                long timeLapse = System.currentTimeMillis() - downloadStartTime;
                // update only if a second or more has passed
                if (timeLapse >= 1000) {
                    // get kb/s, nice that bytes/millis is same as
                    // kilobytes/seconds
                    float downloadSpeed = (float) bufferSize / timeLapse;
                    // round to two decimal places
                    downloadSpeed = ((int) (downloadSpeed * 100)) / 100f;
                    // set current speed message
                    downloadSpeedMessage = " @ " + downloadSpeed + " KB/sec";
                    // reset start time
                    downloadStartTime = System.currentTimeMillis();
                }

                subtaskMessage += downloadSpeedMessage;
                console.setText(console.getText(0, cpos));
                console.append(subtaskMessage);
            }
            console.setText(console.getText(0, cpos));
            console.append("Retrieving: " + currentFile + " 100% \n");

            inputstream.close();
            fos.close();
        }
        subtaskMessage = "";
    }

    /**
     * Extract all jars from any lzma/pack files
     * 
     * @param path
     *            output path
     * @throws exception
     *             if any errors occur
     */
    protected void unpackFiles(URL[] fileList, String path) throws Exception {
        // state = STATE_EXTRACTING_PACKAGES;

        float increment = (float) 10.0 / fileList.length;
        // extract all lzma and pack.lzma files

        for (int i = 0; i < fileList.length; i++) {
            percentage = 55 + (int) (increment * (i + 1));
            String filename = getFileName(fileList[i]);

            if (filename.endsWith(".pack.lzma")) {
                subtaskMessage = "Extracting: " + filename + " to " + filename.replaceAll(".lzma", "");
                console.append(subtaskMessage + "\n");

                extractLZMA(path + filename, path + filename.replaceAll(".lzma", ""));

                subtaskMessage = "Extracting: " + filename.replaceAll(".lzma", "") + " to " + filename.replaceAll(".pack.lzma", "");
                console.append(subtaskMessage + "\n");

                extractPack(path + filename.replaceAll(".lzma", ""), path + filename.replaceAll(".pack.lzma", ""));
            } else if (filename.endsWith(".pack")) {
                subtaskMessage = "Extracting: " + filename + " to " + filename.replace(".pack", "");
                console.append(subtaskMessage + "\n");

                extractPack(path + filename, path + filename.replace(".pack", ""));
            } else if (filename.endsWith(".lzma")) {
                subtaskMessage = "Extracting: " + filename + " to " + filename.replace(".lzma", "");
                console.append(subtaskMessage + "\n");

                extractLZMA(path + filename, path + filename.replace(".lzma", ""));
            }

        }
    }

    /**
     * This method will extract all file from a jar and extract them to the
     * subdirectory specified by the path
     * 
     * @param path
     *            base folder containing all downloaded jars
     * @throws Exception
     *             if it fails to extract files
     */
    protected void extractJar(File jar, File path) throws Exception {

        int initialPercentage = percentage;

        // create extract folder
        if (!path.exists()) {
            path.mkdirs();
        }

        // open jar file
        JarFile jarFile = new JarFile(jar, true);

        // get list of files in jar
        Enumeration entities = jarFile.entries();

        totalSizeExtract = 0;

        // calculate the size of the files to extract for progress bar
        while (entities.hasMoreElements()) {
            JarEntry entry = (JarEntry) entities.nextElement();

            // skip directories and anything in directories
            // conveniently ignores the manifest
            if (entry.isDirectory() || entry.getName().indexOf('/') != -1) {
                continue;
            }
            totalSizeExtract += entry.getSize();
        }

        currentSizeExtract = 0;

        // reset point to beginning by getting list of file again
        entities = jarFile.entries();

        // extract all files from the jar
        while (entities.hasMoreElements()) {
            JarEntry entry = (JarEntry) entities.nextElement();

            // skip directories and anything in directories
            // conveniently ignores the manifest
            if (entry.isDirectory() || entry.getName().indexOf('/') != -1) {
                continue;
            }

            // check if file already exists if so delete it to make room
            // for new one
            // useful when using the reload button on the browser
            File f = new File(path + File.separator + entry.getName());
            if (f.exists()) {
                if (!f.delete()) {
                    continue; // unable to delete file, it is in use, skip
                    // extracting it
                }
            }

            InputStream in = jarFile.getInputStream(jarFile.getEntry(entry.getName()));
            OutputStream out = new FileOutputStream(path + File.separator + entry.getName());

            int bufferSize;
            byte buffer[] = new byte[65536];

            while ((bufferSize = in.read(buffer, 0, buffer.length)) != -1) {
                out.write(buffer, 0, bufferSize);
                currentSizeExtract += bufferSize;

                // update progress bar
                percentage = initialPercentage + ((currentSizeExtract * 20) / totalSizeExtract);
                subtaskMessage = "Extracting: " + entry.getName() + " " + ((currentSizeExtract * 100) / totalSizeExtract) + "%";
            }

            in.close();
            out.close();
        }
        subtaskMessage = "";

        jarFile.close();
    }

    /**
     * Sets the state of the loaded and prints some debug information
     * 
     * @param error
     *            Error message to print
     */
    protected void fatalErrorOccured(String error) {
        fatalError = true;
        fatalErrorDescription = "Fatal error occured: " + error;
        console.append(fatalErrorDescription);

    }

 

    public void copyFile(File sourceFile, File destFile) throws IOException {
        if (!destFile.exists()) {
            destFile.createNewFile();
        }

        FileChannel source = null;
        FileChannel destination = null;

        try {
            source = new FileInputStream(sourceFile).getChannel();
            destination = new FileOutputStream(destFile).getChannel();
            destination.transferFrom(source, 0, source.size());
        } finally {
            if (source != null) {
                source.close();
            }
            if (destination != null) {
                destination.close();
            }
        }
    }

    /**
     * Extract LZMA File
     * 
     * @param in
     *            Input path to pack file
     * @param out
     *            output path to resulting file
     * @throws exception
     *             if any errors occur
     */
    @SuppressWarnings({ "rawtypes", "unchecked" })
    protected void extractLZMA(String in, String out) throws Exception {

        File f = new File(in);
        FileInputStream fileInputHandle = new FileInputStream(f);

        // use reflection to avoid hard dependency
        Class clazz = Class.forName("LZMA.LzmaInputStream");
        Constructor constructor = clazz.getDeclaredConstructor(new Class[] { InputStream.class });
        InputStream inputHandle = (InputStream) constructor.newInstance(new Object[] { fileInputHandle });

        OutputStream outputHandle;
        outputHandle = new FileOutputStream(out);

        byte[] buffer = new byte[1 << 14];

        int ret = inputHandle.read(buffer);
        while (ret >= 1) {
            outputHandle.write(buffer, 0, ret);
            ret = inputHandle.read(buffer);
        }

        inputHandle.close();
        outputHandle.close();

        outputHandle = null;
        inputHandle = null;

        // delete LZMA file, as it is no longer needed
        f.delete();
    }

    /**
     * Extract Pack File
     * 
     * @param in
     *            Input path to pack file
     * @param out
     *            output path to resulting file
     * @throws exception
     *             if any errors occur
     */
    protected void extractPack(String in, String out) throws Exception {
        File f = new File(in);
        FileOutputStream fostream = new FileOutputStream(out);
        JarOutputStream jostream = new JarOutputStream(fostream);

        Pack200.Unpacker unpacker = Pack200.newUnpacker();
        unpacker.unpack(f, jostream);
        jostream.close();

        // delete pack file as its no longer needed
        f.delete();
    }

    /**
     * Retrieves a jar files input stream. This method exists primarily to fix
     * an Opera hang in getInputStream
     * 
     * @param urlconnection
     *            connection to get input stream from
     * @return InputStream or null if not possible
     */
    protected InputStream getJarInputStream(final String currentFile, final URLConnection urlconnection) throws Exception {
        final InputStream[] is = new InputStream[1];

        // try to get the input stream 3 times.
        // Wait at most 5 seconds before interrupting the thread
        for (int j = 0; j < 3 && is[0] == null; j++) {
            Thread t = new Thread() {
                public void run() {
                    try {
                        is[0] = urlconnection.getInputStream();
                    } catch (IOException e) {
                        /* ignored */
                    }
                }
            };
            t.setName("JarInputStreamThread");
            t.start();

            int iterationCount = 0;
            while (is[0] == null && iterationCount++ < 5) {
                try {
                    t.join(1000);
                } catch (InterruptedException inte) {
                    /* ignored */
                }
            }

            if (is[0] == null) {
                try {
                    t.interrupt();
                    t.join();
                } catch (InterruptedException inte) {
                    /* ignored */
                }
            }
        }

        if (is[0] == null) {
            throw new Exception("Unable to get input stream for " + currentFile);
        }

        return is[0];
    }

    /**
     * Get file name portion of URL.
     * 
     * @param url
     *            Get file name from this url
     * @return file name as string
     */
    protected String getFileName(URL url) {
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
    protected String getJarName(URL url) {
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

}
