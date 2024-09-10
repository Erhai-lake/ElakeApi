<?php
// 键轴
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate(true)) {
    // 关键词
    $Name = (string)$Auth->StringParameters('Name', '');
    // 页数
    $Pages = (int)$Auth->IntParameters('Pages', 1);
    // 分类
    $Type = (int)$Auth->RangeIntParameters('Type', 0, 8, 8);
    // 润滑
    $Lube = (int)$Auth->RangeIntParameters('Lube', 0, 3, 3);
    // 导光
    $Light = (int)$Auth->RangeIntParameters('Light', 0, 5, 5);
    // 成色
    $Source = (int)$Auth->RangeIntParameters('Source', 0, 3, 3);
    // 代工
    $Factory = (int)$Auth->RangeIntParameters('Factory', -1, 25, 25);
    // 最低价格
    $MinPrice = (float)$Auth->RangeIntParameters('MinPrice', 0, 41, 41);
    // 最高价格
    $MaxPrice = (float)$Auth->RangeIntParameters('MaxPrice', 0, 41, 41);
    if ($MinPrice > $MaxPrice || $MaxPrice < $MinPrice) {
        $Auth->Return(6, '价格区间错误');
    }
    // 最小触发压力
    $MinForce = (int)$Auth->RangeIntParameters('MinForce', 0, 121, 121);
    // 最大触发压力
    $MaxForce = (int)$Auth->RangeIntParameters('MaxForce', 0, 121, 121);
    if ($MinForce > $MaxForce || $MaxForce < $MinForce) {
        $Auth->Return(6, '触发压力区间错误');
    }
    // 最小触底压力
    $MinTotalForce = (int)$Auth->RangeIntParameters('MinTotalForce', 0, 151, 151);
    // 最大触底压力
    $MaxTotalForce = (int)$Auth->RangeIntParameters('MaxTotalForce', 0, 151, 151);
    if ($MinTotalForce > $MaxTotalForce || $MaxTotalForce < $MinTotalForce) {
        $Auth->Return(6, '触底压力区间错误');
    }
    // 最小触发行程
    $MinTravel = (float)$Auth->RangeIntParameters('MinTravel', 0, 6, 6);
    // 最大触发行程
    $MaxTravel = (float)$Auth->RangeIntParameters('MaxTravel', 0, 6, 6);
    if ($MinTravel > $MaxTravel || $MaxTravel < $MinTravel) {
        $Auth->Return(6, '触底行程区间错误');
    }
    // 最小触底行程
    $MinTotalTravel = (float)$Auth->RangeIntParameters('MinTotalTravel', 0, 6, 6);
    // 最大触底行程
    $MaxTotalTravel = (float)$Auth->RangeIntParameters('MaxTotalTravel', 0, 6, 6);
    if ($MinTotalTravel > $MaxTotalTravel || $MaxTotalTravel < $MinTotalTravel) {
        $Auth->Return(6, '总触底行程区间错误');
    }
    // 排序
    $Sort = (int)$Auth->RangeIntParameters('Sort', 0, 14, 14);
}

if ($ValidRequest) {
    $Parameters = [];
    if ($Name !== '') {
        $Parameters['switch_name'] = $Name;
    }
    if ($Pages !== 1) {
        $Parameters['page_num'] = $Pages;
    }
    if ($Type !== 8) {
        $Parameters['switch_type'] = $Type;
    }
    if ($Lube !== 3) {
        $Parameters['lube'] = $Lube;
    }
    if ($Light !== 5) {
        $Parameters['light'] = $Light;
    }
    if ($Source !== 3) {
        $Parameters['source'] = $Source;
    }
    if ($Factory !== 25) {
        $Parameters['factory'] = $Factory;
    }
    if ($MinPrice !== 41) {
        $Parameters['min_price'] = $MinPrice;
    }
    if ($MaxPrice !== 41) {
        $Parameters['max_price'] = $MaxPrice;
    }
    if ($MinForce !== 121) {
        $Parameters['min_force'] = $MinForce;
    }
    if ($MaxForce !== 121) {
        $Parameters['max_force'] = $MaxForce;
    }
    if ($MinTotalForce !== 151) {
        $Parameters['total_min_force'] = $MinTotalForce;
    }
    if ($MaxTotalForce !== 151) {
        $Parameters['total_max_force'] = $MaxTotalForce;
    }
    if ($MinTravel !== 6) {
        $Parameters['min_travel'] = $MinTravel;
    }
    if ($MaxTravel !== 6) {
        $Parameters['max_travel'] = $MaxTravel;
    }
    if ($MinTotalTravel !== 6) {
        $Parameters['total_min_travel'] = $MinTotalTravel;
    }
    if ($MaxTotalTravel !== 6) {
        $Parameters['total_max_travel'] = $MaxTotalTravel;
    }
    if ($Sort !== 14) {
        switch ($Sort) {
            case 0:
                $Sort = '-_id';
                break;
            case 1:
                $Sort = '_id';
                break;
            case 2:
                $Sort = 'price';
                break;
            case 3:
                $Sort = '-price';
                break;
            case 4:
                $Sort = 'buy_count';
                break;
            case 5:
                $Sort = '-buy_count';
                break;
            case 6:
                $Sort = 'operate_force';
                break;
            case 7:
                $Sort = '-operate_force';
                break;
            case 8:
                $Sort = 'bottomout_force';
                break;
            case 9:
                $Sort = '-bottomout_force';
                break;
            case 10:
                $Sort = 'operate_travel';
                break;
            case 11:
                $Sort = '-operate_travel';
                break;
            case 12:
                $Sort = 'total_travel';
                break;
            case 13:
                $Sort = '-total_travel';
                break;
        }
        $Parameters['order_type'] = $Sort;
    }
    $KeySwitchesJson = json_decode($Auth->Curl('GET', 'https://ysyswitch.top/switch_filter', $Parameters), true);
    $KeySwitchess = [];
    for ($i = 0; $i < count($KeySwitchesJson['data']); $i++) {
        $KeySwitches = [
            'BottomoutForce' => $KeySwitchesJson['data'][$i]['bottomout_force'],
            'Cost' => $KeySwitchesJson['data'][$i]['cost'],
            'Factory' => $KeySwitchesJson['factory'][$KeySwitchesJson['data'][$i]['factory']],
            'Image' => $KeySwitchesJson['data'][$i]['image_url'],
            'Light' => $KeySwitchesJson['light'][$KeySwitchesJson['data'][$i]['light']],
            'Lube' => $KeySwitchesJson['lube'][$KeySwitchesJson['data'][$i]['lube']],
            'MaterialBottom' => $KeySwitchesJson['data'][$i]['material_bottom'],
            'MaterialStem' => $KeySwitchesJson['data'][$i]['material_stem'],
            'MaterialTop' => $KeySwitchesJson['data'][$i]['material_top'],
            'OperateForce' => $KeySwitchesJson['data'][$i]['operate_force'],
            'OperateTravel' => $KeySwitchesJson['data'][$i]['operate_travel'],
            'Price' => $KeySwitchesJson['data'][$i]['price'],
            'Source' => $KeySwitchesJson['source'][$KeySwitchesJson['data'][$i]['source']],
            'TotalTravel' => $KeySwitchesJson['data'][$i]['total_travel'],
            'Type' => $KeySwitchesJson['type'][$KeySwitchesJson['data'][$i]['type']]
        ];
        $KeySwitchess[] = $KeySwitches;
    }
    $Response['Data'] = $KeySwitchess;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
