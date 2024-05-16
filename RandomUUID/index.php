<?php
// 随机UUID生成
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use Ramsey\Uuid\Uuid;

if ($Auth->Authenticate()) {
  // UUID版本
  $Version = (int)$Auth->WhitelistParameters('Version', [1, 4]);
  // 数量
  $Limit = (int)$Auth->RangeIntParameters('Limit', 1, 1000);
  // 类型
  $Type = (int)$Auth->RangeIntParameters('Type', 1, 2);
}

if ($ValidRequest) {
  if ($Version === 1) {
    $UUIDs = UUIDv1();
  } else {
    $UUIDs = UUIDv4();
  }
  $Response['Data'] = $UUIDs;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);

function UUIDv1(): array
{
  global $Limit, $Type;
  $UUIDs = [];
  for ($I = 0; $I < $Limit; $I++) {
    $UUID = Uuid::uuid1();
    if ($Type === 1) {
      $UUIDString = strtolower($UUID->toString());
    } else {
      $UUIDString = strtoupper($UUID->toString());
    }
    $UUIDs[] = $UUIDString;
  }
  return $UUIDs;
}

function UUIDv4(): array
{
  global $Limit, $Type;
  $UUIDs = [];
  for ($I = 0; $I < $Limit; $I++) {
    $UUID = Uuid::uuid4();
    if ($Type === 1) {
      $UUIDString = strtolower($UUID->toString());
    } else {
      $UUIDString = strtoupper($UUID->toString());
    }

    $UUIDs[] = $UUIDString;
  }
  return $UUIDs;
}
