package com.tinylauncher.gui;

import java.applet.Applet;
import java.awt.Dimension;
import java.awt.Image;
import java.awt.Toolkit;
import java.net.MalformedURLException;
import java.net.URL;

import javax.swing.JFrame;

import com.tinylauncher.App;


import net.minecraft.Launcher;

public class MinecraftFrame extends JFrame {

    private static final long serialVersionUID = 2119539996293442153L;

    protected Launcher        appletWrap       = null;
    protected Dimension       frameDims;
    protected String          frameTitle;
    protected Image           frameIcon;

    public MinecraftFrame() {
        this(App.getWindowName(), App.getIcon(), App.getWindowSize());
    }

    public MinecraftFrame(String title) {
        this(title, App.getIcon(), App.getWindowSize());
    }

    public MinecraftFrame(String title, Image icon, Dimension winSize) {
        frameTitle = title;
        frameDims = winSize;
        frameIcon = icon;
        

        setSize(frameDims);
        setTitle(frameTitle);
        setIconImage(frameIcon);

        setLocationRelativeTo(null);
    }

    public void start(Applet mcApplet, String user, String session, Dimension winSize, boolean maximize) {
        try {
            appletWrap = new Launcher(mcApplet, new URL("http://www.minecraft.net/game"));
        } catch (MalformedURLException ignored) {
        }

        appletWrap.setParameter("username", user);
        appletWrap.setParameter("sessionid", session);
        appletWrap.setParameter("stand-alone", "true");
        mcApplet.setStub(appletWrap);

        add(appletWrap);
        appletWrap.setPreferredSize(frameDims);
        pack();
        
        if (maximize)
            this.setExtendedState(MAXIMIZED_BOTH);

        validate();
        appletWrap.init();
        appletWrap.start();
        setVisible(true);
    }

}
