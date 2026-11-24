import sys, urllib.request, os
splash_url = sys.argv[1] if len(sys.argv)>1 else ""
icon_url = sys.argv[2] if len(sys.argv)>2 else ""
def dl(url, dest):
    if not url or url=="null" or url.startswith("uploads/"): print(f"Skip relative URL: {url}"); return
    try:
        os.makedirs(os.path.dirname(dest), exist_ok=True); urllib.request.urlretrieve(url, dest); print(f"Downloaded {url} -> {dest}")
    except Exception as e: print(f"Failed {url}: {e}")
if splash_url: dl(splash_url, "android/app/src/main/res/drawable/splash_logo.png")
if icon_url:
    for d in ["mipmap-mdpi","mipmap-hdpi","mipmap-xhdpi","mipmap-xxhdpi","mipmap-xxxhdpi"]:
        dl(icon_url, f"android/app/src/main/res/{d}/ic_launcher.png"); dl(icon_url, f"android/app/src/main/res/{d}/ic_launcher_round.png")
