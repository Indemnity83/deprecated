package com.tinylauncher.gui;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Toolkit;

import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.SwingConstants;
import javax.swing.JProgressBar;
import javax.swing.UIManager;

public class LauncherFrame extends JFrame {
    public LauncherFrame() {
        setIconImage(Toolkit.getDefaultToolkit().getImage(LauncherFrame.class.getResource("/com/tinylauncher/gui/icon.png")));
        setTitle("TinyLauncher");
        setResizable(false);
        setSize(new Dimension(870, 518));
        getContentPane().setLayout(null);
        
        JLabel lblUpdatingMinecraft = new JLabel("Initializing...");
        lblUpdatingMinecraft.setHorizontalAlignment(SwingConstants.CENTER);
        lblUpdatingMinecraft.setFont(new Font("Miriam", Font.BOLD, 40));
        lblUpdatingMinecraft.setBounds(10, 57, 844, 72);
        getContentPane().add(lblUpdatingMinecraft);
        
        JProgressBar progressBar = new JProgressBar();
        progressBar.setBounds(110, 340, 644, 14);
        getContentPane().add(progressBar);
        
        JLabel lblInitializing_1 = new JLabel("Initializing");
        lblInitializing_1.setHorizontalAlignment(SwingConstants.CENTER);
        lblInitializing_1.setFont(new Font("Minecraft", Font.PLAIN, 20));
        lblInitializing_1.setBounds(10, 292, 844, 37);
        getContentPane().add(lblInitializing_1);
        
        JLabel lblInitializing = new JLabel("Initializing");
        lblInitializing.setHorizontalAlignment(SwingConstants.CENTER);
        lblInitializing.setFont(new Font("Minecraft", Font.PLAIN, 20));
        lblInitializing.setBounds(10, 244, 844, 37);
        getContentPane().add(lblInitializing);
        
        JLabel lblTinylauncherdev = new JLabel("Tiny Launcher 1.0-Dev");
        lblTinylauncherdev.setHorizontalAlignment(SwingConstants.RIGHT);
        lblTinylauncherdev.setFont(UIManager.getFont("TextArea.font"));
        lblTinylauncherdev.setBounds(671, 455, 183, 24);
        getContentPane().add(lblTinylauncherdev);
    }

    /**
     * @param args
     */
    public static void main(String[] args) {
        // TODO Auto-generated method stub

    }
}
