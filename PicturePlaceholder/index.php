<?php
// 图片占位符
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 高度
    $Height = (int)$Auth->RangeIntParameters('Height', 1, 4096, 64);
    //宽度
    $Width = (int)$Auth->RangeIntParameters('Width', 1, 4096, 64);
    // 类型
    $Type = (int)$Auth->RangeIntParameters('Type', 1, 2);
}

if ($ValidRequest) {
    // 背景
    $Image = imagecreatetruecolor($Width, $Height);
    // 背景颜色
    $BackgroundColor = imagecolorallocate($Image, 204, 204, 204);
    imagefilledrectangle($Image, 0, 0, $Width, $Height, $BackgroundColor);
    // 文本内容
    $Text = "{$Width}x{$Height}";
    // 动态计算字体大小
    $FontSize = max(10, min($Width, $Height) / 10);
    // 字体路径
    $FontPath = $_SERVER['DOCUMENT_ROOT'] . '/PicturePlaceholder/Font.ttf';
    // 文字颜色
    $TextColor = imagecolorallocate($Image, 120, 120, 120);
    // 计算文字的位置,确保居中
    $TextBox = imagettfbbox($FontSize, 0, $FontPath, $Text);
    $TextWidth = abs($TextBox[2] - $TextBox[0]);
    $TextHeight = abs($TextBox[7] - $TextBox[1]);
    $X = ($Width - $TextWidth) / 2;
    $Y = ($Height + $TextHeight) / 2;
    // 写入文字
    imagettftext($Image, $FontSize, 0, $X, $Y, $TextColor, $FontPath, $Text);
    ob_start();
    imagepng($Image);
    $Data = ob_get_clean();
    if ($Type === 1) {
        header('Content-Type: PNG');
        echo $Data;
        exit();
    } else {
        $Response['Data'] = 'data:image/png;base64,' . base64_encode($Data);
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
