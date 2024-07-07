<?php
// 搜索
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 关键词
    $Value = (string)$Auth->StringParameters('Value');
    // 页数
    $Page = (int)$Auth->MinRangeIntParameters('Page', 1, 1);
}

if ($ValidRequest) {
    $Parameters = [
        'keyword' => $Value,
        'page' => $Page
    ];
    $Pattern = '/<ul class="qm-pic-txt pic-150-200".*?>(.*?)<\/ul>/s';
    preg_match($Pattern, $Auth->Curl('https://www.qimao.com/search/index/', $Parameters), $Matches);
    $Pattern = '/<li>.*?<div class="pic">.*?<a href="\/shuku\/(.*?)\/">.*?<img.*?src="(.*?) .*?<div class="txt">.*?<span class="s-tit">.*?<a.*?>(.*?)<\/a>.*?<span class="s-tags qm-tags clearfix".*?<a.*?>(.*?)<\/a>(.*?)\n.*?<\/span>.*?<span class="s-des">        (.*?)<\/span>.*?<p class="p-bottom">.*?<a.*?>(.*?)<\/a>.*?<\/li>/s';
    preg_match_all($Pattern, $Matches[0], $MatchesItem);
    for ($I = 0; $I < count($MatchesItem[0]); $I++) {
        $Data[] = [
            'Id' => $MatchesItem[1][$I],
            'Title' => str_replace("<i class='red'>", '', str_replace('</i>', '', $MatchesItem[3][$I])),
            'Author' => str_replace("<i class='red'>", '', str_replace('</i>', '', $MatchesItem[7][$I])),
            'Tag' => $MatchesItem[4][$I],
            'WordsNum' => str_replace('&nbsp;', '', str_replace('完结', '', str_replace('连载中', '', $MatchesItem[5][$I]))),
            'Description' => $MatchesItem[6][$I],
            'Url' => 'https://www.qimao.com/shuku/' . $MatchesItem[1][$I],
            'Cover' => $MatchesItem[2][$I]
        ];
    }
    $Response['Data'] = $Data;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
