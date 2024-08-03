<?php
// 虎扑步行街热帖
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    $Pattern = '/<div class="t-info">(.*?)<\/div>/s';
    preg_match_all($Pattern, $Auth->Curl('GET', 'https://bbs.hupu.com/all-gambia'), $Matches);
    $Data = [];
    $Pattern = '/<a href="(.*?)".*?<span class="t-title">(.*?)<\/span>/s';
    foreach ($Matches[1] as $Item) {
        preg_match($Pattern, $Item, $MatchesItem);
        $Data[] = [
            'Title' => $MatchesItem[2],
            'URL' => 'https://bbs.hupu.com' . $MatchesItem[1]
        ];
        $Response['Data'] = $Data;
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
