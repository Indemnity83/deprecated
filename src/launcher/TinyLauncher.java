package launcher;

import javax.swing.*;
import javax.swing.text.DefaultCaret;

import java.awt.*;
import java.io.*;
import java.lang.reflect.Constructor;
import java.net.*;
import java.security.cert.Certificate;
import java.util.Enumeration;
import java.util.StringTokenizer;
import java.util.jar.JarEntry;
import java.util.jar.JarFile;
import java.util.jar.JarOutputStream;
import java.util.jar.Pack200;


public class TinyLauncher {
    protected static String[] session = new String[4];
    protected static JFrame frame;
    protected static String OS = System.getProperty("os.name").toLowerCase();
    protected static JTextArea console = new JTextArea();
    protected static URL[] urlList;
   
    /** whether a fatal error occurred */
    protected static boolean fatalError;

    /** fatal error that occurred */
    protected static String fatalErrorDescription;
    
    /** current size of download in bytes */
    protected static int currentSizeDownload;    
    
    /** total size of download in bytes */
    protected static int totalSizeDownload;   
    
    /** current size of extracted in bytes */
    protected static int currentSizeExtract;

    /** total size of extracted in bytes */
    protected static int totalSizeExtract;    
    
    /** used to calculate length of progress bar */
    protected static int percentage;	
    
    /** String to display as a subtask */
    protected static String subtaskMessage = "";    
    
    private static String state;

    /**
     * Application main
     * 
     * @param args
     * @throws IOException
     */
    public static void main(String[] args) throws IOException {
	frame = new JFrame("Tiny Launcher");
	frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
	frame.setSize(600, 300);
	frame.setLocationRelativeTo(null);

	console.setLineWrap(true);
	console.setFont(new Font("Monospaced",Font.PLAIN,12));
	console.setBackground(Color.black);
	console.setForeground(Color.green);
	console.setEditable(false);
	JScrollPane scrollConsole = new JScrollPane(console);	
	DefaultCaret caret = (DefaultCaret)console.getCaret();
	caret.setUpdatePolicy(DefaultCaret.ALWAYS_UPDATE);
	frame.add(scrollConsole);
	
	frame.setVisible(true);
	
	
	
	getUpdates();

	/*
	 * Ask the user for credentials until the session is validated or they
	 * click cancel
	 */
	Boolean isSessionOK;
	do {
	    isSessionOK = getUserSession();
	} while (isSessionOK != null && !isSessionOK);

	if (isSessionOK != null && isSessionOK) {
	    getMinecraft();
	    installJarMods();
	    launchGame();
	}
    }

    /**
     * Check for any updates to mods. Ask user to update, or continue with
     * current version.
     */
    public static void getUpdates() {
	// TODO: Implementation
    }
    
    /**
     * Reads list of jars to download and adds the urls to urlList also finds
     * out which OS you are on and adds appropriate native jar to the urlList
     * 
     * @throws MalformedURLException 
     */
    protected static void loadJarURLs() throws MalformedURLException {

	// jars to load
	String jarList = "lwjgl.jar, jinput.jar, lwjgl_util.jar, minecraft.jar";
	StringTokenizer jar = new StringTokenizer(jarList, ", ");
	int jarCount = jar.countTokens() + 1;
	urlList = new URL[jarCount];
	URL path = new URL("https://s3.amazonaws.com/MinecraftDownload/");

	// set jars urls
	for (int i = 0; i < jarCount - 1; i++) {
	    urlList[i] = new URL(path, jar.nextToken());
	}

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

	if (nativeJar == null) {
	    fatalErrorOccured("no lwjgl natives files found");
	} else {
	    urlList[jarCount - 1] = new URL(path, nativeJar);
	}
    }

    private static boolean debugMode;
    
