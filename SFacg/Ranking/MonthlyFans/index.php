<?php
// 获取月度粉丝榜
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
  $Curl = curl_init();
  curl_setopt($Curl, CURLOPT_URL, 'https://book.sfacg.com/rank/');
  curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
  $Fh = curl_exec($Curl);
  curl_close($Curl);
  $Pattern = '/<div id="tab1_0">(.*?)<\/div>/s';
  preg_match($Pattern, $Fh, $ListMatches);
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
