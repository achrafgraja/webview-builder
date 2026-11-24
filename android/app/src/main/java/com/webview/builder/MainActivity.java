package com.webview.builder;
import android.app.DownloadManager; import android.content.Intent; import android.net.Uri; import android.os.*;
import android.webkit.*; import android.widget.Toast; import androidx.appcompat.app.AppCompatActivity; import androidx.swiperefreshlayout.widget.SwipeRefreshLayout; import android.os.Environment;
public class MainActivity extends AppCompatActivity {
    WebView webView; SwipeRefreshLayout swipe; String MAIN_URL; String MAIN_DOMAIN=""; boolean allowExternal=true, allowDownload=true, allowUpload=true;
    private ValueCallback<Uri[]> fileCb; private final static int FILE_CHOOSER=1;
    @Override protected void onCreate(Bundle s){ super.onCreate(s); setContentView(R.layout.activity_main);
        MAIN_URL=getIntent().getStringExtra("url"); if(MAIN_URL==null) MAIN_URL=Config.DEFAULT_URL;
        allowExternal=getIntent().getBooleanExtra("external",true); allowDownload=getIntent().getBooleanExtra("download",true); allowUpload=getIntent().getBooleanExtra("upload",true);
        boolean pull=getIntent().getBooleanExtra("pull",true);
        try{ MAIN_DOMAIN=Uri.parse(MAIN_URL).getHost(); }catch(Exception e){}
        webView=findViewById(R.id.webview); swipe=findViewById(R.id.swipe); swipe.setEnabled(pull);
        WebSettings st=webView.getSettings(); st.setJavaScriptEnabled(true); st.setDomStorageEnabled(true); st.setAllowFileAccess(true); st.setAllowContentAccess(true); st.setMediaPlaybackRequiresUserGesture(false); st.setSupportMultipleWindows(true);
        webView.setWebViewClient(new WebViewClient(){
            @Override public boolean shouldOverrideUrlLoading(WebView v, WebResourceRequest req){
                String url=req.getUrl().toString();
                if(url.startsWith("tel:")||url.startsWith("mailto:")||url.startsWith("whatsapp:")||url.startsWith("geo:")||url.startsWith("intent:")){ try{ startActivity(new Intent(Intent.ACTION_VIEW, Uri.parse(url))); }catch(Exception e){} return true; }
                String host=Uri.parse(url).getHost();
                if(host!=null && MAIN_DOMAIN!=null && host.contains(MAIN_DOMAIN)) return false;
                if(allowExternal){ try{ startActivity(new Intent(Intent.ACTION_VIEW, Uri.parse(url))); }catch(Exception e){} return true; }
                return false;
            }
        });
        if(allowDownload){
            webView.setDownloadListener((url,userAgent,contentDisposition,mimetype,contentLength)->{
                DownloadManager.Request req=new DownloadManager.Request(Uri.parse(url)); req.setMimeType(mimetype); req.addRequestHeader("User-Agent",userAgent); req.addRequestHeader("cookie",CookieManager.getInstance().getCookie(url));
                String fn=URLUtil.guessFileName(url,contentDisposition,mimetype); req.setTitle(fn); req.setDescription("Downloading file..."); req.allowScanningByMediaScanner(); req.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED); req.setDestinationInExternalPublicDir(Environment.DIRECTORY_DOWNLOADS, fn);
                ((DownloadManager)getSystemService(DOWNLOAD_SERVICE)).enqueue(req); Toast.makeText(this,"Download started",Toast.LENGTH_SHORT).show();
            });
        }
        if(allowUpload){
            webView.setWebChromeClient(new WebChromeClient(){
                @Override public boolean onShowFileChooser(WebView w, ValueCallback<Uri[]> cb, FileChooserParams p){ fileCb=cb; Intent it=new Intent(Intent.ACTION_GET_CONTENT); it.addCategory(Intent.CATEGORY_OPENABLE); it.setType("*/*"); startActivityForResult(Intent.createChooser(it,"Choose File"), FILE_CHOOSER); return true; }
            });
        }
        swipe.setOnRefreshListener(()->{ webView.reload(); swipe.setRefreshing(false); });
        String deep=getIntent().getDataString(); webView.loadUrl(deep!=null?deep:MAIN_URL);
    }
    @Override protected void onActivityResult(int rc,int res,Intent d){ super.onActivityResult(rc,res,d); if(rc==FILE_CHOOSER && fileCb!=null){ Uri[] r=null; if(res==RESULT_OK && d!=null) r=new Uri[]{d.getData()}; fileCb.onReceiveValue(r); fileCb=null; } }
    @Override public void onBackPressed(){ if(webView.canGoBack()) webView.goBack(); else super.onBackPressed(); }
}
