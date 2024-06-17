<?php
// 用户请求日志
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
}

if ($ValidRequest) {
    if ($MySQL !== null) {
        $SQL = 'SELECT* FROM APILog WHERE UserID = ?';
        $STMT = $MySQL->prepare($SQL);
        $STMT->bind_param('s', $APPRow['UserID']);
        $STMT->execute();
        $Result = $STMT->get_result();
        $STMT->close();
        if ($Result->num_rows > 0) {
            $Data = [];
            while ($Row = $Result->fetch_assoc()) {
                $Data[] = $Row;
            }
            $Response['Data'] = $Data;
        } else {
            $Auth->Custom('暂无数据');
        }
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
