package com.tinylauncher.tasks;

import java.io.File;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

import com.tinylauncher.App;
import com.tinylauncher.Util;

public class ForgeInstall {

    public static void taskStart() {

        App.setTask("Updating Forge");

        List<URL> jarURLs = new ArrayList<URL>();

        /* Build the URL list */
        App.setAction("Initializing");
        try {
            URL forgeURL = new URL("http://files.minecraftforge.net/minecraftforge/");
            jarURLs.add(new URL(forgeURL, "minecraftforge-universal-" + App.getForgeVersion() + ".zip"));
        } catch (MalformedURLException e) {
            App.fatalError(e.getMessage());
        }

        App.setAction("Creating directories");
        File tempDir = new File(App.getBaseDir() + "temp");
        tempDir.mkdirs();

        App.setAction("Downloading package");
        for (URL jarURL : jarURLs) {
            File file = new File(tempDir, Util.getFileName(jarURL));
            Util.download(jarURL, file);
        }
        
    }

}
