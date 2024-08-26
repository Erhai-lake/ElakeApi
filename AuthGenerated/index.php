<?php
// 认证数据生成器
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use Ramsey\Uuid\Uuid;

if ($Auth->Authenticate(true)) {
    // 客户端ID
    $SecretID = (string)$Auth->StringParameters('SecretID');
    // 客户端秘钥
    $SecretKey = (string)$Auth->StringParameters('SecretKey');
}

if ($ValidRequest) {
    $Time = time();
    $Characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $Random = '';
    for ($i = 0; $i < 6; $i++) {
        $Random .= $Characters[random_int(0, strlen($Characters) - 1)];
    }
    $DS = md5('salt=' . $SecretKey . '&t=' . $Time . '&r=' . $Random);
    $DS = sprintf('%s,%s,%s', $Time, $Random, $DS);
    $UUID = Uuid::uuid4();
    $AuthGenerated = [
        'SecretID' => $SecretID,
        'DS' => $DS,
        'Deprecated' => $UUID->toString()
    ];
    $Response['Data'] = 'Bearer ' . base64_encode(json_encode($AuthGenerated));
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
