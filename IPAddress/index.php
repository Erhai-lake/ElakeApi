<?php
// 获取本机IP
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    $Response['Data'] = (string)$_SERVER['REMOTE_ADDR'];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
