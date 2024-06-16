<?php
// 背景图片
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    $Type = $Auth->RangeIntParameters('Type', 1, 3, 1);
    $URl = file_get_contents('URL.json');
    switch ($Type) {
        case 1:
            $URl = json_decode($URl, true)['PC'];
            break;
        case 2:
            $URl = json_decode($URl, true)['PE'];
            break;
        case 3:
            $URl = json_decode($URl, true)['Avatar'];
            break;
    }
    $Source = $Auth->RangeIntParameters('Source', 0, count($URl), 0);
}

if ($ValidRequest) {
    $Data = '图床异常';
    if ($Source === 0) {
        foreach ($URl as $Item) {
            $Curl = curl_init();
            curl_setopt($Curl, CURLOPT_URL, $Item[0]);
            curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($Curl, CURLOPT_TIMEOUT_MS, 1500);
            curl_exec($Curl);
            $StatusCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
            curl_close($Curl);
            if ($StatusCode >= 200 && $StatusCode < 300) {
                $Data = $Item;
                break;
            }
        }
    } else {
        $Data = $URl[$Source - 1];
    }
    if ($Data !== '图床异常') {
        header('Content-Type: PNG');
        echo $Auth->Curl($Data[0]);
    } else {
        $Auth->Custom('图床异常');
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
