package com.tinylauncher.gui;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.GridBagConstraints;
import java.awt.GridBagLayout;
import java.awt.Image;
import java.awt.Insets;
import java.awt.Toolkit;
import java.util.Date;
import java.util.Locale;

import javax.swing.BorderFactory;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.SwingUtilities;
import javax.swing.UIManager;
import javax.swing.text.DefaultCaret;

public class ConsoleFrame extends JFrame {

    protected static final long serialVersionUID = -5583460382815076821L;
    protected Dimension         frameDims;
    protected String            frameTitle;
    protected Image             frameIcon;
    protected JTextArea         textConsole      = null;
    
    /**
     * time format string (yyyy-MM-dd HH:mm:ss.SSS)
     */
    private static final String TIME_FORMAT_STRING = "%1$tF %1$tT.%1$tL";

    public ConsoleFrame() {
        this("Console", Toolkit.getDefaultToolkit().getImage(ConsoleFrame.class.getResource("/com/tinylauncher/gui/icon.png")), new Dimension(600, 300));
    }

    public ConsoleFrame(String title) {
        this(title, Toolkit.getDefaultToolkit().getImage(ConsoleFrame.class.getResource("/com/tinylauncher/gui/icon.png")), new Dimension(600, 300));
    }

    /**
     * Constructor 
     * 
     * @param title
     * @param icon
     * @param winSize
     */
    public ConsoleFrame(String title, Image icon, Dimension winSize) {
        frameTitle = title;
        frameDims = winSize;
        frameIcon = icon;

        setSize(frameDims);
        setTitle(frameTitle);
        setIconImage(frameIcon);

        setLocationRelativeTo(null);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

        getContentPane().setLayout(new GridBagLayout());

        JTextArea textConsole = new JTextArea();
        textConsole.setTabSize(2);
        textConsole.setEditable(false);
        textConsole.setBorder(BorderFactory.createLineBorder(Color.LIGHT_GRAY));

        JScrollPane scrollConsole = new JScrollPane(textConsole);
        DefaultCaret caret = (DefaultCaret) textConsole.getCaret();
        caret.setUpdatePolicy(DefaultCaret.ALWAYS_UPDATE);
        GridBagConstraints gbc_scrollConsole = new GridBagConstraints();
        gbc_scrollConsole.insets = new Insets(8, 8, 8, 8);
        gbc_scrollConsole.gridx = 0;
        gbc_scrollConsole.gridy = 0;
        gbc_scrollConsole.gridwidth = 5;
        gbc_scrollConsole.weightx = 1d;
        gbc_scrollConsole.weighty = 1d;
        gbc_scrollConsole.fill = 1;
        getContentPane().add(scrollConsole, gbc_scrollConsole);

        JButton btnGenerateCrashReport = new JButton("Generate Crash Report");
        GridBagConstraints gbc_btnGenerateCrashReport = new GridBagConstraints();
        gbc_btnGenerateCrashReport.insets = new Insets(0, 8, 8, 8);
        gbc_btnGenerateCrashReport.gridx = 1;
        gbc_btnGenerateCrashReport.gridy = 1;
        gbc_btnGenerateCrashReport.anchor = GridBagConstraints.WEST;
        getContentPane().add(btnGenerateCrashReport, gbc_btnGenerateCrashReport);

        JButton btnKillMinecraft = new JButton("Kill Minecraft");
        GridBagConstraints gbc_btnKillMinecraft = new GridBagConstraints();
        gbc_btnKillMinecraft.weightx = 1.0;
        gbc_btnKillMinecraft.insets = new Insets(0, 0, 8, 8);
        gbc_btnKillMinecraft.gridx = 3;
        gbc_btnKillMinecraft.gridy = 1;
        gbc_btnKillMinecraft.anchor = GridBagConstraints.EAST;
        getContentPane().add(btnKillMinecraft, gbc_btnKillMinecraft);

        JButton btnHide = new JButton("Hide");
        btnHide.setFont(UIManager.getFont("Button.font"));
        GridBagConstraints gbc_btnHide = new GridBagConstraints();
        gbc_btnHide.insets = new Insets(0, 0, 8, 8);
        gbc_btnHide.gridx = 4;
        gbc_btnHide.gridy = 1;
        gbc_btnHide.anchor = GridBagConstraints.EAST;
        getContentPane().add(btnHide, gbc_btnHide);
    }
    
    /**
     * Log the specified message. This method can be called on any thread
     * 
     * @param text
     */
    public void log(final String message) {
        SwingUtilities.invokeLater(new Runnable() {
            public void run() {
                textConsole.append(String.format(Locale.ENGLISH, "[" + TIME_FORMAT_STRING + "] %2$s\n", new Date(), message));
            }
        });
    }

}
