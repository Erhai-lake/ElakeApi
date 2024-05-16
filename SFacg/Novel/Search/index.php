<?php
// 搜索轻小说
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 关键词
  $Value = (string)$Auth->StringParameters('Value');
}

if ($ValidRequest) {
  $Curl = curl_init();
  curl_setopt($Curl, CURLOPT_URL, 'https://s.sfacg.com/default.aspx?Key=' . $Value . '&S=1&SS=0');
  curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
  $Fh = curl_exec($Curl);
  curl_close($Curl);
  $Pattern = '/<ul style="width:100%">\s*<li.*?>\s*<img src="(.*?)" .*?>\s*<\/li>\s*<li><strong.*?><a href="(.*?)" .*?>(.*?)<\/a><\/strong><br \/>\s*综合信息： (.*?)<br \/>(.*?)<\/li>\s*<\/ul>/s';
  preg_match_all($Pattern, $Fh, $Matches);
  $Data = [];
  for ($I = 0; $I < count($Matches[0]); $I++) {
    $Latest = explode('/', $Matches[4][$I]);
    $Profile = ltrim($Matches[5][$I], " \r\n\t");
    $Data[] = [
      'Title' => $Matches[3][$I],
      'Url' => $Matches[2][$I],
      'Image' => $Matches[1][$I],
      'Latest' => $Latest[0],
      'UpdateTime' => $Latest[1] . '-' . $Latest[2] . '-' . $Latest[3],
      'Profile' => $Profile
    ];
  }
  $Response['Data'] = $Data;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
