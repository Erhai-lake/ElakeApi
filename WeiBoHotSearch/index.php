<?php
// 微博热搜
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    $Json = json_decode($Auth->Curl('https://weibo.com/ajax/side/hotSearch'), true)['data'];
    $Data = [];
    foreach ($Json['hotgovs'] as $Item) {
        $Data[] = [
            'Title' => $Item['name'],
            'URL' => $Item['url']
        ];
        $Response['Data'] = $Data;
    }
    foreach ($Json['realtime'] as $Item) {
        $Data[] = [
            'Title' => $Item['word'],
            'URL' => 'https://s.weibo.com/weibo?q=%23' . $Item['word'] . '%23'
        ];
        $Response['Data'] = $Data;
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
