package src.launcher;

import javax.swing.*;
import java.awt.*;
import java.io.*;
import java.net.*;

public class TinyLauncher
  {
  protected static String[] session = new String[4];
  protected static JFrame   frame;

  /*****************************************************************************
  * main */
  /**
  * Application entrance.
  * 
  * @param args
  * @throws IOException
  *****************************************************************************/
  public static void main(String[] args) throws IOException
    {
    frame = new JFrame("Tiny Launcher");
    frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    frame.setSize(600, 300);
    frame.setLocationRelativeTo(null);
    frame.setVisible(true);

    getUpdates();

    /*-------------------------------*/
    /* Ask user for credentials      */
    /* until they cancel or succeed. */
    /*-------------------------------*/
    Boolean isSessionOK;
    do
      {
      isSessionOK = getUserSession();
      }
    while (isSessionOK != null && !isSessionOK);

    if (isSessionOK != null && isSessionOK)
      {
      JOptionPane.showMessageDialog(frame, "Login Successful!");
      getJar();
      installJarMods();
      launchGame();
      }
    }

  /*****************************************************************************
  * getUpdates */
  /**
  * Check for any updates to mods. Ask user to update, or continue with current
  * version. 
  *****************************************************************************/
  public static void getUpdates()
    {
    // TODO: Figure this out! Do we check against our specific server? Probably
    // better to
    // check against the jenkins system for the latest tagged version.
    //
    // Check out
    // http://main.brothersklaus.com:8080/job/Oakheart/lastSuccessfulBuild/api/json?pretty=true
    }

  /*****************************************************************************
  * getJar */
  /**
  * Check for the latest jar. Ask user to update, or continue with current
  * version.
  *****************************************************************************/
  public static void getJar()
    {
    // Get latest jar!
    // https://s3.amazonaws.com/MinecraftDownload/minecraft.jar
    // TODO: Is there a way to insure we have the right version?
    }

  /*****************************************************************************
  * installJarMods */
  /**
  * Create a backup of the main jar. Extract all files (in order) from the
  * <i>instmods</i> file in the parent folder. Add everything that was extracted
  * into the jar.
  * Delete the META-INF from the jar and clean up any temp folders/files.
  *****************************************************************************/
  public static void installJarMods()
    {
    //TODO implementation.
    // check if we already have a backup jar, if we do just return
    }

  /*****************************************************************************
  * getUserSession */
  /**
  * Opens a dialog for the user to enter credentials. Validates with the server
  * and checks versions.
  * 
  * @return  Results. true=success, false=fail, null=cancel
  * @throws  IOException
  *****************************************************************************/
  public static Boolean getUserSession() throws IOException
    {
    // Prompt user for credentials
    JPanel panel       = new JPanel();
    String loginReturn = null;
    panel.setLayout(new GridLayout(4, 1));

    JLabel         username  = new JLabel("Username");
    JLabel         password  = new JLabel("Password");
    JTextField     userField = new JTextField(12);
    JPasswordField passField = new JPasswordField(12);
    
    passField.setEchoChar('*');

    panel.add(username);
    panel.add(userField);
    panel.add(password);
    panel.add(passField);

    int a = JOptionPane.showConfirmDialog(frame, panel, "Login to Minecraft",
        JOptionPane.OK_CANCEL_OPTION, JOptionPane.QUESTION_MESSAGE);

    /*-----------------------------------------*/
    /* OK - Validate credentials with servers. */
    /*-----------------------------------------*/
    if (a == JOptionPane.OK_OPTION)
      {
      // Get a session from Minecraft login servers
      session[0]      = userField.getText();
      URL         url = null;
      InputStream is  = null;
      
      try
        {
        url = new URL(String.format(
            "https://login.minecraft.net/?user=%s&password=%s&version=14",
            userField.getText(), new String(passField.getPassword())));

        /*------------------*/
        /* Read the result. */
        /*------------------*/
        is = url.openStream();
        loginReturn = new BufferedReader(new InputStreamReader(is)).readLine();
        }
      catch (MalformedURLException e)
        {
        JOptionPane.showMessageDialog(frame,
            "URL Panic (Malformed URL)! " + e.getMessage());
        return null;
        }
      catch (IOException e)
        {
        JOptionPane.showMessageDialog(frame,
            "URL Panic (IO Exception)! " + e.getMessage());
        return null;
        }
      finally
        {
        /*-------------------*/
        /* Close the stream. */
        /*-------------------*/
        if (is != null)
          is.close();
        }
      }

    /*-------------------------*/
    /* Cancel - User canceled. */
    /*-------------------------*/
    if (a == JOptionPane.CANCEL_OPTION) { return null; }

    // Check if our login looks correct
    if (loginReturn.split(":").length == 5)
      {
      // Yay! valid session
      return true;
      }

    // Something went wrong, lets see if we can figure it out
    switch (loginReturn.toLowerCase())
      {
      case "bad login":
        JOptionPane.showMessageDialog(frame, "Invalid username or password.");
        return false;
      case "old version":
        JOptionPane.showMessageDialog(frame,
            "Launcher outdated, please update.");
        return false;
      default:
        JOptionPane.showMessageDialog(frame, "Login failed!: " + loginReturn);
        return false;
      }
    }

  /*****************************************************************************
  * launchGame */
  /**
  * Launches game.
  *****************************************************************************/
  public static void launchGame()
    {
    // Lets do it!
    session[2] = "Oakhart";      // Window Name
    session[3] = "max";          // Start maximized
    // MultiMCLauncher launcher = new MultiMCLauncher();
    // launcher.main(session);
    }

  }
