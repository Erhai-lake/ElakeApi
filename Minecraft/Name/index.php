<?php
// 获取用户名
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // UUID
    $UUID = (string)$Auth->StringParameters('UUID');
}

if ($ValidRequest) {
    $DataJson = json_decode($Auth->Curl('GET', 'https://sessionserver.mojang.com/session/minecraft/profile/' . $UUID), true)['name'];
    $Response['Data'] = $DataJson;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
