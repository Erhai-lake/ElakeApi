<?php
// 获取首页轮播图
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
  $Curl = curl_init();
  curl_setopt($Curl, CURLOPT_URL, 'https://book.sfacg.com/');
  curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
  $Fh = curl_exec($Curl);
  curl_close($Curl);
  $Pattern = '/<li>\s*<a\s+href="([^"]+)"[^>]*>\s*<img\s+[^>]*data-original="([^"]+)"[^>]*>\s*<\/a>\s*<\/li>/s';
  preg_match_all($Pattern, $Fh, $Matches, PREG_SET_ORDER);
  $Data = [];
  foreach ($Matches as $Item) {
    if (substr($Item[1], 0, 7) === 'http://' || substr($Item[1], 0, 8) === 'https://') {
      $Url = $Item[1];
    } else {
      $Url = 'https://book.sfacg.com' . $Item[1];
    }
    $Data[] = [
      'Url' => $Url,
      'Image' => $Item[2]
    ];
  }
  $Response['Data'] = $Data;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
