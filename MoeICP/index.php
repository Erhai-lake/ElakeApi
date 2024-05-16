<?php
// 萌ICP查询
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 备案号
  $ICP = (string)$Auth->StringParameters('ICP');
}

if ($ValidRequest) {
  $Curl = curl_init();
  curl_setopt($Curl, CURLOPT_URL, 'https://icp.gov.moe/?keyword=' . $ICP);
  curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
  $Fh = curl_exec($Curl);
  curl_close($Curl);
  $Pattern = '/<div class="value">(.*?)<\/div>/s';
  preg_match_all($Pattern, $Fh, $Matches);
  $Name = isset($Matches[1][0]) ? $Matches[1][0] : "无";
  $Domain = isset($Matches[1][1]) ? $Matches[1][1] : "无";
  $Info = isset($Matches[1][3]) ? $Matches[1][3] : "无";
  $Icp = isset($Matches[1][4]) ? $Matches[1][4] : "无";
  $Owner = isset($Matches[1][5]) ? $Matches[1][5] : "无";
  $UpdateDate = isset($Matches[1][6]) ? $Matches[1][6] : "无";
  $Status = isset($Matches[1][7]) ? $Matches[1][7] : "无";
  $Response['Data'] = [
    'Name' => $Name,
    'Domain' => $Domain,
    'Info' => $Info,
    'Icp' => $Icp,
    'Owner' => $Owner,
    'UpdateDate' => $UpdateDate
  ];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
