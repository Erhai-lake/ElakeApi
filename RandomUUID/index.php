<?php
// 随机UUID生成
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use Ramsey\Uuid\Uuid;

if ($Auth->Authenticate(true)) {
    // UUID版本
    $Version = (int)$Auth->WhitelistParameters('Version', [1, 4, 5], 4);
    // 数量
    $Limit = (int)$Auth->RangeIntParameters('Limit', 1, 1000, 1);
    // 类型
    $Type = (int)$Auth->RangeIntParameters('Type', 1, 2, 2);
    // UUID5要的名字
    $Value = (string)$Auth->StringParameters('Value', 'Elake');
}

if ($ValidRequest) {
    switch ($Version) {
        case 1:
            $UUIDs = UUIDv1();
            break;
        case 4:
            $UUIDs = UUIDv4();
            break;
        case 5:
            $UUIDs = UUIDv5($Value);
            break;
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

function UUIDv5(String $Value): array
{
    global $Limit, $Type;
    $UUIDs = [];
    for ($I = 0; $I < $Limit; $I++) {
        $UUID = Uuid::uuid5('6ba7b810-9dad-11d1-80b4-00c04fd430c8', $Value);
        if ($Type === 1) {
            $UUIDString = strtolower($UUID->toString());
        } else {
            $UUIDString = strtoupper($UUID->toString());
        }

        $UUIDs[] = $UUIDString;
    }
    return $UUIDs;
}
