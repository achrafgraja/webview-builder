# WebView App Builder Platform - PHP + MySQL + Android

## How it works
1. Upload `php/` to your hosting (PHP 8 + MySQL)
2. Import `php/database.sql`
3. Edit `php/config.php` with DB credentials
4. Open `admin.php` -> Create App: Name, URL, Icon, Splash Logo, Splash Color, Splash Duration, External Links, Download, Upload
5. PHP saves to MySQL + uploads images to `uploads/` + creates `config.json` in `api/apps/{id}/`
6. Copy that config values into Android template OR use API mode
7. Build Android APK in Android Studio

## Two Modes
- MODE A - API Mode (Recommended, build APK once): App fetches config from `api.php?code=XXX`
- MODE B - Build Mode: Replace values in `android/app/src/main/java/.../Config.java` and rebuild per app

## Android Features
- Splash screen: logo image + background color + duration (2-5 sec) configurable
- App Icon: replace in res/mipmap-xxxhdpi/ic_launcher.png
- External Links: if NOT your site -> open in Chrome / tel: mailto: whatsapp: geo:
- Download: PDF/ZIP/Image via DownloadManager to Downloads folder + notification
- File Upload: <input type=file> via file chooser
- Pull to refresh, Back button, Full screen WebView
