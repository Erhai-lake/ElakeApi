<?php
// 获取番剧列表
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 季度
    $Quarter = (int)$Auth->IntParameters('Quarter');
    // 种类
    $Type = (int)$Auth->IntParameters('Type', 0);
    // 风格
    $Style = (int)$Auth->IntParameters('Style', 0);
    // 配音演员
    $CV = (int)$Auth->IntParameters('CV', 0);
}

if ($ValidRequest) {
    $Parameters = [
        'type' => $Type,
        'style' => $Style,
        'cv_name' => $CV,
        'quarter' => $Quarter
    ];
    $Header = [
        'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
        'Language: zh-CN'
    ];
    $DataJson = json_decode($Auth->Curl('GET', 'https://api.miroko.cn/api/anime-new/list', $Parameters, $Header), true);
    if ($DataJson['code'] !== 200001) {
        $Auth->Return(5);
    } else {
        $Week1 = [];
        foreach ($DataJson['data']['week']['1'] as $Item) {
            $Week1[] = [
                'Id' => $Item['id'],
                'Name' => $Item['name'],
                'Cover' => $Item['cover'],
                'Date' => $Item['show_month_day'] . ' ' . $Item['show_time']
            ];
        }
        $Week2 = [];
        foreach ($DataJson['data']['week']['1'] as $Item) {
            $Week2[] = [
                'Id' => $Item['id'],
                'Name' => $Item['name'],
                'Cover' => $Item['cover'],
                'Date' => $Item['show_month_day'] . ' ' . $Item['show_time']
            ];
        }
        $Week3 = [];
        foreach ($DataJson['data']['week']['1'] as $Item) {
            $Week3[] = [
                'Id' => $Item['id'],
                'Name' => $Item['name'],
                'Cover' => $Item['cover'],
                'Date' => $Item['show_month_day'] . ' ' . $Item['show_time']
            ];
        }
        $Week4 = [];
        foreach ($DataJson['data']['week']['1'] as $Item) {
            $Week4[] = [
                'Id' => $Item['id'],
                'Name' => $Item['name'],
                'Cover' => $Item['cover'],
                'Date' => $Item['show_month_day'] . ' ' . $Item['show_time']
            ];
        }
        $Week5 = [];
        foreach ($DataJson['data']['week']['1'] as $Item) {
            $Week5[] = [
                'Id' => $Item['id'],
                'Name' => $Item['name'],
                'Cover' => $Item['cover'],
                'Date' => $Item['show_month_day'] . ' ' . $Item['show_time']
            ];
        }
        $Week6 = [];
        foreach ($DataJson['data']['week']['1'] as $Item) {
            $Week6[] = [
                'Id' => $Item['id'],
                'Name' => $Item['name'],
                'Cover' => $Item['cover'],
                'Date' => $Item['show_month_day'] . ' ' . $Item['show_time']
            ];
        }
        $Week7 = [];
        foreach ($DataJson['data']['week']['1'] as $Item) {
            $Week7[] = [
                'Id' => $Item['id'],
                'Name' => $Item['name'],
                'Cover' => $Item['cover'],
                'Date' => $Item['show_month_day'] . ' ' . $Item['show_time']
            ];
        }
        $Data = [];
        foreach ($DataJson['data']['info'] as $Item) {
            $PlayAddress = [];
            foreach ($Item['play_address'] as $PlayAddressItem) {
                $PlayAddress[] = [
                    'Id' => $PlayAddressItem['anime_play_net_id'],
                    'Area' => $PlayAddressItem['area'],
                    'Address' => $PlayAddressItem['address'],
                    'Logo' => $PlayAddressItem['logo']
                ];
            }
            $PVAddress = [];
            foreach ($Item['pv_address'] as $PVAddressItem) {
                $PVAddress[] = [
                    'Cover' => $PVAddressItem['cover'],
                    'Address' => 'https://www.bilibili.com/video/' . $PVAddressItem['address']
                ];
            }
            $CV = [];
            foreach ($Item['cv'] as $CVItem) {
                $CV[] = trim($CVItem, "\r\n");
            }
            $Staff = [];
            foreach ($Item['staff'] as $StaffItem) {
                $Staff[] = trim($StaffItem, "\r\n");
            }
            $Data[] = [
                'Id' => $Item['id'],
                'Name' => $Item['name'],
                'NameJP' => $Item['name_jp'],
                'Cover' => $Item['cover'],
                'Type' => $Item['type'],
                'Tag' => json_decode($Item['anime_tag_id'], true),
                'Date' => $Item['show_month_day'] . ' ' . $Item['show_time'],
                'Week' => $Item['show_week'],
                'Intro' => $Item['intro'],
                'PlayAddress' => $PlayAddress,
                'PVAddress' => $PVAddress,
                'CV' => $CV,
                'Staff' => $Staff,
                'EpisodeSum' => $Item['episode_sum'],
                'OfficialWeb' => $Item['official_web']
            ];
        }
        $Response['Data'] = [
            'Week' => [
                '1' => $Week1,
                '2' => $Week2,
                '3' => $Week3,
                '4' => $Week4,
                '5' => $Week5,
                '6' => $Week6,
                '7' => $Week7,
            ],
            'Info' => $Data
        ];
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
