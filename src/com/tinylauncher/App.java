package com.tinylauncher;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.EventQueue;
import java.awt.Font;
import java.awt.Image;
import java.awt.Insets;
import java.awt.Toolkit;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.io.PrintStream;

import javax.swing.BorderFactory;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JProgressBar;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.SwingConstants;
import javax.swing.SwingUtilities;
import javax.swing.text.DefaultCaret;

import com.tinylauncher.gui.BackgroundPanel;
import com.tinylauncher.tasks.ForgeInstall;
import com.tinylauncher.tasks.GameUpdate;
import com.tinylauncher.tasks.LaunchMinecraft;

public class App extends JFrame {

	/** Serial version */
	private static final long serialVersionUID = 8845394161717273278L;

	/** Application Version */
	private static final String version = "1.0-dev";

	/** Window elements */
	private static JLabel lblTask;
	private static JProgressBar progressBar;
	private static JLabel lblAction;
	private static JLabel lblProgress;
	private static JTextArea txtConsole;
	private static JScrollPane scrollConsole;

	/** Application parameters */
	/* TODO Load this from an XML file */
	final static String VERSION_FORGE = "latest";
	final static String VERSION_MINECRAFT = "1.4.6";
	final static String WINDOW_NAME = "MinecraftForge";
	final static Dimension WINDOW_SIZE = new Dimension(800, 600);
	final static Boolean IS_MAXIMIZED = false;
	final static Image WINDOW_ICON = Toolkit.getDefaultToolkit().getImage(App.class.getResource("/com/tinylauncher/gui/icon.png"));

	/** Session variables */
	private static String strUsername;
	private static String strSessionID;

