# AUTO APK BUILDER - Setup in 5 Minutes (FREE)

## Why this works
PHP cannot build APK. GitHub can - for FREE.
Flow: Your admin.php -> Click BUILD -> build.php calls GitHub API -> GitHub server builds APK -> You download APK

## Setup Steps
### 1. Push this project to GitHub
- Create repo on github.com e.g. `webview-builder`
- Upload ALL files including `.github/` folder (GitHub hides dot-folders, make sure you upload it)
- Branch must be `main`

### 2. Create GitHub Token
- Go to github.com/settings/tokens -> Generate new token (classic)
- Check: `repo` + `workflow`
- Copy token `ghp_xxx...`

### 3. Edit php/github_config.php
```
GITHUB_TOKEN = your token
GITHUB_OWNER = your github username
GITHUB_REPO = webview-builder
GITHUB_BRANCH = main
```

### 4. Edit php/config.php
- Set SITE_URL to your real domain e.g. https://yourbuilder.com (no trailing slash)
- Important: splash_logo and app_icon URLs must be FULL URLs, otherwise GitHub cannot download them.

### 5. Use it
- admin.php -> Create App -> Click BUILD or open build.php?id=1
- Click BUILD APK NOW
- Wait 1-3 min -> Open GitHub Actions link -> Click latest run -> Download Artifact `app-ABC123` -> Inside is your .apk

## Files Added
- `.github/workflows/build-apk.yml` - The factory
- `.github/scripts/update_config.py` - Injects your URL/color/logo into Config.java
- `.github/scripts/download_assets.py` - Downloads icon/logo and replaces launcher icon
- `php/build.php` - Build page + status + auto-refresh
- `php/github_config.php` - Your GitHub credentials

## Troubleshooting
- 404 on dispatch: Token missing `workflow` scope OR workflow file name wrong OR repo is private and token wrong.
- Build fails at gradlew: Make sure `android/gradlew` exists. If you open project in Android Studio once and build, it generates gradlew. Included here is a placeholder - Android Studio will generate it.
- Icon not changed: Make sure app_icon URL is full https://... not uploads/...
- Want signed APK for Play Store? Add your keystore to GitHub Secrets and add signing step in workflow. For testing, debug APK works on any phone.
