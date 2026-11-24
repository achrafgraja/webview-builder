<?php
require 'config.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
$code = $_GET['code'] ?? '';
if(!$code){ echo json_encode(['error'=>'code required']); exit; }
$stmt=$pdo->prepare("SELECT * FROM apps WHERE app_code=?");
$stmt->execute([$code]);
$app=$stmt->fetch(PDO::FETCH_ASSOC);
if(!$app){ echo json_encode(['error'=>'app not found']); exit; }
echo json_encode([
  'app_name'=>$app['app_name'],
  'app_url'=>$app['app_url'],
  'splash_duration'=>(int)$app['splash_duration'],
  'splash_color'=>$app['splash_color'],
  'splash_logo'=> $app['splash_logo'] ? SITE_URL.'/'.$app['splash_logo'] : '',
  'app_icon'=> $app['app_icon'] ? SITE_URL.'/'.$app['app_icon'] : '',
  'external_links'=>(bool)$app['external_links'],
  'allow_download'=>(bool)$app['allow_download'],
  'allow_upload'=>(bool)$app['allow_upload'],
  'pull_refresh'=>(bool)$app['pull_refresh'],
]);
