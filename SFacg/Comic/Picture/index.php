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
  $Header = [
    'Cookie: session_PC=' . $Session . '; .SFCommunity=' . $Community
  ];
  $Pattern = '/<script language="javascript">.*?var c = (.*?);.*?<\/script>/s';
  preg_match($Pattern, $Auth->Curl('GET', 'https://manhua.sfacg.com/mh/' . $ComicID  . '/' . $ChaptersID, [], $Header), $Matches);
  $Parameters = [
    'op' => 'getPics',
    'cid' => $Matches[1],
    'chapId' => $ChaptersID
  ];
  $Json = json_decode($Auth->Curl('GET', 'https://manhua.sfacg.com/ajax/Common.ashx', $Parameters, $Header), true)['data'];
  $Response['Data'] = $Json;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
