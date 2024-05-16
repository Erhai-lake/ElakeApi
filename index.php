<?php
// 延迟
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
  $Authenticat = substr($_SERVER['HTTP_AUTHORIZATION'], 7);
  $Authenticat = base64_decode($Authenticat);
  $Authenticat = json_decode($Authenticat, true);
  $Timestamp = $Authenticat['Timestamp'];
  $Response['Data'] = time() - $Timestamp;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
