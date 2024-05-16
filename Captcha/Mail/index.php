<?php
// 说明
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 字符串参数
  // $Test = (string)$Auth->StringParameters('Test');
  // 数值参数
  // $Test = (int)$Auth->IntParameters('Test');
  // 最大范围数值参数
  // $Test = (int)$Auth->MaxRangeIntParameters('Test', 8);
  // 最小范围数值参数
  // $Test = (int)$Auth->MinRangeIntParameters('Test', 1);
  // 最大最小范围数值参数
  // $Test = (int)$Auth->RangeIntParameters('Test', 1, 8);
  // 参数白名单
  // $Test = (string)$Auth->WhitelistParameters('Test', []);
  // 参数黑名单
  // $Test = (string)$Auth->BlacklistParameters('Test', []);
  // 正则参数
  // $Test = (string)$Auth->PCREParameters('Test', '');
  // Cookie参数
  // $Test = (string)$Auth->CookieParameters('Test');
}

if ($ValidRequest) {
  $RandomText = substr(str_shuffle("01TDIJKLGHU23NOR6SE78PQ9AB4MF5CVWXYZ"), 0, 6);
  $Response['Data'] = $RandomText;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
