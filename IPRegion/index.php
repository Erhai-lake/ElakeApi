<?php
// IP所属地查询
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();
require_once 'XdbSearcher.php';
$IPDBFile = "ip2region.xdb";

if ($Auth->Authenticate()) {
    // IP地址
    $IP = (string)$Auth->StringParameters('IP');
}

if ($ValidRequest) {
    try {
        $Searcher = XdbSearcher::newWithFileOnly($IPDBFile);
    } catch (Exception $E) {
        $Auth->Return(6, '无法创建搜索器');
    }
    if ($ValidRequest) {
        $STime = XdbSearcher::now();
        $Region = $Searcher->search($IP);
        if ($Region === null) {
            $Auth->Return(6, '搜索失败');
        } else {
            $Region .= '|' . (string)round(XdbSearcher::now() - $STime, 2) . 'ms';
            $Region = explode('|', $Region);
            $Response['Data'] = [
                'Countries' => $Region[0],
                'Regional' => $Region[1],
                'Provinces' => $Region[2],
                'City' => $Region[3],
                'ISP' => $Region[4],
                'Took' => $Region[5]
            ];
        }
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
