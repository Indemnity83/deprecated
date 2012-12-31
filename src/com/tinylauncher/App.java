package com.tinylauncher;

import java.awt.Dimension;
import java.awt.EventQueue;
import java.awt.Font;
import java.awt.Image;
import java.awt.Toolkit;

import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JProgressBar;
import javax.swing.SwingConstants;
import javax.swing.SwingUtilities;
import javax.swing.UIManager;

import com.tinylauncher.tasks.ForgeInstall;
import com.tinylauncher.tasks.GameUpdate;
import com.tinylauncher.tasks.LaunchMinecraft;

public class App extends JFrame {

    /** Serial version */
    private static final long   serialVersionUID  = 8845394161717273278L;

    /** Application Version */
    private static final String version           = "1.0-dev";

    /** Window elements */
    private static JLabel       lblTask;
    private static JProgressBar progressBar;
    private static JLabel       lblAction;
    private static JLabel       lblProgress;

    /** Application parameters */
    /* TODO Load this from an XML file */
    final static String                VERSION_FORGE     = "latest";
    final static String                VERSION_MINECRAFT = "1.4.6";
    final static String                WINDOW_NAME       = "MinecraftForge";
    final static Dimension             WINDOW_SIZE       = new Dimension(600, 300);
    final static Boolean               IS_MAXIMIZED      = true;            
    final static Image                 WINDOW_ICON       = Toolkit.getDefaultToolkit().getImage(App.class.getResource("/com/tinylauncher/gui/icon.png"));
    
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

        /* Schedule GUI Creation on the EDT */
        EventQueue.invokeLater(new Runnable() {

            @Override
            public void run() {
                try {
                    UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
                } catch (Exception e) {/* Ignore */
                }

                App app = new App();
                app.setVisible(true);
            }
        });

        App.resetProgress();
        GameUpdate.taskStart();
        ForgeInstall.taskStart();
        
        LaunchMinecraft.taskStart();
        
        App.resetProgress();
        App.setTask("Done");

    }

    /**
     * Constructor
     */
    public App() {

        /* Setup the application frame */
        setIconImage(WINDOW_ICON);
        setTitle(WINDOW_NAME);
        setResizable(false);
        setSize(new Dimension(870, 518));
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        getContentPane().setLayout(null);

        /* Setup form fonts */
        /* TODO Pick prettier fonts */
        Font fontTask = new Font("Monospaced", Font.PLAIN, 50);
        Font fontBody = new Font("Monospaced", Font.PLAIN, 20);
        Font fontVer = new Font("Monospaced", Font.PLAIN, 12);

        /* Add the main status label */
        lblTask = new JLabel("Initializing...");
        lblTask.setHorizontalAlignment(SwingConstants.CENTER);
        lblTask.setFont(fontTask);
        lblTask.setBounds(10, 57, 844, 72);
        getContentPane().add(lblTask);

        /* Add the action label */
        lblAction = new JLabel("Initializing");
        lblAction.setHorizontalAlignment(SwingConstants.CENTER);
        lblAction.setFont(fontBody);
        lblAction.setBounds(10, 244, 844, 37);
        getContentPane().add(lblAction);
        
        /* Add the progress label */
        lblProgress = new JLabel("Initializing");
        lblProgress.setHorizontalAlignment(SwingConstants.CENTER);
        lblProgress.setFont(fontBody);
        lblProgress.setBounds(10, 292, 844, 37);
        getContentPane().add(lblProgress);

        /* Add the progress bar */
        progressBar = new JProgressBar();
        progressBar.setBounds(110, 340, 644, 14);
        getContentPane().add(progressBar);

        /* Add the version info */
        JLabel lblTinylauncherdev = new JLabel("TinyLauncher " + version);
        lblTinylauncherdev.setHorizontalAlignment(SwingConstants.RIGHT);
        lblTinylauncherdev.setFont(fontVer);
        lblTinylauncherdev.setBounds(671, 455, 183, 24);
        getContentPane().add(lblTinylauncherdev);

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
     * @return String case correct Username from Mojang, null if no authenticated user
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
                if(progress >= 0 && progress <= 100) {
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
     * Resets progress indication to zero
     */
    public static void resetProgress() {
        setProgress("", -1);
    }

    public static void fatalError(final String message) {
        JOptionPane.showMessageDialog(null, "Fatal Error: " + message);
        System.exit(0);
    }



}