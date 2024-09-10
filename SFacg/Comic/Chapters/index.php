<?php
// 获取章节
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // ID
    $ID = (string)$Auth->StringParameters('ID');
}

if ($ValidRequest) {
    $Pattern = '/<div class="comic_Serial_list">(.*?)<\/div>/s';
    preg_match_all($Pattern, $Auth->Curl('GET', 'https://manhua.sfacg.com/mh/' . $ID), $ReelMatches);
    $Pattern2 = '/<a href="(.*?)" .*?>(.*?)<\/a>/s';
    $Pattern3 = '/<b>VIP<\/b>/s';
    for ($I = 0; $I < count($ReelMatches[0]); $I++) {
        preg_match_all($Pattern2, $ReelMatches[1][$I], $Matches);
        $Chapters = [];
        for ($J = 0; $J < count($Matches[0]); $J++) {
            if (preg_match($Pattern3, $Matches[2][$J])) {
                $Vip = true;
                $Title = preg_replace($Pattern3, '', $Matches[2][$J]);
            } else {
                $Vip = false;
                $Title = $Matches[2][$J];
            }
            $Title = preg_replace('/<font color="red">/s', '', $Title);
            $Title = preg_replace('/<\/font>/s', '', $Title);
            if (substr($Matches[1][$J], 0, 7) === 'http://' || substr($Matches[1][$J], 0, 8) === 'https://') {
                $Url = $Matches[1][$J];
            } else {
                $Url = 'https://manhua.sfacg.com' . $Matches[1][$J];
            }
            $Chapters[] = [
                'Title' => $Title,
                'Url' => $Url,
                'Vip' => $Vip
            ];
        }
    }
    $Response['Data'] = $Chapters;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
