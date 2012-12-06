import javax.swing.*;
import java.awt.*;
import java.io.*;
import java.net.*;


public class TinyLauncher
{
	protected static String[] session = new String[4];
	protected static JFrame frame;

    public static void main(String[] args)
	{
		frame = new JFrame("Tiny Launcher");
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frame.setSize(600,300);
		frame.setLocationRelativeTo(null);
		frame.setVisible(true);
		
		getUpdates();
		
		Boolean isSessionOK = false;
		do
		{
		    isSessionOK = getUserSession();			
		} while( !isSessionOK || isSessionOK == null );
		
		if( isSessionOK ) 
		{		
			JOptionPane.showMessageDialog(frame, "Login Successful!");
		    getJar();		
		    installJarMods();		
		    launchGame();		
		}
						
	}
	
	public static void getUpdates() {
		// TODO: Figure this out! Do we check against our specific server? Probably better to 
		// check against the jenkins system for the latest tagged version. 
		//
		// Check out http://main.brothersklaus.com:8080/job/Oakheart/lastSuccessfulBuild/api/json?pretty=true
	}
	
	public static void getJar()
	{
	    // Get latest jar!
		// https://s3.amazonaws.com/MinecraftDownload/minecraft.jar
		// TODO: Is there a way to insure we have the right version?
	}
	
	public static void installJarMods()
	{
	    // check if we already have a backup jar, if we do just return
		// Create a backup of the main jar
		// Extract all files (in order) from the instmods file in the parent folder
		// Add everything that was extracted into the jar
		// Delete the META-INF from the jar
		// Clean up any temp folders/files
	}
	
	public static Boolean getUserSession()
	{
	    // Prompt user for credentials
		JPanel panel = new JPanel();
		panel.setLayout(new GridLayout(4,1));
		String loginReturn = null;
		
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
		
		if( a == JOptionPane.OK_OPTION )
		{
		    // Get a session from Minecraft login servers
			session[0] = userField.getText();
			URL url = null;
			InputStream is = null;
			DataInputStream dis;
			
			try 
			{
			    url = new URL("https://login.minecraft.net/?user=" + userField.getText() + "&password=" + new String(passField.getPassword()) + "&version=14");
				is = url.openStream();
				dis = new DataInputStream(new BufferedInputStream(is));
				loginReturn = dis.readLine();
			} 			
			catch( MalformedURLException e ) 
			{
			   JOptionPane.showMessageDialog(frame, "URL Panic (Malformed URL)! " + e.getMessage());
			   return null;
			}
			catch( IOException e ) 
			{
			   JOptionPane.showMessageDialog(frame, "URL Panic (IO Exception)! " + e.getMessage());
			   return null;
			}
		}
		
		
		if( a == JOptionPane.CANCEL_OPTION )
		{
			return null;
		}	

		// Check if our login looks correct
		String[] sessionParts = loginReturn.toLowerCase().split(":");
		if( sessionParts.length > 1 && sessionParts[2].equals(userField.getText().toLowerCase()) )
		{
			// Yay! valid sesison
			return true;
		}
		
		// Something went wrong, lets see if we can figure it out
		switch(loginReturn.toLowerCase()) 
		{
            case "bad login":
				JOptionPane.showMessageDialog(frame, "Invalid username or password.");
				return false;	
			case "old version":
				JOptionPane.showMessageDialog(frame, "Launcher outdated, please update.");
				return false;
		    default:
				JOptionPane.showMessageDialog(frame, "Login failed!: " + loginReturn);
				return false;			
		}
	}
	
	public static void launchGame()
	{
	    // Lets do it!
		session[2] = "Oakhart";      // Window Name
		session[3] = "max";          // Start maximized
		//MultiMCLauncher launcher = new MultiMCLauncher();
		//launcher.main(session);
	}
	
}