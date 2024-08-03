<?php
// 头像图标生成
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 用户名
  $Name = (string)$Auth->StringParameters('Name');
  // 自发光颜色
  $LuminousColor = (string)$Auth->StringParameters('LuminousColor', 'FFFFFF');
  // 背景颜色
  $BackgroundColor = (string)$Auth->StringParameters('BackgroundColor', 'FFFFFF');
  // 图像大小
  $Size = (int)$Auth->RangeIntParameters('Size', 256, 2048, 256);
  // 类型
  $Type = (int)$Auth->RangeIntParameters('Type', 1, 2, 1);
}

if ($ValidRequest) {
  // 第一步: 载入皮肤
  $UUID = json_decode($Auth->Curl('GET', 'https://api.mojang.com/users/profiles/minecraft/' . $Name), true)['id'];
  $Skin = $Auth->Curl('GET', json_decode(base64_decode(json_decode($Auth->Curl('GET', 'https://sessionserver.mojang.com/session/minecraft/profile/' . $UUID), true)['properties'][0]['value']), true)['textures']['SKIN']['url']);
  $Image = imagecreatefromstring($Skin);
  if (imagesx($Image) != 64 || imagesy($Image) != 64) {
    imagedestroy($Image);
    $Auth->Custom('皮肤图片不合规');
  } else {
    // 第二步: 截取头部并放大居中
    $Head = imagecreatetruecolor(192, 192);
    imagesavealpha($Head, true);
    $TransparentColor = imagecolorallocatealpha($Head, 255, 255, 255, 127);
    imagefill($Head, 0, 0, $TransparentColor);
    imagecopyresampled($Head, $Image, 0, 0, 8, 8, 192, 192, 8, 8);
    $CenterHead = imagecreatetruecolor(256, 256);
    imagesavealpha($CenterHead, true);
    $TransparentColor = imagecolorallocatealpha($CenterHead, 255, 255, 255, 127);
    imagefill($CenterHead, 0, 0, $TransparentColor);
    $X = (256 - imagesx($Head)) / 2;
    $Y = (256 - imagesy($Head)) / 2;
    imagecopy($CenterHead, $Head, $X, $Y, 0, 0, imagesx($Head), imagesy($Head));
    $Head = $CenterHead;
    // 第三步: 截取头发并放大居中
    $Hair = imagecreatetruecolor(224, 224);
    imagesavealpha($Hair, true);
    $TransparentColor = imagecolorallocatealpha($Hair, 255, 255, 255, 127);
    imagefill($Hair, 0, 0, $TransparentColor);
    imagecopyresampled($Hair, $Image, 0, 0, 40, 8, 224, 224, 8, 8);
    $CenterHair = imagecreatetruecolor(256, 256);
    imagesavealpha($CenterHair, true);
    $TransparentColor = imagecolorallocatealpha($CenterHair, 255, 255, 255, 127);
    imagefill($CenterHair, 0, 0, $TransparentColor);
    $X = (256 - imagesx($Hair)) / 2;
    $Y = (256 - imagesy($Hair)) / 2;
    imagecopy($CenterHair, $Hair, $X, $Y, 0, 0, imagesx($Hair), imagesy($Hair));
    $Hair = $CenterHair;
    // 第四步: 头部与头发合并
    $HeadMerged = imagecreatetruecolor(256, 256);
    imagesavealpha($HeadMerged, true);
    $TransparentColor = imagecolorallocatealpha($HeadMerged, 255, 255, 255, 127);
    imagefill($HeadMerged, 0, 0, $TransparentColor);
    imagecopy($HeadMerged, $Head, 0, 0, 0, 0, 256, 256);
    imagecopy($HeadMerged, $Hair, 0, 0, 0, 0, 256, 256);
    $Amplify = $HeadMerged;
    // 第五步: 阴影
    $Luminous = imagecreatetruecolor(256, 256);
    imagesavealpha($Luminous, true);
    $TransparentColor = imagecolorallocatealpha($Luminous, 0, 0, 0, 127);
    imagefill($Luminous, 0, 0, $TransparentColor);
    $R = hexdec(substr($LuminousColor, 0, 2));
    $G = hexdec(substr($LuminousColor, 2, 2));
    $B = hexdec(substr($LuminousColor, 4, 2));
    for ($X = 0; $X < 256; $X++) {
      for ($Y = 0; $Y < 256; $Y++) {
        $Color = imagecolorat($Amplify, $X, $Y);
        $Alpha = ($Color & 0x7F000000) >> 24;
        if ($Alpha === 0) {
          imagesetpixel($Luminous, $X, $Y, imagecolorallocatealpha($Luminous, $R, $G, $B, 80));
        }
      }
    }
    $LuminousAmplify = imagecreatetruecolor(264, 264);
    imagealphablending($LuminousAmplify, false);
    imagesavealpha($LuminousAmplify, true);
    imagecopyresampled($LuminousAmplify, $Luminous, 0, 0, 0, 0, 264, 264, 256, 256);
    imagecopyresampled($Luminous, $LuminousAmplify, 0, 0, 4, 4, 256, 256, 256, 256);
    // 第六步: 阴影与头像合并
    $LuminousMerged = imagecreatetruecolor(256, 256);
    imagesavealpha($LuminousMerged, true);
    $TransparentColor = imagecolorallocatealpha($LuminousMerged, 255, 255, 255, 127);
    imagefill($LuminousMerged, 0, 0, $TransparentColor);
    imagecopy($LuminousMerged, $Luminous, 0, 0, 0, 0, 256, 256);
    imagecopy($LuminousMerged, $HeadMerged, 0, 0, 0, 0, 256, 256);
    // 第七步: 背景颜色
    $Background = imagecreatetruecolor(256, 256);
    imagesavealpha($Background, true);
    $R = hexdec(substr($BackgroundColor, 0, 2));
    $G = hexdec(substr($BackgroundColor, 2, 2));
    $B = hexdec(substr($BackgroundColor, 4, 2));
    $TransparentColor = imagecolorallocate($Background, $R, $G, $B);
    imagefill($Background, 0, 0, $TransparentColor);
    // 第七步: 背景颜色与头像合并
    imagecopy($Amplify, $Background, 0, 0, 0, 0, 256, 256);
    imagecopy($Amplify, $LuminousMerged, 0, 0, 0, 0, 256, 256);
    // 第八步: 放大
    $Enlarged = imagecreatetruecolor($Size, $Size);
    imagealphablending($Enlarged, false);
    imagesavealpha($Enlarged, true);
    imagecopyresampled($Enlarged, $Amplify, 0, 0, 0, 0, $Size, $Size, 256, 256);
    if ($Type === 1) {
      header('Content-Type: image/png');
      imagepng($Enlarged);
      // 第九步: 销毁
      imagedestroy($Enlarged);
      exit;
    }
    ob_start();
    imagepng($Enlarged);
    $Response['Data'] = 'data:image/png;base64,' . base64_encode(ob_get_clean());
    // 第九步: 销毁
    imagedestroy($Enlarged);
  }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
