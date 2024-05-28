<?php
// 获取月度粉丝榜
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
  $Pattern = '/<div id="tab1_0">(.*?)<\/div>/s';
  preg_match($Pattern, $Auth->Curl('https://book.sfacg.com/rank/'), $ListMatches);
  $Pattern2 = '/<li class="bd_PHB_list"><span>(\d+)<\/span><span><a href="([^"]+)">([^<]+)<\/a><\/span>(\d+)<\/li>/';
  preg_match_all($Pattern2, $ListMatches[1], $Matches, PREG_SET_ORDER);
  $Data = [];
  foreach ($Matches as $Item) {
    $Data[] = [
      'Title' => $Item[3],
      'Url' => $Item[2],
      'Consumption' => $Item[4]
    ];
  }
  $Response['Data'] = $Data;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
