<?php
// 获取章节正文(免费)
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 小说ID
  $NovelID = (string)$Auth->StringParameters('NovelID');
  // 卷ID
  $ReelID = (string)$Auth->StringParameters('ReelID');
  // 章节ID
  $ChaptersID = (string)$Auth->StringParameters('ChaptersID');
}

if ($ValidRequest) {
  $Curl = curl_init();
  curl_setopt($Curl, CURLOPT_URL, 'https://book.sfacg.com/Novel/' . $NovelID . '/' . $ReelID . '/' . $ChaptersID);
  curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
  $Fh = curl_exec($Curl);
  curl_close($Curl);
  $Pattern = '/<div class="article-content font16" id="ChapterBody" data-class="font16">.\s*(.*?)<\/div>/s';
  preg_match_all($Pattern, $Fh, $Matches);
  $Pattern2 = '/<p>(.*?)<\/p>/s';
  preg_match_all($Pattern2, $Matches[1][0], $NovelMatches);
  $Response['Data'] = $NovelMatches[1];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
