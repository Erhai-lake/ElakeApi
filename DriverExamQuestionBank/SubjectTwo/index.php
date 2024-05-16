<?php
// 科目二题库
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {}

if ($ValidRequest) {
  $Response['Data'] = [
    [
      'Title' => '倒车入库',
      'Video' => 'https://sp.mnks.cn/km23/dcrk.mp4'
    ],
    [
      'Title' => '坡道定点停车和起步',
      'Video' => 'https://sp.mnks.cn/km23/pdqb.mp4'
    ],
    [
      'Title' => '侧方停车',
      'Video' => 'https://sp.mnks.cn/km23/cftc.mp4'
    ],
    [
      'Title' => '曲线行驶',
      'Video' => 'https://sp.mnks.cn/km23/qxxs.mp4'
    ],
    [
      'Title' => '直角转弯',
      'Video' => 'https://sp.mnks.cn/km23/zjzw.mp4'
    ],
    [
      'Title' => '模拟高速公路停车取卡',
      'Video' => 'https://sp.mnks.cn/km23/tcqk.mp4'
    ],
    [
      'Title' => '模拟隧道行驶',
      'Video' => 'https://sp.mnks.cn/km23/mnsd.mp4'
    ],
    [
      'Title' => '模拟紧急情况处置',
      'Video' => 'https://sp.mnks.cn/km23/jjqk.mp4'
    ],
    [
      'Title' => '模拟雨雾天湿滑路面',
      'Video' => 'https://sp.mnks.cn/km23/mnywt.mp4'
    ]
  ];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
