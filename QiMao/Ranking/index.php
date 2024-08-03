<?php
// 排行榜
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 类型
    $Type = (int)$Auth->RangeIntParameters('Type', 1, 5, 1);
    // 性别
    $Gender = (int)$Auth->RangeIntParameters('Gender', 1, 2, 1) - 1;
    // 日期
    $Date = (int)$Auth->RangeIntParameters('Date', 1, 2, 1);
    // 页数
    $Page = (int)$Auth->RangeIntParameters('Page', 1, 5, 1);
}

if ($ValidRequest) {
    $Parameters = [
        'is_girl' => $Gender,
        'rank_type' => $Type,
        'date_type' => $Date,
        'page' => $Page
    ];
    $Json = json_decode($Auth->Curl('GET', 'https://www.qimao.com/api/rank/book-list', $Parameters), true);
    $Data = [];
    foreach ($Json['data']['table_data'] as $Item) {
        $Data[] = [
            'Id' => $Item['book_id'],
            'Title' => $Item['title'],
            'Author' => $Item['author'],
            'Tag' => $Item['category1_name'] . '-' . $Item['category2_name'],
            'WordsNum' => $Item['words_num'],
            'Description' => $Item['intro'],
            'Url' => $Item['book_url'],
            'Cover' => $Item['image_link']
        ];
    }
    $Response['Data'] = $Data;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
