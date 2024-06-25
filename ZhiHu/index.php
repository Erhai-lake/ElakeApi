<?php
// 知乎热榜
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate(true)) {
}

if ($ValidRequest) {
    $Json = json_decode($Auth->Curl('https://www.zhihu.com/api/v3/feed/topstory/hot-lists/total'), true)['data'];
    $Data = [];
    foreach ($Json as $Item) {
        $Data[] = [
            'Title' => $Item['target']['title'],
            'Hot' => $Item['detail_text'],
            'URL' => 'https://www.zhihu.com/question/' . $Item['target']['id']
        ];
    }
    $Response['Data'] = $Data;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
