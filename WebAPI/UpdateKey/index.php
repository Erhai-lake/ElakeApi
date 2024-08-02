<?php
// 更新秘钥
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use Ramsey\Uuid\Uuid;

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    if ($MySQL !== null) {
        $SecretKey = NewSecretKey($Authenticat['SecretID']);
        $SQL = 'UPDATE APPs SET SecretKey = ? WHERE SecretID = ? AND SecretKey = ?';
        $STMT = $MySQL->prepare($SQL);
        $STMT->bind_param('sss', $SecretKey, $Authenticat['SecretID'], $Authenticat['SecretKey']);
        $STMT->execute();
        $STMT->close();
        $Response['Data'] = $SecretKey;
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);

function NewSecretKey(String $Value): string
{
    $UUIDv4 = Uuid::uuid4();
    $UUIDv4String = strtoupper($UUIDv4->toString());
    $UUID = Uuid::uuid5($Value, $UUIDv4String);
    $UUIDString = strtoupper($UUID->toString());
    return $UUIDString;
}
