<?php
// 获取Minecraft新闻
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 类型
    $Type = (int)$Auth->RangeIntParameters('Type', 1, 5);
    // 页数
    $Page = (int)$Auth->MinRangeIntParameters('Page', 1);
    // 数量
    $Limit = (int)$Auth->RangeIntParameters('Limit', 1, 50);
}

if ($ValidRequest) {
    switch ($Type) {
        case 1:
            // Java
            $DataJson = json_decode($Auth->Curl('GET', 'https://launchercontent.mojang.com/javaPatchNotes.json'), true)['entries'];
            $Page = ($Page - 1) * $Limit;
            $Limit = $Page + $Limit;
            if ($Limit > count($DataJson)) {
                $Limit = count($DataJson);
            }
            $Data = [];
            for ($I = $Page; $I < $Limit; $I++) {
                $Data[] = [
                    'Id' => $DataJson[$I]['id'],
                    'Title' => $DataJson[$I]['title'],
                    'Type' => $DataJson[$I]['type'],
                    'Version' => $DataJson[$I]['version'],
                    'Cover' => [
                        'Title' => $DataJson[$I]['image']['title'],
                        'Url' => 'https://launchercontent.mojang.com' . $DataJson[$I]['image']['url']
                    ],
                    'Body' => $DataJson[$I]['body'],
                    'Content' => 'https://launchercontent.mojang.com/' . $DataJson[$I]['contentPath']
                ];
            }
            $Response['Data'] = [
                'News' => $Data,
                'Total' => count($DataJson)
            ];
            break;
        case 2:
            // Bedrock
            $DataJson = json_decode($Auth->Curl('GET', 'https://launchercontent.mojang.com/bedrockPatchNotes.json'), true)['entries'];
            $Page = ($Page - 1) * $Limit;
            $Limit = $Page + $Limit;
            if ($Limit > count($DataJson)) {
                $Limit = count($DataJson);
            }
            $Data = [];
            for ($I = $Page; $I < $Limit; $I++) {
                $Data[] = [
                    'Id' => $DataJson[$I]['id'],
                    'Title' => $DataJson[$I]['title'],
                    'Date' => $DataJson[$I]['date'],
                    'Type' => $DataJson[$I]['patchNoteType'],
                    'Version' => $DataJson[$I]['version'],
                    'Cover' => [
                        'Title' => $DataJson[$I]['image']['title'],
                        'Url' => 'https://launchercontent.mojang.com' . $DataJson[$I]['image']['url']
                    ],
                    'Body' => $DataJson[$I]['body'],
                    'Content' => 'https://launchercontent.mojang.com/' . $DataJson[$I]['contentPath']
                ];
            }
            $Response['Data'] = [
                'News' => $Data,
                'Total' => count($DataJson)
            ];
            break;
        case 3:
            // Dungeons
            $DataJson = json_decode($Auth->Curl('GET', 'https://launchercontent.mojang.com/dungeonsPatchNotes.json'), true)['entries'];
            $Page = ($Page - 1) * $Limit;
            $Limit = $Page + $Limit;
            if ($Limit > count($DataJson)) {
                $Limit = count($DataJson);
            }
            $Data = [];
            for ($I = $Page; $I < $Limit; $I++) {
                $Data[] = [
                    'Id' => $DataJson[$I]['id'],
                    'Title' => $DataJson[$I]['title'],
                    'Date' => $DataJson[$I]['date'],
                    'Version' => $DataJson[$I]['version'],
                    'Cover' => [
                        'Title' => $DataJson[$I]['image']['title'],
                        'Url' => 'https://launchercontent.mojang.com' . $DataJson[$I]['image']['url']
                    ],
                    'Body' => $DataJson[$I]['body'],
                    'Content' => 'https://launchercontent.mojang.com/' . $DataJson[$I]['contentPath']
                ];
            }
            $Response['Data'] = [
                'News' => $Data,
                'Total' => count($DataJson)
            ];
            break;
        case 4:
            // Legends
            $DataJson = json_decode($Auth->Curl('GET', 'https://launchercontent.mojang.com/legendsPatchNotes.json'), true)['entries'];
            $Page = ($Page - 1) * $Limit;
            $Limit = $Page + $Limit;
            if ($Limit > count($DataJson)) {
                $Limit = count($DataJson);
            }
            $Data = [];
            for ($I = $Page; $I < $Limit; $I++) {
                $Data[] = [
                    'Id' => $DataJson[$I]['id'],
                    'Title' => $DataJson[$I]['title'],
                    'Date' => $DataJson[$I]['date'],
                    'Type' => $DataJson[$I]['patchNoteType'],
                    'Version' => $DataJson[$I]['version'],
                    'Cover' => [
                        'Title' => $DataJson[$I]['image']['title'],
                        'Url' => 'https://launchercontent.mojang.com' . $DataJson[$I]['image']['url']
                    ],
                    'Body' => $DataJson[$I]['body'],
                    'Content' => 'https://launchercontent.mojang.com/' . $DataJson[$I]['contentPath']
                ];
            }
            $Response['Data'] = [
                'News' => $Data,
                'Total' => count($DataJson)
            ];
            break;
        case 5:
            // MoJang
            $DataJson = json_decode($Auth->Curl('GET', 'https://launchercontent.mojang.com/news.json'), true)['entries'];
            $Page = ($Page - 1) * $Limit;
            $Limit = $Page + $Limit;
            if ($Limit > count($DataJson)) {
                $Limit = count($DataJson);
            }
            $Data = [];
            for ($I = $Page; $I < $Limit; $I++) {
                $Data[] = [
                    'Id' => $DataJson[$I]['id'],
                    'Title' => $DataJson[$I]['title'],
                    'Date' => $DataJson[$I]['date'],
                    'Category' => $DataJson[$I]['category'],
                    'Type' => $DataJson[$I]['newsType'],
                    'Content' => $DataJson[$I]['text'],
                    'Cover' => [
                        [
                            'Title' => $DataJson[$I]['playPageImage']['title'],
                            'Url' => 'https://launchercontent.mojang.com' . $DataJson[$I]['playPageImage']['url'],
                        ],
                        [
                            'Title' => $DataJson[$I]['newsPageImage']['title'],
                            'Url' => 'https://launchercontent.mojang.com' . $DataJson[$I]['newsPageImage']['url'],
                            'Dimensions' => [
                                'Width' => $DataJson[$I]['newsPageImage']['dimensions']['width'],
                                'Height' => $DataJson[$I]['newsPageImage']['dimensions']['height']
                            ]
                        ]
                    ],
                    'Content' => $DataJson[$I]['readMoreLink']
                ];
            }
            $Response['Data'] = [
                'News' => $Data,
                'Total' => count($DataJson)
            ];
            break;
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
