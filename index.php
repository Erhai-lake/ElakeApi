<?php
// 服务测试
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
  $Response['Data'] = (string)round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 2) . 'ms';
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
