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
        $SecretKey = UUID5(UUID4());
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

function UUID4(): string
{
    $UUID = Uuid::uuid4();
    $UUIDString = strtoupper($UUID->toString());
    return $UUIDString;
}

function UUID5(String $Value): string
{
    $UUID = Uuid::uuid5('6ba7b810-9dad-11d1-80b4-00c04fd430c8', $Value);
    $UUIDString = strtoupper($UUID->toString());
    return $UUIDString;
}
