<?php
// 详细
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 书本ID
    $Id = (string)$Auth->StringParameters('Id');
}

if ($ValidRequest) {
    $Html = $Auth->Curl('https://www.qimao.com/shuku/' . $Id . '/');
    $PatternMain = '/<div class="book-information clearfix left".*?>(.*?)<\/div><\/div>/s';
    preg_match($PatternMain, $Html, $MatchesMain);
    $PatternTitle = '/<div class="title clearfix".*?<span class="txt".*?>(.*?)<\/span>/s';
    preg_match($PatternTitle, $MatchesMain[1], $MatchesTitle);
    $PatternAuthor = '/<div class="sub-title".*?<span class="txt".*?<em.*?<a.*?>\n                        (.*?)\n                    <\/a>/s';
    preg_match($PatternAuthor, $MatchesMain[1], $MatchesAuthor);
    $PatternWordsNum = '/<div class="statistics-wrap".*?<span class="txt".*?<em.*?>(.*?)<\/em>(.*?)<\/span>/s';
    preg_match($PatternWordsNum, $MatchesMain[1], $MatchesWordsNum);
    $PatternDescription = '/<div class="qm-with-title-tb".*?<p.*?>(.*?)<\/p>/s';
    preg_match($PatternDescription, $Html, $MatchesDescription);
    $PatternCover = '/<div class="wrap-pic".*?<img src="(.*?)"/s';
    preg_match($PatternCover, $MatchesMain[1], $MatchesCover);
    $Parameters = [
        'book_id' => $Id
    ];
    $Json = json_decode($Auth->Curl('https://www.qimao.com/api/book/chapter-list', $Parameters), true);
    $Chapter = [];
    foreach ($Json['data']['chapters'] as $Item) {
        $Chapter[] = [
            'Id' => $Item['id'],
            'Title' => $Item['title'],
            'VIP' => $Item['is_vip'] === '0' ? false : true
        ];
    }
    $Response['Data'] = [
        'Id' => $Id,
        'Title' => $MatchesTitle[1],
        'Author' => $MatchesAuthor[1],
        'WordsNum' => $MatchesWordsNum[1] . $MatchesWordsNum[2],
        'Description' => $MatchesDescription[1],
        'Url' => 'https://www.qimao.com/shuku/' . $Id,
        'Cover' => $MatchesCover[1],
        'Chapter' => $Chapter
    ];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
