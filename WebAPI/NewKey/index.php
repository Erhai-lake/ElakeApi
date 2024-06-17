<?php
// 新建秘钥
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use Ramsey\Uuid\Uuid;

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    if ($MySQL !== null) {
        $SQL = 'SELECT APPID, UserID FROM APPs WHERE UserID = ?';
        $STMT = $MySQL->prepare($SQL);
        $STMT->bind_param('s', $APPRow['UserID']);
        $STMT->execute();
        $Result = $STMT->get_result();
        $STMT->close();
        if ($Result->num_rows < $UserRow['LimitAPP']) {
            $SecretID = UUID4();
            $SecretKey = UUID5($SecretID);
            $SQL = 'INSERT INTO APPs (UserID, SecretID, SecretKey, AccessControl, Switch) VALUES (?, ?, ?, 0, 0)';
            $STMT = $MySQL->prepare($SQL);
            $STMT->bind_param('sss', $UserRow['UserID'], $SecretID, $SecretKey);
            $STMT->execute();
            $STMT->close();
            $Response['Data'] = [
                'SecretID' => $SecretID,
                'SecretKey' => $SecretKey
            ];
        } else {
            $Auth->Custom('您最多只能创建' . $UserRow['LimitAPP'] . '个应用');
        }
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
