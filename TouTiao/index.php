<?php
// 今日头条
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    $Parameters = [
        'origin' => 'toutiao_pc'
    ];
    $Json = json_decode($Auth->Curl('GET', 'https://www.toutiao.com/hot-event/hot-board', $Parameters), true);
    $Data = [];
    foreach ($Json['fixed_top_data'] as $Item) {
        $Data[] = [
            'Title' => $Item['Title'],
            'URL' => $Item['Url']
        ];
        $Response['Data'] = $Data;
    }
    foreach ($Json['data'] as $Item) {
        $Data[] = [
            'Title' => $Item['Title'],
            'URL' => 'https://www.toutiao.com/trending/' . $Item['ClusterId'],
            'Cover' => $Item['Image']['url']
        ];
        $Response['Data'] = $Data;
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
