<?php
// 随机字符串生成
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 类型
  $Type = (string)$Auth->WhitelistParameters('Type', ['1', '2', '3', '4', '12', '13', '14', '123', '124', '134', '1234']);
  //长度
  $Length = (int)$Auth->RangeIntParameters('Length', 1, 100);
  //数量
  $Limit = (int)$Auth->RangeIntParameters('Limit', 1, 1000);
}

if ($ValidRequest) {
  $String = '';
  if (strpos($Type, '1') !== false) {
    $String .= '0123456789';
  }
  if (strpos($Type, '2') !== false) {
    $String .= 'abcdefghijklmnopqrstuvwxyz';
  }
  if (strpos($Type, '3') !== false) {
    $String .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  }
  if (strpos($Type, '4') !== false) {
    $String .= '`~!@#$%^&*()-_=+[{]}|;:\',<.>/?';
  }
  $RandomStrings = array();
  $CharactersLength = strlen($String);
  for ($I = 0; $I < $Limit; $I++) {
    $RandomString = '';
    for ($J = 0; $J < $Length; $J++) {
      $RandomString .= $String[rand(0, $CharactersLength - 1)];
    }
    $RandomStrings[] = $RandomString;
  }
  $Response['Data'] = $RandomStrings;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
