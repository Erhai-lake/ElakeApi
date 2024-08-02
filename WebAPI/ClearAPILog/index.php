<?php
// 清空API日志
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    if ($MySQL !== null) {
        $SQL = 'DELETE FROM APILog WHERE APPID = ?';
        $STMT = $MySQL->prepare($SQL);
        $STMT->bind_param('i', $APPRow['APPID']);
        $STMT->execute();
        $Result = $STMT->get_result();
        $STMT->close();
        $Response['清除完毕'];
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
