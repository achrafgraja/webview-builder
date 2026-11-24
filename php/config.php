<?php
// EDIT THIS
define('DB_HOST','sultandbard.mysql.db');
define('DB_NAME','sultandbard');
define('DB_USER','sultandbard');
define('DB_PASS','tZZs55CkT3k9n4');
define('SITE_URL','https://yourbuilder.com'); // no trailing slash
define('UPLOAD_DIR', __DIR__.'/uploads/');

try {
  $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
} catch(Exception $e){ die("DB Error: ".$e->getMessage()); }
function upload_image($field, $prefix){
  if(!isset($_FILES[$field]) || $_FILES[$field]['error']!==0) return '';
  $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
  if(!in_array($ext,['png','jpg','jpeg','webp'])) return '';
  if(!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR,0777,true);
  $name = $prefix.'_'.time().'_'.rand(100,999).'.'.$ext;
  move_uploaded_file($_FILES[$field]['tmp_name'], UPLOAD_DIR.$name);
  return 'uploads/'.$name;
}
function app_code(){ return strtoupper(substr(md5(uniqid(rand(),true)),0,6)); }
