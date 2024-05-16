<?php
// 生成图形验证码
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
  $RandomText = substr(str_shuffle("01TDIJKLGHU23NOR6SE78PQ9AB4MF5CVWXYZ"), 0, 6);
  $Width = 170;
  $Height = 50;
  $Image = imagecreatetruecolor($Width, $Height);
  $BackgroundColor = imagecolorallocate($Image, 255, 255, 255);
  imagefilledrectangle($Image, 0, 0, $Width, $Height, $BackgroundColor);
  for ($I = 0; $I < 500; $I++) {
    $PointColor = imagecolorallocate($Image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imagesetpixel($Image, mt_rand(0, $Width), mt_rand(0, $Height), $PointColor);
  }
  for ($I = 0; $I < 10; $I++) {
    $LineColor = imagecolorallocate($Image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    imageline($Image, 0, mt_rand(0, $Height), $Width, mt_rand(0, $Height), $LineColor);
  }
  imagettftext($Image, 20, mt_rand(-0, 20), 20 + 0 * 20, 30, RandomColor($Image), RandomFont(), $RandomText[0]);
  imagettftext($Image, 20, mt_rand(-20, 40), 20 + 1 * 20, 30, RandomColor($Image), RandomFont(), $RandomText[1]);
  imagettftext($Image, 20, mt_rand(-40, 80), 20 + 2 * 20, 30, RandomColor($Image), RandomFont(), $RandomText[2]);
  imagettftext($Image, 20, mt_rand(-40, 40), 40 + 3 * 20, 30, RandomColor($Image), RandomFont(), $RandomText[3]);
  imagettftext($Image, 20, mt_rand(-80, 20), 40 + 4 * 20, 30, RandomColor($Image), RandomFont(), $RandomText[4]);
  imagettftext($Image, 20, mt_rand(-100, 0), 40 + 5 * 20, 30, RandomColor($Image), RandomFont(), $RandomText[5]);
  ob_start();
  imagepng($Image);
  $Data = ob_get_clean();
  $Response['Data'] = [
    'Captcha' => Argon2Encipher($RandomText),
    'Image' => 'data:image/png;base64,' . base64_encode($Data)
  ];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);

function RandomColor($Image): int
{
  return imagecolorallocate($Image, mt_rand(0, 155), mt_rand(0, 155), mt_rand(0, 255));
}

function RandomFont()
{
  return  $_SERVER['DOCUMENT_ROOT'] . '/Captcha/Graphics/' . mt_rand(1, 4) . '.ttf';
}

function Argon2Encipher(string $Plaintext)
{
  $Options = [
    'memory_cost' => 65536,
    'time_cost' => 10,
    'threads' => 2
  ];
  return base64_encode(password_hash($Plaintext, PASSWORD_ARGON2I, $Options));
}
