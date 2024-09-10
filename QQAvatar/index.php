<?php
// QQ头像解析
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // QQ号
    $QQ = (string)$Auth->StringParameters('QQ');
    // 类型
    $Type = (int)$Auth->RangeIntParameters('Type', 1, 2);
}

if ($ValidRequest) {
    if ($Type === 1) {
        header('Content-Type: PNG');
        echo $Auth->Curl('GET', 'https://qlogo4.store.qq.com/qzone/' . $QQ . '/' . $QQ . '/100');
        exit();
    } else {
        $Response['Data'] = 'https://qlogo4.store.qq.com/qzone/' . $QQ . '/' . $QQ . '/100';
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
