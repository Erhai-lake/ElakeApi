<?php
// 获取章节图片
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 漫画ID
  $ComicID = (string)$Auth->StringParameters('ComicID');
  // 章节ID
  $ChaptersID = (string)$Auth->StringParameters('ChaptersID');
  // 社区
  $Community = (string)$Auth->CookieParameters('Community');
  // 会话
  $Session = (string)$Auth->CookieParameters('Session');
}

if ($ValidRequest) {
  $Curl = curl_init();
  curl_setopt($Curl, CURLOPT_URL, 'https://manhua.sfacg.com/mh/' . $ComicID  . '/' . $ChaptersID);
  curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($Curl, CURLOPT_HTTPHEADER, [
    'Cookie: session_PC=' . $Session . '; .SFCommunity=' . $Community
  ]);
  $Fh = curl_exec($Curl);
  $Pattern = '/<script language="javascript">.*?var c = (.*?);.*?<\/script>/s';
  preg_match($Pattern, $Fh, $Matches);
  curl_setopt($Curl, CURLOPT_URL, 'https://manhua.sfacg.com/ajax/Common.ashx?op=getPics&cid=' . $Matches[1] . '&chapId=' . $ChaptersID);
  $Fh = curl_exec($Curl);
  curl_close($Curl);
  $Json = json_decode($Fh, true)['data'];
  $Response['Data'] = $Json;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
