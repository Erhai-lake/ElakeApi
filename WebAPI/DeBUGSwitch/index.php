<?php
// DeBUG开关
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    if ($MySQL !== null) {
        if ($APPRow['DeBUG'] === 0) {
            $DeBUG = 1;
            $Data = '已开启';
        } else {
            $DeBUG = 0;
            $Data = '已关闭';
        }
        $SQL = 'UPDATE APPs SET DeBUG = ? WHERE SecretID = ? AND SecretKey = ?';
        $STMT = $MySQL->prepare($SQL);
        $STMT->bind_param('iss', $DeBUG, $Authenticat['SecretID'], $Authenticat['SecretKey']);
        $STMT->execute();
        $STMT->close();
        $Response['Data'] = $Data;
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
