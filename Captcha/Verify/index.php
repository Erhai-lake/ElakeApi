<?php
// 校验验证码
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 用户输入
    $Code = strtoupper((string)$Auth->StringParameters('Code'));
    // 验证码
    $Captcha = base64_decode((string)$Auth->StringParameters('Captcha'));
}

if ($ValidRequest) {
    $Response['Data'] = Argon2Verify($Code, $Captcha);
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
if ($ValidRequest) {
}

function Argon2Verify(string $PlainText, string $CipherText)
{
    if (password_verify($PlainText, $CipherText)) {
        return "校验通过";
    } else {
        return "校验失败";
    }
}
