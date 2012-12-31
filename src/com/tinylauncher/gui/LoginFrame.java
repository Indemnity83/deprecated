package com.tinylauncher.gui;

import javax.swing.JButton;
import javax.swing.JCheckBox;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPasswordField;
import javax.swing.JTextField;
import javax.swing.SwingConstants;

public class LoginFrame extends JFrame {

    protected static final long serialVersionUID = -1554637441027811453L;
    protected JTextField        txtUsername;
    protected JPasswordField    txtPassword;
    protected String          frameTitle;

    public LoginFrame() {
        this("Login");
    }

    public LoginFrame(String title) {
        frameTitle = title;

        setTitle(frameTitle);
        setSize(400, 153);
        setResizable(false);
        setAlwaysOnTop(true);
        setType(Type.UTILITY);
        setLocationRelativeTo(null);

        getContentPane().setLayout(null);

        JLabel lblUsername = new JLabel("Username:");
        lblUsername.setHorizontalAlignment(SwingConstants.RIGHT);
        lblUsername.setBounds(10, 11, 60, 14);
        getContentPane().add(lblUsername);

        JLabel lblPassword = new JLabel("Password:");
        lblPassword.setHorizontalAlignment(SwingConstants.RIGHT);
        lblPassword.setBounds(10, 36, 60, 14);
        getContentPane().add(lblPassword);

        txtUsername = new JTextField();
        txtUsername.setBounds(80, 8, 294, 20);
        getContentPane().add(txtUsername);
        txtUsername.setColumns(10);

        txtPassword = new JPasswordField();
        txtPassword.setBounds(80, 33, 294, 20);
        getContentPane().add(txtPassword);
        txtPassword.setColumns(10);
        txtPassword.setEchoChar('*');

        JButton btnForceUpdate = new JButton("Force update");
        btnForceUpdate.setBounds(10, 61, 100, 23);
        getContentPane().add(btnForceUpdate);

        JButton btnPlayOffline = new JButton("Play Offline");
        btnPlayOffline.setBounds(10, 95, 100, 23);
        getContentPane().add(btnPlayOffline);

        JButton btnCancel = new JButton("Cancel");
        btnCancel.setBounds(285, 95, 89, 23);
        getContentPane().add(btnCancel);

        JButton btnOk = new JButton("OK");
        btnOk.setBounds(186, 95, 89, 23);
        getContentPane().add(btnOk);

        JCheckBox chckbxRememberPassword = new JCheckBox("Remember password");
        chckbxRememberPassword.setHorizontalAlignment(SwingConstants.RIGHT);
        chckbxRememberPassword.setBounds(224, 61, 150, 23);
        getContentPane().add(chckbxRememberPassword);

        setVisible(true);
    }
}