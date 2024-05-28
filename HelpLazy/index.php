<?php
// 让我帮你搜索一下
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 问题
  $Query = (string)$Auth->StringParameters('Query');
}

if ($ValidRequest) {
  $Url = $Auth->CurrentURL() . '/HelpLazy';
  $Response['Data'] = [
    'BaiDu' => $Url . '/BaiDu?Query=' . base64_encode($Query),
    'Bing' => $Url . '/Bing?Query=' . base64_encode($Query),
    'Google' => $Url . '/Google?Query=' . base64_encode($Query)
  ];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
