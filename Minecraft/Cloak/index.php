<?php
// 获取玩家披风
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // UUID
    $UUID = (string)$Auth->StringParameters('UUID');
    // 类型
    $Type = (int)$Auth->RangeIntParameters('Type', 1, 2);
}

if ($ValidRequest) {
    $DataJson = json_decode(base64_decode(json_decode($Auth->Curl('GET', 'https://sessionserver.mojang.com/session/minecraft/profile/' . $UUID), true)['properties'][0]['value']), true)['textures'];
    if ($Type === 1) {
        header('Content-Type: PNG');
        echo $Auth->Curl('GET', $DataJson['CAPE']['url']);
        exit();
    } else {
        $Response['Data'] = 'data:image/png;base64,' . base64_encode($Auth->Curl('GET', $DataJson['CAPE']['url']));
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