    /**
     * Will download the jars from the server using the list of urls in urlList,
     * while at the same time updating progress bar
     * 
     * @param path
     *            location of the directory to save to
     * @throws Exception
     *             if download fails
     */
    protected static void downloadJars(String path) throws Exception {

	//state = STATE_DOWNLOADING;

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
	    debug_sleep(2000);

	    urlconnection = urlList[i].openConnection();

	    String currentFile = getFileName(urlList[i]);
	    InputStream inputstream = getJarInputStream(currentFile,
		    urlconnection);
	    FileOutputStream fos = new FileOutputStream(path + currentFile);

	    int bufferSize;
	    long downloadStartTime = System.currentTimeMillis();
	    String downloadSpeedMessage = "";
	    
	    int cpos = console.getCaretPosition();
	    console.append("Retrieving: " + currentFile + " ");
	    while ((bufferSize = inputstream.read(buffer, 0, buffer.length)) != -1) {
		debug_sleep(10);
		fos.write(buffer, 0, bufferSize);
		currentSizeDownload += bufferSize;
		percentage = initialPercentage
			+ ((currentSizeDownload * 45) / totalSizeDownload);
		subtaskMessage = "Retrieving: " + currentFile + " "
			+ ((currentSizeDownload * 100) / totalSizeDownload)
			+ "%";

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
		console.setText(console.getText(0,cpos));
		console.append(subtaskMessage);
	    }
	    console.setText(console.getText(0,cpos));
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
    protected static void extractJars(String path) throws Exception {
	//state = STATE_EXTRACTING_PACKAGES;

	float increment = (float) 10.0 / urlList.length;
	// extract all lzma and pack.lzma files
	
	
	for (int i = 0; i < urlList.length; i++) {
	    percentage = 55 + (int) (increment * (i + 1));
	    String filename = getFileName(urlList[i]);

	    if (filename.endsWith(".pack.lzma")) {
		subtaskMessage = "Extracting: " + filename + " to "
			+ filename.replaceAll(".lzma", "");
		console.append(subtaskMessage + "\n");
		debug_sleep(1000);
		extractLZMA(path + filename,
			path + filename.replaceAll(".lzma", ""));

		subtaskMessage = "Extracting: "
			+ filename.replaceAll(".lzma", "") + " to "
			+ filename.replaceAll(".pack.lzma", "");
		console.append(subtaskMessage + "\n");
		debug_sleep(1000);
		extractPack(path + filename.replaceAll(".lzma", ""), path
			+ filename.replaceAll(".pack.lzma", ""));
	    } else if (filename.endsWith(".pack")) {
		subtaskMessage = "Extracting: " + filename + " to "
			+ filename.replace(".pack", "");
		console.append(subtaskMessage + "\n");
		debug_sleep(1000);
		extractPack(path + filename,
			path + filename.replace(".pack", ""));
	    } else if (filename.endsWith(".lzma")) {
		subtaskMessage = "Extracting: " + filename + " to "
			+ filename.replace(".lzma", "");
		console.append(subtaskMessage + "\n");
		debug_sleep(1000);
		extractLZMA(path + filename,
			path + filename.replace(".lzma", ""));
	    }

	}
    }    
    
    /**
     * This method will extract all file from the native jar and extract them to
     * the subdirectory called "natives" in the local path, will also check to
     * see if the native jar files is signed properly
     * 
     * @param path
     *            base folder containing all downloaded jars
     * @throws Exception
     *             if it fails to extract files
     */
    protected static void extractNatives(String path) throws Exception {

	//state = STATE_EXTRACTING_PACKAGES;

	int initialPercentage = percentage;

	// get name of jar file with natives from urlList, it will be the last
	// url
	String nativeJar = getJarName(urlList[urlList.length - 1]);

	// create native folder
	File nativeFolder = new File(path + "natives");
	if (!nativeFolder.exists()) {
	    nativeFolder.mkdir();
	}

	// open jar file
	JarFile jarFile = new JarFile(path + nativeJar, true);

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

	// reset point to begining by getting list of file again
	entities = jarFile.entries();

	// extract all files from the jar
	while (entities.hasMoreElements()) {
	    JarEntry entry = (JarEntry) entities.nextElement();

	    // skip directories and anything in directories
	    // conveniently ignores the manifest
	    if (entry.isDirectory() || entry.getName().indexOf('/') != -1) {
		continue;
	    }

	    // check if native file already exists if so delete it to make room
	    // for new one
	    // useful when using the reload button on the browser
	    File f = new File(path + "natives" + File.separator
		    + entry.getName());
	    if (f.exists()) {
		if (!f.delete()) {
		    continue; // unable to delete file, it is in use, skip
			      // extracting it
		}
	    }

	    debug_sleep(1000);

	    InputStream in = jarFile.getInputStream(jarFile.getEntry(entry
		    .getName()));
	    OutputStream out = new FileOutputStream(path + "natives"
		    + File.separator + entry.getName());

	    int bufferSize;
	    byte buffer[] = new byte[65536];

	    while ((bufferSize = in.read(buffer, 0, buffer.length)) != -1) {
		debug_sleep(10);
		out.write(buffer, 0, bufferSize);
		currentSizeExtract += bufferSize;

		// update progress bar
		percentage = initialPercentage
			+ ((currentSizeExtract * 20) / totalSizeExtract);
		subtaskMessage = "Extracting: " + entry.getName() + " "
			+ ((currentSizeExtract * 100) / totalSizeExtract) + "%";
	    }

	    in.close();
	    out.close();
	}
	subtaskMessage = "";

	jarFile.close();

	// delete native jar as it is no longer needed
	File f = new File(path + nativeJar);
	f.delete();
    }    

    /**
     * Sets the state of the loaded and prints some debug information
     * 
     * @param error
     *            Error message to print
     */
    protected static void fatalErrorOccured(String error) {
	fatalError = true;
	fatalErrorDescription = "Fatal error occured (" + state + "): " + error;
	console.append(fatalErrorDescription);

    }

    /**
     * Get the required files to run Minecraft. Verify we're running the
     * expected versions
     * 
     * TODO: Is there a way to insure we have the right version?
     */
    public static void getMinecraft() {

	// Create minecraft directory
	String path = "bin/";
	File minecraftBin = new File(path);
	if (!minecraftBin.exists()) {
	    minecraftBin.mkdirs();
	}	
	
	try {
	    loadJarURLs();		// parse the urls for the jars into the URL list
	    downloadJars(path);
	    extractJars(path);
	    extractNatives(path);
	} catch (MalformedURLException e) {
	    fatalErrorOccured("URL error: " + e.getMessage());
	} catch (Exception e) {
	    // TODO Auto-generated catch block
	    fatalErrorOccured("Fatal error: " + e.getMessage());
	    e.printStackTrace();
	}

    }

    /**
     * Create a backup of the main jar. Extract all files (in order) from the
     * <i>instmods</i> file in the parent folder. Add everything that was
     * extracted into the jar. Delete the META-INF from the jar and clean up any
     * temp folders/files.
     */
    public static void installJarMods() {
	// TODO: Implementation
    }

    /**
     * Opens a dialog for the user to enter credentials. Validates with the
     * server and checks versions.
     * 
     * @return Results. true=success, false=fail, null=cancel
     * @throws IOException
     */
    public static Boolean getUserSession() throws IOException {
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

	int a = JOptionPane.showConfirmDialog(frame, panel,
		"Login to Minecraft", JOptionPane.OK_CANCEL_OPTION,
		JOptionPane.QUESTION_MESSAGE);

	/*
	 * Validate credentials with login server
	 */
	if (a == JOptionPane.OK_OPTION) {
	    // Get a session from Minecraft login servers
	    session[0] = userField.getText();
	    URL url = null;
	    InputStream is = null;

	    try {
		url = new URL(
			String.format(
				"https://login.minecraft.net/?user=%s&password=%s&version=14",
				userField.getText(),
				new String(passField.getPassword())));

		// Read the result
		is = url.openStream();
		loginReturn = new BufferedReader(new InputStreamReader(is))
			.readLine();
	    } catch (MalformedURLException e) {
		JOptionPane.showMessageDialog(frame,
			"URL Panic (Malformed URL)! " + e.getMessage());
		return null;
	    } catch (IOException e) {
		JOptionPane.showMessageDialog(frame,
			"URL Panic (IO Exception)! " + e.getMessage());
		return null;
	    } finally {
		// close the steam
		if (is != null)
		    is.close();
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
	    if( loginReturn.toLowerCase() == "bad login" ) {
		console.append("Bad login \n");
		JOptionPane.showMessageDialog(frame,
			"Invalid username or password.");
		return false;
	    } else if (loginReturn.toLowerCase() = "old version" ) {
		console.append("Old version \n");
		JOptionPane.showMessageDialog(frame,
			"Launcher outdated, please update.");
		return false;
	    } else {
		console.append("Unexpected error \n");
		JOptionPane.showMessageDialog(frame, "Login failed!: "
			+ loginReturn);
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
     * Launch Minecraft
     */
    public static void launchGame() {
	// Lets do it!
	console.append("Launching Minecraft ... \n");
	session[2] = "Oakhart"; // Window Name
	session[3] = "max"; // Start maximized
	MultiMCLauncher.main(session);
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
    protected static void extractLZMA(String in, String out) throws Exception {

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
    protected static void extractPack(String in, String out) throws Exception {
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
    protected static InputStream getJarInputStream(final String currentFile,
	    final URLConnection urlconnection) throws Exception {
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
    protected static String getFileName(URL url) {
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
    protected static String getJarName(URL url) {
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
     * Utility method for sleeping Will only really sleep if debug has been
     * enabled
     * 
     * @param ms
     *            milliseconds to sleep
     */
    protected static void debug_sleep(long ms) {
	if (debugMode) {
	    sleep(ms);
	}
    }
    
    /**
     * Utility method for sleeping
     * 
     * @param ms
     *            milliseconds to sleep
     */
    protected static void sleep(long ms) {
	try {
	    Thread.sleep(ms);
	} catch (Exception e) {
	    /* ignored */
	}
    }

}
