<?php
// 小说正文
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 小说ID
    $NovelId = (string)$Auth->StringParameters('NovelId');
    // 章节ID
    $ChapterId = (string)$Auth->StringParameters('ChapterId');
}

if ($ValidRequest) {
    $Pattern = '/<div class="article".*?>(.*?)<\/div>/s';
    preg_match($Pattern, $Auth->Curl('https://www.qimao.com/shuku/' . $NovelId . '-' . $ChapterId . '/'), $Matches);
    $Pattern = '/<p>(.*?)<\/p>/s';
    preg_match_all($Pattern, $Matches[1], $MatchesItem);
    $Response['Data'] = $MatchesItem[1];
    if (empty($Response['Data'])) {
        $Auth->Custom('无法获取,可能是需要VIP,我没钱购入VIP,所以没法抓,抱歉...');
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
