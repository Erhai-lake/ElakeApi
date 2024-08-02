<?php
// 注销账号
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    if ($MySQL !== null) {
        // 删除日志
        $SQL = 'DELETE FROM APILog WHERE UserID = ?';
        $STMT = $MySQL->prepare($SQL);
        $STMT->bind_param('i', $APPRow['UserID']);
        $STMT->execute();
        $Result = $STMT->get_result();
        $STMT->close();
        // 删除应用
        $SQL = 'DELETE FROM APPs WHERE UserID = ?';
        $STMT = $MySQL->prepare($SQL);
        $STMT->bind_param('i', $APPRow['UserID']);
        $STMT->execute();
        $Result = $STMT->get_result();
        $STMT->close();
        // 删除用户
        $SQL = 'DELETE FROM Users WHERE UserID = ?';
        $STMT = $MySQL->prepare($SQL);
        $STMT->bind_param('i', $APPRow['UserID']);
        $STMT->execute();
        $Result = $STMT->get_result();
        $STMT->close();
        $Response['注销完毕'];
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
