<?php
// 获取章节图片(VIP)
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 小说ID
  $NovelID = (string)$Auth->StringParameters('NovelID');
  // 章节ID
  $ChaptersID = (string)$Auth->StringParameters('ChaptersID');
  // 社区
  $Community = (string)$Auth->CookieParameters('Community');
}

if ($ValidRequest) {
  header("Content-Type: PNG");
  $Curl = curl_init();
  curl_setopt($Curl, CURLOPT_URL, 'https://book.sfacg.com/ajax/ashx/common.ashx?op=getChapPic&cid=' . $ChaptersID . '&nid=' . $NovelID);
  curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($Curl, CURLOPT_HTTPHEADER, [
    'Cookie: .SFCommunity=' . $Community
  ]);
  $Fh = curl_exec($Curl);
  curl_close($Curl);
  echo $Fh;
  exit();
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
