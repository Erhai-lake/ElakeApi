<?php
// 新浪新闻
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 数量
    $Limit = (int)$Auth->RangeIntParameters('Limit', 1, 50);
    // 页数
    $Page = (int)$Auth->MinRangeIntParameters('Page', 1);
}

if ($ValidRequest) {
    $Parameters = [
        'pageid' => '153',
        'lid' => '2509',
        'num' => $Limit,
        'page' => $Page
    ];
    $NewsJson = json_decode($Auth->Curl('GET', 'https://feed.mix.sina.com.cn/api/roll/get', $Parameters), true)['result']['data'];
    $News = [];
    foreach ($NewsJson as $Item) {
        $News[] = [
            'Title' => $Item['title'],
            'Intro' => $Item['intro'],
            'Url' => $Item['url'],
            'Time' => date('Y-m-d', $Item['ctime'])
        ];
    }
    $Response['Data'] = $News;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
