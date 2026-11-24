package com.webview.builder;
import android.content.Intent; import android.graphics.Color; import android.os.*;
import android.widget.ImageView; import androidx.appcompat.app.AppCompatActivity;
import com.bumptech.glide.Glide; import org.json.JSONObject; import java.io.*; import java.net.*;
public class SplashActivity extends AppCompatActivity {
    @Override protected void onCreate(Bundle s){ super.onCreate(s); setContentView(R.layout.activity_splash);
        new Thread(() -> {
            String url=Config.DEFAULT_URL; int dur=Config.DEFAULT_SPLASH_DURATION; String color=Config.DEFAULT_SPLASH_COLOR; String logo=Config.DEFAULT_SPLASH_LOGO;
            boolean ext=Config.DEFAULT_EXTERNAL_LINKS, dl=Config.DEFAULT_DOWNLOAD, up=Config.DEFAULT_UPLOAD, pull=Config.DEFAULT_PULL_REFRESH;
            try{ // API MODE: fetch config from PHP
                if(!Config.APP_CODE.isEmpty()){
                    HttpURLConnection c=(HttpURLConnection)new URL(Config.API_BASE+Config.APP_CODE).openConnection(); c.setConnectTimeout(4000);
                    BufferedReader r=new BufferedReader(new InputStreamReader(c.getInputStream())); StringBuilder sb=new StringBuilder(); String l; while((l=r.readLine())!=null) sb.append(l);
                    JSONObject j=new JSONObject(sb.toString());
                    if(j.has("app_url")){ url=j.getString("app_url"); dur=j.getInt("splash_duration"); color=j.getString("splash_color"); logo=j.optString("splash_logo",""); ext=j.getBoolean("external_links"); dl=j.getBoolean("allow_download"); up=j.getBoolean("allow_upload"); pull=j.getBoolean("pull_refresh"); }
                }
            }catch(Exception e){}
            String fUrl=url; int fDur=dur; String fColor=color; String fLogo=logo;
            runOnUiThread(()->{
                try{ findViewById(R.id.splash_layout).setBackgroundColor(Color.parseColor(fColor)); }catch(Exception e){}
                ImageView iv=findViewById(R.id.splash_logo);
                if(!fLogo.isEmpty()) Glide.with(this).load(fLogo).into(iv);
            });
            Intent i=new Intent(this, MainActivity.class);
            i.putExtra("url",fUrl); i.putExtra("external",ext); i.putExtra("download",dl); i.putExtra("upload",up); i.putExtra("pull",pull);
            new Handler(Looper.getMainLooper()).postDelayed(()->{ startActivity(i); finish(); }, fDur);
        }).start();
    }
}
