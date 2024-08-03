<?php
// 获取卷和章节
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // ID
  $ID = (string)$Auth->StringParameters('ID');
}

if ($ValidRequest) {
  $Pattern = '/<div class="story-catalog">.*?<h3 class="catalog-title">【.*?】 (.*?)<\/h3>.*?<ul class="clearfix">.\s*<li>(.*?)<\/li>.\s*<\/ul>/s';
  preg_match_all($Pattern, $Auth->Curl('GET', 'https://book.sfacg.com/Novel/'. $ID . '/MainIndex/'), $ReelMatches);
  $Pattern2 = '/<a href="(.*?)" title="(.*?)" .*?>.*?<\/a>/s';
  $Data = [];
  for ($I = 0; $I < count($ReelMatches[0]); $I++) {
    preg_match_all($Pattern2, $ReelMatches[2][$I], $Matches);
    $Chapters = [];
    for ($J = 0; $J < count($Matches[0]); $J++) {
      if (substr($Matches[1][$J], 1, 3) === 'vip') {
        $Vip = true;
      } else {
        $Vip = false;
      }
      if (substr($Matches[1][$J], 0, 7) === 'http://' || substr($Matches[1][$J], 0, 8) === 'https://') {
        $Url = $Matches[1][$J];
      } else {
        $Url = 'https://book.sfacg.com' . $Matches[1][$J];
      }
      $Chapters[] = [
        'Title' => $Matches[2][$J],
        'Url' => $Url,
        'Vip' => $Vip
      ];
    }
    $Data[] = [
      'Title' => $ReelMatches[1][$I],
      'Chapters' => $Chapters
    ];
  }
  $Response['Data'] = $Data;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
