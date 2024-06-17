<?php
// 新冠肺炎疫情殉职名单
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate(true)) {
}

if ($ValidRequest) {
    $Response['Data'] = json_decode(file_get_contents('Data.json'), true);
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