	/**
	 * Main entry point for the program
	 * 
	 * @param args
	 *            command-line arguments (none implemented)
	 */
	public static void main(final String[] args) {

		redirectSystemStreams();

		/* Schedule GUI Creation on the EDT */
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				App app = new App();
				app.setVisible(true);
			}
		});

		App.resetProgress();
		App.logLn("Running Game Update");
		GameUpdate.taskStart();
		App.logLn("Running Forge Install");
		ForgeInstall.taskStart();
		App.logLn("Launching Minecraft");
		LaunchMinecraft.taskStart();

		App.showConsole();
		App.resetProgress();
		App.setTask("");

	}

	/**
	 * Constructor
	 */
	public App() {

		/* Setup the background image */
		Image bkgd = Toolkit.getDefaultToolkit().getImage(App.class.getResource("/com/tinylauncher/gui/background.png"));
		BackgroundPanel backgroundPane = new BackgroundPanel(bkgd, BackgroundPanel.TILED);
		backgroundPane.setTransparentAdd(false);
		setContentPane(backgroundPane);

		/* Setup the application frame */
		setIconImage(bkgd);
		setTitle(WINDOW_NAME);
		setResizable(false);
		setSize(new Dimension(870, 518));
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		setLocationRelativeTo(null);
		getContentPane().setLayout(null);

		/* Setup form fonts */
		Font fontTask = getFont(Font.BOLD, 40);
		Font fontBody = getFont(Font.PLAIN, 16);
		Font fontVer = getFont(Font.PLAIN, 12);

		/* Add the main status label */
		lblTask = new JLabel("Initializing...");
		lblTask.setHorizontalAlignment(SwingConstants.CENTER);
		lblTask.setFont(fontTask);
		lblTask.setBounds(10, 57, 844, 72);
		lblTask.setForeground(Color.LIGHT_GRAY);
		getContentPane().add(lblTask);

		/* Add the action label */
		lblAction = new JLabel("");
		lblAction.setHorizontalAlignment(SwingConstants.CENTER);
		lblAction.setFont(fontBody);
		lblAction.setBounds(10, 260, 844, 37);
		lblAction.setForeground(Color.LIGHT_GRAY);
		getContentPane().add(lblAction);

		/* Add the progress label */
		lblProgress = new JLabel("");
		lblProgress.setHorizontalAlignment(SwingConstants.CENTER);
		lblProgress.setFont(fontBody);
		lblProgress.setBounds(10, 292, 844, 37);
		lblProgress.setForeground(Color.LIGHT_GRAY);
		getContentPane().add(lblProgress);

		/* Add the progress bar */
		progressBar = new JProgressBar();
		progressBar.setBounds(110, 340, 644, 14);
		progressBar.setBorderPainted(false);
		progressBar.setForeground(Color.GREEN);
		progressBar.setBackground(Color.BLACK);
		getContentPane().add(progressBar);

		/* Add the version info */
		JLabel lblTinylauncherdev = new JLabel("TinyLauncher " + version);
		lblTinylauncherdev.setHorizontalAlignment(SwingConstants.RIGHT);
		lblTinylauncherdev.setFont(fontVer);
		lblTinylauncherdev.setBounds(671, 455, 183, 24);
		lblTinylauncherdev.setForeground(Color.LIGHT_GRAY);
		getContentPane().add(lblTinylauncherdev);

		/* Create the console */
		txtConsole = new JTextArea();
		txtConsole.setBackground(Color.BLACK);
		txtConsole.setForeground(Color.GREEN);
		txtConsole.setFont(new Font("monospaced", Font.PLAIN, 12));
		txtConsole.setLineWrap(true);
		txtConsole.setEditable(false);
		txtConsole.setBorder(BorderFactory.createMatteBorder(0, 0, 1, 0, Color.LIGHT_GRAY));
		txtConsole.setMargin(new Insets(10, 10, 10, 10));
		DefaultCaret caret = (DefaultCaret) txtConsole.getCaret();
		caret.setUpdatePolicy(DefaultCaret.ALWAYS_UPDATE);
		scrollConsole = new JScrollPane(txtConsole);
		scrollConsole.setBounds(0, 0, 864, 400);
		scrollConsole.setBorder(BorderFactory.createMatteBorder(0, 0, 2, 0, Color.GRAY));
		scrollConsole.setVisible(false);
		getContentPane().add(scrollConsole);

	}

	/**
	 * Shows the console and hides the Task/Action/Progress elements
	 */
	private static void showConsole() {
		SwingUtilities.invokeLater(new Runnable() {
			public void run() {
				lblTask.setVisible(false);
				lblAction.setVisible(false);
				lblProgress.setVisible(false);
				progressBar.setVisible(false);
				scrollConsole.setVisible(true);
			}
		});
	}

	/**
	 * Shows the application Task/Action/Progress elements and hides the console
	 */
	private static void hideConsole() {
		SwingUtilities.invokeLater(new Runnable() {
			public void run() {
				lblTask.setVisible(true);
				lblAction.setVisible(true);
				lblProgress.setVisible(true);
				progressBar.setVisible(true);
				scrollConsole.setVisible(false);
			}
		});
	}

	/**
	 * Gets the required forge version for this application
	 * 
	 * @return String containing Forge version number
	 */
	public static String getForgeVersion() {
		return VERSION_FORGE;
	}

	/**
	 * Gets the required Minecraft version for this application
	 * 
	 * @return String containing Minecraft version number
	 */
	public static String getMinecraftVersion() {
		return VERSION_MINECRAFT;
	}

	/**
	 * Gets the base directory for all files
	 * 
	 * @return String containing base directory folder name
	 */
	public static String getBaseDir() {
		return "minecraft/";
	}

	public static String getMinecraftBin() {
		return getBaseDir() + "bin";
	}

	/**
	 * Gets the current players Username
	 * 
	 * @return String case correct Username from Mojang, null if no
	 *         authenticated user
	 */
	public static String getUsername() {
		return strUsername;
	}

	/**
	 * Gets the current players SessionID from Mojang
	 * 
	 * @return session ID from Mojang, null if no authenticated user
	 */
	public static String getSessionID() {
		return strSessionID;
	}

	/**
	 * Gets the initial window size specified in the configuration file
	 * 
	 * @return Dimension window size
	 */
	public static Dimension getWindowSize() {
		return WINDOW_SIZE;
	}

	/**
	 * Gets the title for Application windows
	 * 
	 * @return String window title
	 */
	public static String getWindowName() {
		return WINDOW_NAME;
	}

	/**
	 * Should run Maximized
	 * 
	 * @return Boolean true if run maximized
	 */
	public static Boolean runMaximized() {
		return IS_MAXIMIZED;
	}

	/**
	 * Returns the Image to be used for window Icons
	 * 
	 * @return Image icon
	 */
	public static Image getIcon() {
		return WINDOW_ICON;
	}

	/**
	 * Sets the current task description and resets action and progress
	 * 
	 * @param status
	 *            String containing task title
	 */
	public static void setTask(final String status) {
		SwingUtilities.invokeLater(new Runnable() {
			public void run() {
				lblTask.setText(status);
				lblAction.setText("");
			}
		});
	}

	/**
	 * Sets the current action label and resets progress
	 * 
	 * @param status
	 *            String with current action title
	 */
	public static void setAction(final String status) {
		SwingUtilities.invokeLater(new Runnable() {
			public void run() {
				lblAction.setText(status);
				resetProgress();
			}
		});
	}

	/**
	 * Sets progress label and progress bar
	 * 
	 * @param status
	 *            String with progress
	 * @param progress
	 *            Int containing progress (0-100)
	 */
	public static void setProgress(final String status, final int progress) {
		SwingUtilities.invokeLater(new Runnable() {
			public void run() {
				lblProgress.setText(status);
				if (progress >= 0 && progress <= 100) {
					progressBar.setValue(progress);
					progressBar.setVisible(true);
				} else {
					progressBar.setValue(0);
					progressBar.setVisible(false);
				}
			}
		});
	}

	/**
	 * Resets progress indication to zero and hides the progress bar
	 */
	public static void resetProgress() {
		setProgress("", -1);
	}

	/**
	 * Simple handler for fatal application errors
	 * 
	 * @param message String message to show user
	 */
	public static void fatalError(final String message) {
		JOptionPane.showMessageDialog(null, "Fatal Error: " + message);
		System.exit(0);
	}

	/**
	 * Returns the custom Minecraft font, or reverts to a standard font if
	 * there's an error
	 * 
	 * @param style
	 *            Font.BOLD, Font.PLAIN
	 * @param size
	 *            text size
	 * @return Font
	 */
	public static Font getFont(int style, int size) {
		Font font = null;
		String fName = "/com/tinylauncher/gui/minecraft.ttf";

		try {
			InputStream is = App.class.getResourceAsStream(fName);
			Font fontBase = Font.createFont(Font.TRUETYPE_FONT, is);
			font = fontBase.deriveFont(style, size);
		} catch (Exception ex) {
			ex.printStackTrace();
			System.err.println(fName + " not loaded. Using monospaced font.");
			font = new Font("monospaced", style, size);
		}

		return font;
	}

	/**
	 * Outputs a string to the console log
	 * 
	 * @param text String to output
	 */
	public static void log(final String text) {
		SwingUtilities.invokeLater(new Runnable() {
			public void run() {
				txtConsole.append(text);
			}
		});
	}

	/**
	 * Outputs a line to the console log
	 * 
	 * @param text String to output
	 */
	public static void logLn(String text) {
		log(text + "\n");
	}

	/**
	 * Redirects system streams (Sysout and Syserr) to our custom console
	 */
	public static void redirectSystemStreams() {
		OutputStream out = new OutputStream() {
			@Override
			public void write(int b) throws IOException {
				log(String.valueOf((char) b));
			}

			@Override
			public void write(byte[] b, int off, int len) throws IOException {
				log(new String(b, off, len));
			}

			@Override
			public void write(byte[] b) throws IOException {
				write(b, 0, b.length);
			}
		};

		System.setOut(new PrintStream(out, true));
		System.setErr(new PrintStream(out, true));
	}

}