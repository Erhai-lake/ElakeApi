<?php
// 获取番茄小说
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate(true)) {
    // 番茄小说ID
    $ID = (string)$Auth->StringParameters('ID');
}

if ($ValidRequest) {
    $Dictionary = json_decode($Auth->Curl('GET', '//api.elake.top/FanQieNovel/Dictionary.json'), true);
    $Pattern = '/<div class="muye-reader-box font-DNMrHsV173Pd4pgy muye-reader-content-16.*?>(.*?)<\/button><\/div><\/div>/s';
    preg_match($Pattern, $Auth->Curl('GET', 'https://fanqienovel.com/reader/' . $ID), $Matches);
    $Pattern = '/<h1 class="muye-reader-title">(.*?)<\/h1>.*?<\/span>(.*?)<!-- -->字.*?<\/span>.*?<\/span>(.*?)<\/span>.*?<div>(.*?)<\/div>/s';
    preg_match($Pattern, $Matches[1], $ContentMatches);
    preg_match_all('/<p>(.*?)<\/p>/s', $ContentMatches[4], $NovelMatches);
    $Utf16Units = [];
    for ($i = 0; $i < count($NovelMatches[1]); $i++) {
        $Utf16Unit = PrintUtf16Units($NovelMatches[1][$i]);
        $Row = '';
        for ($j = 0; $j < count($Utf16Unit); $j++) {
            if ($Dictionary[$Utf16Unit[$j][1]] !== null) {
                $Row .= $Dictionary[$Utf16Unit[$j][1]];
            } else {
                $Row .= '<' . $Utf16Unit[$j][1] . '>';
                $Auth->Return(6, '词库未收录该字,已用占位符代替,还请自行添加该字符,你的添加会被记录,感谢你的付出! 字符: ' . $Utf16Unit[$j][0] . ' 码点: ' . $Utf16Unit[$j][1]);
            }
        }
        $Utf16Units[] = $Row;
    }
    $Response['Data'] = [
        'Title' => $ContentMatches[1],
        'Number' => $ContentMatches[2],
        'UpdateTime' => $ContentMatches[3],
        'Content' => $Utf16Units
    ];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);

function Utf16Encode($Str)
{
    $Result = [];
    $Strlen = mb_strlen($Str, 'UTF-8');
    for ($i = 0; $i < $Strlen; $i++) {
        $Char = mb_substr($Str, $i, 1, 'UTF-8');
        $Utf16Char = mb_convert_encoding($Char, 'UTF-16BE', 'UTF-8');
        $Utf16Units = unpack('n*', $Utf16Char);
        if (count($Utf16Units) == 1) {
            $Utf16Units = [$Utf16Units[1]];
        }
        $Result[] = $Utf16Units;
    }
    return $Result;
}

function PrintUtf16Units($Str)
{
    $Array = [];
    $Utf16Units = Utf16Encode($Str);
    foreach ($Utf16Units as $Index => $Units) {
        $Char = mb_substr($Str, $Index, 1, 'UTF-8');
        $UnitStrings = array_map(function ($Unit) {
            return dechex($Unit);
        }, $Units);
        $Array[] = [
            $Char,
            implode(' ', $UnitStrings)
        ];
    }
    return $Array;
}
