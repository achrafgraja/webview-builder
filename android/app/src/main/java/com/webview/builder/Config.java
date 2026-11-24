package com.webview.builder;
// BUILD MODE: Edit these values from PHP config.json OR use API MODE (fetch from api.php?code=XXX)
public class Config {
    public static String API_BASE = "https://yourbuilder.com/api.php?code="; // set your domain
    public static String APP_CODE = "ABC123"; // code from admin.php - if empty, use DEFAULT below
    // Fallback if API fails / Build Mode
    public static String DEFAULT_URL = "https://naqlatheth.com";
    public static String DEFAULT_NAME = "Naqlatheth";
    public static int DEFAULT_SPLASH_DURATION = 2000; // 2000 = 2 sec
    public static String DEFAULT_SPLASH_COLOR = "#0A1A2F";
    public static String DEFAULT_SPLASH_LOGO = ""; // URL or empty = use drawable
    public static boolean DEFAULT_EXTERNAL_LINKS = true;
    public static boolean DEFAULT_DOWNLOAD = true;
    public static boolean DEFAULT_UPLOAD = true;
    public static boolean DEFAULT_PULL_REFRESH = true;
}
