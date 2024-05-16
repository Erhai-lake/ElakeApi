<?php
// 发送邮箱验证码
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($Auth->Authenticate()) {
  // SMTP服务器
  $Host = (string)$Auth->StringParameters('Host', 'smtp.qiye.163.com');
  // SMTP身份验证
  $SMTPAuth = (int)$Auth->RangeIntParameters('SMTPAuth', 1, 2, 1);
  // SMTP用户名
  $Name = (string)$Auth->StringParameters('Name', 'elakeserver@elake.top');
  // SMTP密码
  $Password = (string)$Auth->StringParameters('Password', 'BBv7xpFxJUXD4cnv');
  // SMTP端口
  $Port = (int)$Auth->IntParameters('Port', 587);
  // 发信人邮箱地址
  $SenderEmail = (string)$Auth->StringParameters('SenderEmail', 'elakeserver@elake.top');
  // 发信人名称
  $SenderName = (string)$Auth->StringParameters('SenderName', '洱海工作室');
  // 收信人邮箱地址
  $ReceiversEmail = (string)$Auth->StringParameters('ReceiversEmail');
  // 收信人名称
  $ReceiversName = (string)$Auth->StringParameters('ReceiversName');
  // 平台
  $Platform = (string)$Auth->StringParameters('Platform');
  // 平台Logo
  $PlatformLogo = (string)$Auth->StringParameters('PlatformLogo');
  // 操作
  $Operation = (string)$Auth->StringParameters('Operation');
  // 样式
  $Style = (int)$Auth->RangeIntParameters('Style', 1, 2, 1);
}

if ($ValidRequest) {
  $Mail = new PHPMailer(true);
  try {
    $Mail->isSMTP();
    $Mail->Host = $Host;
    if ($SMTPAuth === 1) {
      $Mail->SMTPAuth = true;
    } else {
      $Mail->SMTPAuth = false;
    }
    $Mail->Username = $Name;
    $Mail->Password = $Password;
    $Mail->SMTPSecure = 'tls';
    $Mail->Port = $Port;
    $Mail->CharSet = 'UTF-8';
    $Mail->Encoding = 'base64';
    $Mail->setFrom($SenderEmail, $SenderName);
    $Mail->addAddress($ReceiversEmail, $ReceiversName);
    $Mail->Subject = $Platform;
    $RandomText = substr(str_shuffle("01TDIJKLGHU23NOR6SE78PQ9AB4MF5CVWXYZ"), 0, 6);
    $ReplaceArray = [
      '{{Platform}}' => $Platform,
      '{{PlatformLogo}}' => $PlatformLogo,
      '{{Captcha}}' => $RandomText,
      '{{Operation}}' => $Operation
    ];
    $HtmlContent = strtr(file_get_contents('Style/' . $Style . '.html'), $ReplaceArray);
    $Mail->isHTML(true);
    $Mail->Body = $HtmlContent;
    $Mail->send();
    $Response['Data'] = Argon2Encipher($RandomText);
  } catch (Exception $E) {
    $Auth->Custom('邮件发送失败');
  }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);

function Argon2Encipher(string $Plaintext)
{
  $Options = [
    'memory_cost' => 65536,
    'time_cost' => 10,
    'threads' => 2
  ];
  return base64_encode(password_hash($Plaintext, PASSWORD_ARGON2I, $Options));
}
