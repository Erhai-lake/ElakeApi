<?php
// 添加词库
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate(true)) {
    // 码点
    $Code = (string)$Auth->StringParameters('Code');
    // 字符
    $Character = (string)$Auth->StringParameters('Character');
}

if ($ValidRequest) {
    $Dictionary = json_decode(file_get_contents('//api.elake.top/FanQieNovel/Dictionary.json'), true);
    if ($Dictionary[$Code] !== null) {
        $Response['Data'] = '词库已收录该码点';
    } else{
        $NewData = [
            $Code => $Character
        ];
        $DataArray = $Dictionary + $NewData;
        $NewJsonString = json_encode($DataArray, JSON_UNESCAPED_UNICODE);
        file_put_contents('//api.elake.top/FanQieNovel/Dictionary.json', $NewJsonString);
        $Response['Data'] = '收录成功';
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
