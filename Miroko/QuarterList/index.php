<?php
// 获取季度列表
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 页数
  $Page = (int)$Auth->IntParameters('Page');
  // 数量
  $Limit = (int)$Auth->IntParameters('Limit');
}

if ($ValidRequest) {
  $Parameters = [
    'page' => $Page,
    'limit' => $Limit
  ];
  $Header = [
    'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
    'Language: zh-CN'
  ];
  $DataJson = json_decode($Auth->Curl('GET', 'https://api.miroko.cn/api/anime-new/past-list', $Parameters, $Header), true);
  if ($DataJson['code'] !== 200001) {
    $Auth->Return(5);
  } else {
    $Data = [];
    foreach ($DataJson['data']['list'] as $Item) {
      $Data[] = [
        'Year' => $Item['year'],
        'Quarter' => $Item['quarter'],
        'Cover' => $Item['cover']
      ];
    }
    $Response['Data'] = [
      'List' => $Data,
      'Total' => $DataJson['data']['total']
    ];
  }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
