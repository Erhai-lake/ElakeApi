<?php
// 今日历史
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
  $Header = [
    'User-Agent: Elake'
  ];
  $Pattern1 = '/<ul id="tohlis">(.*?)<\/ul>/s';
  preg_match($Pattern1, $Auth->Curl('https://tool.lu/todayonhistory/', [], $Header), $Matches);
  $Pattern2 = '/<li>(.*?) (.*?)<a .*?>.*?<\/a><\/li>/s';
  preg_match_all($Pattern2, $Matches[1], $MatchesItem);
  $Response['Data'] = $MatchesItem;
  $Data = [];
  for ($I = 0; $I < count($MatchesItem[0]); $I++) {
    $Data[] = [
      'Time' => $MatchesItem[1][$I],
      'Title' => $MatchesItem[2][$I],
      'BaiDu' => 'https://www.baidu.com/s?wd=' . $MatchesItem[1][$I] . ' ' . $MatchesItem[2][$I],
      'Bing' => 'https://www.bing.com/search?q=' . $MatchesItem[1][$I] . ' ' . $MatchesItem[2][$I],
      'google' => 'https://www.google.com/search?q=' . $MatchesItem[1][$I] . ' ' . $MatchesItem[2][$I]
    ];
  }
  $Response['Data'] = $Data;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
