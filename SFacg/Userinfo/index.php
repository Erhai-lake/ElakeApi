<?php
// 获取用户信息
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 社区
  $Community = (string)$Auth->CookieParameters('Community');
  // 会话
  $Session = (string)$Auth->CookieParameters('Session');
}

if ($ValidRequest) {
  $Header = [
    'Cookie: session_PC=' . $Session . '; .SFCommunity=' . $Community
  ];
  $Pattern = '/<div class="wrap_right cover">(.*?)<\/ul>/s';
  preg_match($Pattern, $Auth->Curl('GET', 'https://passport.sfacg.com/', [], $Header), $Matches);
  $Pattern2 = '/我的账户余额：(.*?)（￥1元 = 100火劵）  总计消费：(.*?)<\/div>/s';
  preg_match($Pattern2, $Matches[1], $BalanceMatches);
  $Pattern3 = '/<li style="line-height:1.5em;float:left;">(.*?)<\/li>/s';
  preg_match($Pattern3, $Matches[1], $NameMatches);
  $Pattern4 = '/<li style="float:left;"><img src="(.*?)"  border="0"/s';
  preg_match($Pattern4, $Matches[1], $ImageMatches);
  $Response['Data'] = [
    'Name' => $NameMatches[1],
    'Balance' => $BalanceMatches[1],
    'Consumption' => $BalanceMatches[2],
    'Image' => $ImageMatches[1]
  ];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
