<?php
// 获取分类
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 季度ID
  $Quarter = (int)$Auth->IntParameters('Quarter');
}

if ($ValidRequest) {
  $Parameters = [
    'quarter' => $Quarter
  ];
  $Header = [
    'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
    'Language: zh-CN'
  ];
  $DataJson = json_decode($Auth->Curl('GET', 'https://api.miroko.cn/api/anime-new/top-info', $Parameters, $Header), true);
  if ($DataJson['code'] !== 200001) {
    $Auth->Return(5);
  } else {
    $Response['Data'] = [
      'Year' => $DataJson['data']['year'],
      'Cover' => $DataJson['data']['cover'],
      'Count' => [
        'Original' => $DataJson['data']['anime_count']['original'],
        'Comic' => $DataJson['data']['anime_count']['comic'],
        'Novel' => $DataJson['data']['anime_count']['novel'],
        'Game' => $DataJson['data']['anime_count']['game'],
        'Other' => $DataJson['data']['anime_count']['other'],
        'Sum' => $DataJson['data']['anime_count']['sum']
      ],
      'Classification' => [
        'Type' => $DataJson['data']['filtrate']['type'],
        'Tag' => $DataJson['data']['filtrate']['tag'],
        'CV' => $DataJson['data']['filtrate']['cv']
      ]
    ];
  }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
