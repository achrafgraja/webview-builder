import sys, re
_, file, url, name, duration, color, logo, ext, dl, up = sys.argv
with open(file) as f: content=f.read()
def repl(key, val, is_str=True):
    global content
    v=f'"{val}"' if is_str else val
    pattern = rf"(public static (?:String|int|boolean) {key} = )[^;]+;"
    content=re.sub(pattern, rf"\1{v};", content)
repl("DEFAULT_URL", url); repl("DEFAULT_NAME", name); repl("DEFAULT_SPLASH_DURATION", duration, False); repl("DEFAULT_SPLASH_COLOR", color); repl("DEFAULT_SPLASH_LOGO", logo)
repl("DEFAULT_EXTERNAL_LINKS", "true" if ext=="1" else "false", False); repl("DEFAULT_DOWNLOAD", "true" if dl=="1" else "false", False); repl("DEFAULT_UPLOAD", "true" if up=="1" else "false", False)
content=content.replace('public static String APP_CODE = "ABC123";', 'public static String APP_CODE = "";')
with open(file,'w') as f: f.write(content)
print("Config updated OK")
