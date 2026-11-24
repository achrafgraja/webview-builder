<?php require 'config.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $icon = upload_image('app_icon','icon');
  $logo = upload_image('splash_logo','splash');
  $code = app_code();
  $stmt=$pdo->prepare("INSERT INTO apps (app_code,app_name,app_url,splash_duration,splash_color,splash_logo,app_icon,external_links,allow_download,allow_upload,pull_refresh) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
  $stmt->execute([$code, $_POST['app_name'], trim($_POST['app_url']), (int)$_POST['splash_duration'], $_POST['splash_color'], $logo, $icon, isset($_POST['external_links'])?1:0, isset($_POST['allow_download'])?1:0, isset($_POST['allow_upload'])?1:0, isset($_POST['pull_refresh'])?1:0]);
  $id=$pdo->lastInsertId();
  // create config.json for build mode
  $dir=__DIR__."/api/apps/$id"; if(!is_dir($dir)) mkdir($dir,0777,true);
  $cfg=['app_name'=>$_POST['app_name'],'app_url'=>trim($_POST['app_url']),'splash_duration'=>(int)$_POST['splash_duration'],'splash_color'=>$_POST['splash_color'],'splash_logo'=>$logo,'app_icon'=>$icon,'external_links'=>isset($_POST['external_links']),'allow_download'=>isset($_POST['allow_download']),'allow_upload'=>isset($_POST['allow_upload']),'pull_refresh'=>isset($_POST['pull_refresh'])];
  file_put_contents("$dir/config.json", json_encode($cfg, JSON_PRETTY_PRINT));
  $msg="App created! Code: <b>$code</b> | API: ".SITE_URL."/api.php?code=$code | Config: api/apps/$id/config.json";
}
$apps=$pdo->query("SELECT * FROM apps ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>WebView Builder</title>
<style>*{box-sizing:border-box}body{font-family:Arial;background:#0f172a;color:#fff;padding:20px}.wrap{max-width:1000px;margin:auto}.card{background:#1e293b;padding:20px;border-radius:16px;margin-bottom:20px}input,select{width:100%;padding:12px;border-radius:10px;border:0;margin:6px 0;background:#0f172a;color:#fff}label{font-size:13px;color:#cbd5e1}.row{display:grid;grid-template-columns:1fr 1fr;gap:12px}.switch{display:flex;gap:10px;align-items:center;background:#0f172a;padding:12px;border-radius:10px;margin:6px 0}button{background:#22c55e;color:#000;font-weight:700;padding:14px 20px;border:0;border-radius:12px;cursor:pointer;width:100%}.app{background:#0f172a;padding:14px;border-radius:12px;margin:8px 0;display:flex;gap:12px;align-items:center}.app img{width:56px;height:56px;object-fit:contain;background:#fff;border-radius:12px}.badge{background:#22c55e;color:#000;padding:3px 8px;border-radius:20px;font-size:11px;font-weight:700}</style></head>
<body><div class="wrap"><h1>🚀 WebView App Builder</h1><?php if($msg) echo "<div class='card' style='background:#14532d'>$msg</div>"; ?>
<div class="card"><h2>Create New App</h2><form method="post" enctype="multipart/form-data">
<label>App Name</label><input name="app_name" placeholder="Naqlatheth" required>
<label>Website URL</label><input name="app_url" placeholder="https://naqlatheth.com" required>
<div class="row"><div><label>App Icon (PNG 512x512)</label><input type="file" name="app_icon" accept="image/*"></div><div><label>Splash Logo</label><input type="file" name="splash_logo" accept="image/*"></div></div>
<div class="row"><div><label>Splash Background Color</label><input type="color" name="splash_color" value="#0A1A2F"></div><div><label>Splash Duration (ms) - 2000 = 2 sec</label><select name="splash_duration"><option value="1500">1.5 sec</option><option value="2000" selected>2 sec</option><option value="3000">3 sec</option><option value="4000">4 sec</option><option value="5000">5 sec</option></select></div></div>
<div class="switch"><input type="checkbox" name="external_links" checked style="width:auto"> External Links - if NOT your site, open outside (Chrome)</div>
<div class="switch"><input type="checkbox" name="allow_download" checked style="width:auto"> Download - PDF, ZIP, Images via DownloadManager</div>
<div class="switch"><input type="checkbox" name="allow_upload" checked style="width:auto"> File Upload - allow &lt;input type=file&gt;</div>
<div class="switch"><input type="checkbox" name="pull_refresh" checked style="width:auto"> Pull to Refresh</div>
<button>✨ GENERATE APP</button></form></div>
<div class="card"><h2>Your Apps - Click BUILD to build APK (<?=count($apps)?>)</h2><?php foreach($apps as $a): ?><div class="app"><img src="<?= $a['splash_logo'] ?: $a['app_icon'] ?: 'https://via.placeholder.com/56' ?>"><div style="flex:1"><b><?=$a['app_name']?></b><br><small><?=$a['app_url']?> • Code: <b><?=$a['app_code']?></b> • Splash: <?=$a['splash_duration']?>ms • Color: <?=$a['splash_color']?></small></div><a href='build.php?id=<?=$a["id"]?>' style='background:#22c55e;color:#000;padding:8px 14px;border-radius:20px;font-weight:700;text-decoration:none'>🚀 BUILD APK</a></div><?php endforeach; ?></div></div></body></html>
