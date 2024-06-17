<?php
// 删除秘钥
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    if ($MySQL !== null) {
        $SQL = "SELECT APPID, UserID FROM APPs WHERE UserID = '$APPRow[UserID]'";
        $Result = $MySQL->query($SQL);
        $KeyNum = $Result->num_rows;
        if ($KeyNum > 1) {
            $SQL = 'DELETE FROM APPs WHERE SecretID = ? AND SecretKey = ?';
            $STMT = $MySQL->prepare($SQL);
            $STMT->bind_param('ss', $Authenticat['SecretID'], $Authenticat['SecretKey']);
            $STMT->execute();
            $STMT->close();
            $Response['Data'] = '删除成功';
        } else {
            $Auth->Custom('您必须保留一个密钥');
        }
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
