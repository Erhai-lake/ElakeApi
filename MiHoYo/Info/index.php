<?php
// 获取米哈游游戏信息
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    $Parameters = [
        'launcher_id' => 'jGHBHlcOq1',
        'language' => 'zh-cn'
    ];
    $GamesJson = json_decode($Auth->Curl('GET', 'https://hyp-api.mihoyo.com/hyp/hyp-connect/api/getGames', $Parameters), true);
    $Data = [];
    foreach ($GamesJson['data']['games'] as $Item) {
        $Parameters = [
            'launcher_id' => 'jGHBHlcOq1',
            'language' => 'zh-cn',
            'game_id' => $Item['id']
        ];
        $GameBasicInfoJson = json_decode($Auth->Curl('GET', 'https://hyp-api.mihoyo.com/hyp/hyp-connect/api/getAllGameBasicInfo', $Parameters), true);
        $Parameters = [
            'launcher_id' => 'jGHBHlcOq1',
            'language' => 'zh-cn',
            'game_id' => $Item['id']
        ];
        $GameContentJson = json_decode($Auth->Curl('GET', 'https://hyp-api.mihoyo.com/hyp/hyp-connect/api/getGameContent', $Parameters), true);
        $GameContentBanners = [];
        foreach ($GameContentJson['data']['content']['banners'] as $GameContentBannersItem) {
            $GameContentBanners[] = [
                'Id' => $GameContentBannersItem['id'],
                'Image' => $GameContentBannersItem['image']['url'],
                'Url' => $GameContentBannersItem['image']['link']
            ];
        }
        $GameContentPosts = [];
        foreach ($GameContentJson['data']['content']['posts'] as $GameContentPostsItem) {
            switch ($GameContentPostsItem['type']) {
                case 'POST_TYPE_ACTIVITY':
                    $Type = '活动';
                    break;
                case 'POST_TYPE_INFO':
                    $Type = '资讯';
                    break;
                case 'POST_TYPE_ANNOUNCE':
                    $Type = '公告';
                    break;
            }
            $GameContentPosts[] = [
                'Id' => $GameContentPostsItem['id'],
                'Type' => $Type,
                'Title' => $GameContentPostsItem['title'],
                'Url' => $GameContentPostsItem['link'],
                'Date' => str_replace('/', '-', $GameContentPostsItem['date'])
            ];
        }
        $Data[] = [
            $Item['id'] => [
                'Name' => $Item['display']['name'],
                'Icon' => $Item['display']['icon']['url'],
                'Logo' => $Item['display']['logo']['url'],
                'Background' => $Item['display']['background']['url'],
                'LauncherBackground' => $GameBasicInfoJson['data']['game_info_list'][0]['backgrounds'][0]['background']['url'],
                'GameContent' => [
                    'Banners' => $GameContentBanners,
                    'Posts' => $GameContentPosts
                ]
            ]
        ];
    }
    $Response['Data'] = $Data;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
