package src.launcher;

import javax.swing.*;
import java.awt.*;
import java.io.*;
import java.net.*;
import java.nio.channels.Channels;
import java.nio.channels.ReadableByteChannel;

public class TinyLauncher {
    protected static String[] session = new String[4];
    protected static JFrame frame;
    protected static String OS = System.getProperty("os.name").toLowerCase();

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
	    JOptionPane.showMessageDialog(frame, "Login Successful!");
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
     * Get the required files to run Minecraft. Verify we're running the
     * expected versions
     * 
     * TODO: Is there a way to insure we have the right version?
     */
    public static void getMinecraft() {

	File dir = new File("bin");
	dir.mkdir();

	download("lwjgl.jar");
	download("jinput.jar");
	download("lwjgl_util.jar");
	download("minecraft.jar");

	if (isWindows()) {
	    download("windows_natives.jar.lzma");
	}

	if (isMac()) {
	    download("macosx_natives.jar.lzma");
	}

	if (isUnix()) {
	    download("linux_natives.jar.lzma");
	}

	if (isSolaris()) {
	    download("solaris_natives.jar.lzma");
	}

    }

    /**
     * Download file from MinecraftDownload site
     */
    public static boolean download(String filename) {
	FileOutputStream fos = null;

	File f = new File("bin/" + filename);
	if (!f.exists()) {
	    try {
		URL minecraftJar = new URL(
			"https://s3.amazonaws.com/MinecraftDownload/"
				+ filename);
		ReadableByteChannel rbc = Channels.newChannel(minecraftJar
			.openStream());
		fos = new FileOutputStream("bin/" + filename);
		fos.getChannel().transferFrom(rbc, 0, 1 << 24);
	    } catch (MalformedURLException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
	    } catch (IOException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
	    } finally {
		try {
		    fos.close();
		} catch (IOException e) {
		    // TODO Auto-generated catch block
		    e.printStackTrace();
		}
	    }
	}

	return false;
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
		return true;
	    }

	    /*
	     * No valid session, tell the user why
	     */
	    switch (loginReturn.toLowerCase()) {
	    case "bad login":
		JOptionPane.showMessageDialog(frame,
			"Invalid username or password.");
		return false;
	    case "old version":
		JOptionPane.showMessageDialog(frame,
			"Launcher outdated, please update.");
		return false;
	    default:
		JOptionPane.showMessageDialog(frame, "Login failed!: "
			+ loginReturn);
		return false;
	    }
	}

	/*
	 * The user clicked cancel
	 */
	if (a == JOptionPane.CANCEL_OPTION) {
	    return null;
	}
	
	/*
	 * Shouldn't ever end up here, but if we do assume a cancel
	 */
	return null;

    }

    /**
     * Launch Minecraft
     */
    public static void launchGame() {
	// Lets do it!
	session[2] = "Oakhart"; // Window Name
	session[3] = "max"; // Start maximized
	MultiMCLauncher.main(session);
    }

    /**
     * Check if current system appears to be Windows
     * 
     * @return true if system is Windows
     */
    public static boolean isWindows() {
	return (OS.indexOf("win") >= 0);
    }

    /**
     * Check if current system appears to be a Mac
     * 
     * @return true if system is a Mac
     */
    public static boolean isMac() {
	return (OS.indexOf("mac") >= 0);
    }

    /**
     * Check if current system appears to be Linux or Unix
     * 
     * @return true if system is Linux or Unix
     */
    public static boolean isUnix() {
	return (OS.indexOf("nix") >= 0 || OS.indexOf("nux") >= 0 || OS
		.indexOf("aix") > 0);
    }

    /**
     * Check if current system appears to be Solaris
     * 
     * @return true if system is Solaris
     */
    public static boolean isSolaris() {
	return (OS.indexOf("sunos") >= 0);
    }

}
