<?php
require 'config.php'; require 'github_config.php';
$id = (int)($_GET['id'] ?? 0);
$stmt=$pdo->prepare("SELECT * FROM apps WHERE id=?"); $stmt->execute([$id]); $app=$stmt->fetch(PDO::FETCH_ASSOC);
if(!$app) die("App not found");
$msg=''; $run_url='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  // 1. Trigger GitHub Actions build via API
  $payload=['ref'=>GITHUB_BRANCH,'inputs'=>[
    'app_id'=>(string)$app['id'],
    'app_code'=>$app['app_code'],
    'app_name'=>$app['app_name'],
    'app_url'=>$app['app_url'],
    'splash_duration'=>(string)$app['splash_duration'],
    'splash_color'=>$app['splash_color'],
    'splash_logo'=> $app['splash_logo'] ? SITE_URL.'/'.$app['splash_logo'] : '',
    'app_icon'=> $app['app_icon'] ? SITE_URL.'/'.$app['app_icon'] : '',
    'external_links'=>(string)$app['external_links'],
    'allow_download'=>(string)$app['allow_download'],
    'allow_upload'=>(string)$app['allow_upload'],
  ]];
  $ch=curl_init("https://api.github.com/repos/".GITHUB_OWNER."/".GITHUB_REPO."/actions/workflows/".GITHUB_WORKFLOW."/dispatches");
  curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,CURLOPT_HTTPHEADER=>["Authorization: Bearer ".GITHUB_TOKEN,"Accept: application/vnd.github+json","User-Agent: WebView-Builder","Content-Type: application/json"],CURLOPT_POSTFIELDS=>json_encode($payload)]);
  $res=curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
  if($code==204){
    $pdo->prepare("UPDATE apps SET build_status='building', build_started_at=NOW() WHERE id=?")->execute([$id]);
    $msg="✅ Build started! GitHub is building your APK now (1-3 min). Refresh this page to check status.";
    $run_url="https://github.com/".GITHUB_OWNER."/".GITHUB_REPO."/actions";
  } else { $msg="❌ GitHub Error ($code): $res - Check GITHUB_TOKEN, OWNER, REPO in github_config.php"; }
}
// 2. Check latest build status from GitHub
$status=$app['build_status'] ?? 'not_started';
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Build APK - <?=htmlspecialchars($app['app_name'])?></title>
<style>body{font-family:Arial;background:#0f172a;color:#fff;padding:20px}.wrap{max-width:600px;margin:auto}.card{background:#1e293b;padding:24px;border-radius:16px}button{background:#22c55e;font-weight:700;padding:14px;border:0;border-radius:12px;width:100%;cursor:pointer;font-size:16px}a{color:#38bdf8}.bar{background:#0f172a;height:14px;border-radius:20px;overflow:hidden;margin:12px 0}.fill{height:100%;background:#22c55e;width:<?= $status=='building'?'70%':'0%'?>;transition:1s}</style>
<script>setTimeout(()=>{ if("<?=$status?>"=="building") location.reload(); },15000);</script></head>
<body><div class="wrap"><div class="card"><h1>📱 <?=htmlspecialchars($app['app_name'])?></h1><p>URL: <?=htmlspecialchars($app['app_url'])?><br>Code: <b><?=$app['app_code']?></b> | Splash: <?=$app['splash_duration']?>ms | Color: <?=$app['splash_color']?></p>
<p>Status: <b><?=strtoupper($status)?></b></p><div class="bar"><div class="fill"></div></div>
<?php if($msg) echo "<p style='background:#14532d;padding:12px;border-radius:10px'>$msg</p>"; ?>
<?php if($status!=='building'): ?><form method="post"><button>🚀 BUILD APK NOW (Free via GitHub)</button></form><?php else: ?><p>⏳ Building... This page auto-refreshes every 15 sec. Check GitHub Actions:</p><?php endif; ?>
<p><a href="https://github.com/<?=GITHUB_OWNER?>/<?=GITHUB_REPO?>/actions" target="_blank">👉 Open GitHub Actions → Download APK from Artifacts</a></p>
<p><a href="admin.php">← Back to Admin</a></p>
<p style="font-size:12px;color:#94a3b8">How it works: PHP → GitHub API → GitHub server installs Android SDK + Java → Updates Config.java with your URL/color/logo → Runs ./gradlew assembleRelease → Uploads APK as Artifact. You download it from GitHub Actions page.</p>
</div></div></body></html>
