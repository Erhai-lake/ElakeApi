<?php
// 获取UUID
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 用户名
  $Name = (string)$Auth->StringParameters('Name');
}

if ($ValidRequest) {
  $DataJson = json_decode($Auth->Curl('GET', 'https://api.mojang.com/users/profiles/minecraft/' . $Name), true)['id'];
  $Response['Data'] = $DataJson;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
