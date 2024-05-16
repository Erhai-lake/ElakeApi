<?php
// 二维码生成
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelMedium;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelQuartile;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;

if ($Auth->Authenticate()) {
  // 内容
  $Value = (string)$Auth->StringParameters('Value');
  // 尺寸
  $Size = (int)$Auth->IntParameters('Size');
  // 边距
  $Margins = (int)$Auth->IntParameters('Margins');
  // 类型
  $Type = (int)$Auth->RangeIntParameters('Type', 1, 2);
  // 纠错等级
  $ECL = (int)$Auth->RangeIntParameters('ECL', 1, 4);
  // Logo
  $Logo = (string)$Auth->StringParameters('Logo', '');
  // logo尺寸
  $LogoSize = (int)$Auth->IntParameters('LogoSize', 0);
  // 底部文本
  $Text = (string)$Auth->StringParameters('Text', '');
  // 文本尺寸
  $TextSize = (int)$Auth->IntParameters('TextSize', 0);
  // 背景颜色
  $BackgroundColor = (string)$Auth->StringParameters('BackgroundColor', '');
  // 前景颜色
  $ForegroundColor = (string)$Auth->StringParameters('ForegroundColor', '');
}

if ($ValidRequest) {
  $QRCode = Builder::create()
    ->writer(new PngWriter())
    ->writerOptions([])
    ->data($Value)
    ->encoding(new Encoding('UTF-8'))
    ->size($Size)
    ->margin($Margins)
    ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
    ->validateResult(false);
  switch ($ECL) {
    case 1:
      $QRCode->errorCorrectionLevel(new ErrorCorrectionLevelHigh());
      break;
    case 2:
      $QRCode->errorCorrectionLevel(new ErrorCorrectionLevelLow());
      break;
    case 3:
      $QRCode->errorCorrectionLevel(new ErrorCorrectionLevelMedium());
      break;
    case 4:
      $QRCode->errorCorrectionLevel(new ErrorCorrectionLevelQuartile());
      break;
  }
  if ($Logo !== '') {
    $QRCode->logoPath($Logo);
    $QRCode->logoResizeToWidth($LogoSize);
    $QRCode->logoResizeToHeight($LogoSize);
  }
  if ($Text !== '') {
    $QRCode->labelText($Text);
    $QRCode->labelFont(new NotoSans($TextSize));
    $QRCode->labelAlignment(new LabelAlignmentCenter());
  }
  if ($BackgroundColor !== '') {
    $Color =  [
      hexdec(substr($BackgroundColor, 0, 2)),
      hexdec(substr($BackgroundColor, 2, 2)),
      hexdec(substr($BackgroundColor, 4, 2))
    ];
    $QRCode->backgroundColor(new Color($Color[0], $Color[1], $Color[2]));
  }
  if ($ForegroundColor !== '') {
    $Color =  [
      hexdec(substr($ForegroundColor, 0, 2)),
      hexdec(substr($ForegroundColor, 2, 2)),
      hexdec(substr($ForegroundColor, 4, 2))
    ];
    $QRCode->foregroundColor(new Color($Color[0], $Color[1], $Color[2]));
  }
  $QRCode = $QRCode->build();
  if ($Type === 1) {
    header('Content-Type: image/png');
    echo $QRCode->getString();
    exit;
  } elseif ($Type === 2) {
    $Response['Data'] = 'data:image/png;base64,' . base64_encode($QRCode->getString());
  }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
