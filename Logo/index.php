<?php
// 说明
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate(true)) {
    // 颜色类型
    $ColorType = (int)$Auth->RangeIntParameters('ColorType', 1, 2, 2);
    // 尺寸
    $Size = (int)$Auth->RangeIntParameters('Size', 128, 2048, 256);
}

if ($ValidRequest) {
    $ImagePath = $ColorType === 1 ? '纯净白.png' : '洱海蓝.png';
    $Image = imagecreatefrompng($ImagePath);
    // 获取原始图片的宽度和高度
    $Width = imagesx($Image);
    $Height = imagesy($Image);
    // 存放调整大小后的图片
    $ResizedImage = imagecreatetruecolor($Size, $Size);
    // 启用alpha通道透明度混合
    imagealphablending($ResizedImage, false);
    imagesavealpha($ResizedImage, true);
    imagecopyresampled($ResizedImage, $Image, 0, 0, 0, 0, $Size, $Size, $Width, $Height);
    // 输出
    header('Content-Type: PNG');
    imagepng($ResizedImage);
    // 释放
    imagedestroy($Image);
    imagedestroy($ResizedImage);
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
