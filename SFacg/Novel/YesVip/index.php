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
  $Parameters = [
    'op' => 'getChapPic',
    'cid' => $ChaptersID,
    'nid' => $NovelID
  ];
  $Header = [
    'Cookie: .SFCommunity=' . $Community
  ];
  echo $Auth->Curl('https://book.sfacg.com/ajax/ashx/common.ashx', $Parameters, $Header);
  exit();
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
