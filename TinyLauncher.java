import java.io.File;
import java.io.IOException;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLClassLoader;
import java.util.zip.ZipEntry;
import java.util.zip.ZipException;
import java.util.zip.ZipFile;

public class TinyLauncher
{
	protected String[] session = new String[3];

    public static void main(String[] args)
	{
	    getUserSession();
		getUpdates();
		getJar();
		installJarMods();
		
		// Lets do it!
		session[2] = "Oakhart";      // Window Name
		session[3] = "max";          // Start maximized
		MultiMCLauncher launcher = new MultiMCLauncher(session);
				
	}
	
	public void getUpdates() {
		// TODO: Figure this out! Do we check against our specific server? Probably better to 
		// check against the jenkins system for the latest tagged version. 
		//
		// Check out http://main.brothersklaus.com:8080/job/Oakheart/lastSuccessfulBuild/api/json?pretty=true
	}
	
	public void getJar()
	{
	    // Get latest jar!
		// https://s3.amazonaws.com/MinecraftDownload/minecraft.jar
		// TODO: Is there a way to insure we have the right version?
	}
	
	public void installJarMods()
	{
	    // check if we already have a backup jar, if we do just return
		// Create a backup of the main jar
		// Extract all files (in order) from the instmods file in the parent folder
		// Add everything that was extracted into the jar
		// Delete the META-INF from the jar
		// Clean up any temp folders/files
	}
	
	public String[] getUserSession();
	{
	    // Prompt user for credentials somehow
		// get session via https://login.minecraft.net/?user=%USER%&password=%PASS%&version=14
		// Check that return result is valid
		// Return the username and session string
		// TODO: Make sure we don't expose the password somehow, IE force garbage collection?
		
		session[0] = "Indemnity83";
		session[1] = "Pass";
	}
	
}