<?php
// 用户请求日志
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 数量
    $Limit = (int)$Auth->RangeIntParameters('Limit', 1, 50, 10);
    // 页数
    $Page = (int)$Auth->MinRangeIntParameters('Page', 1, 1);
}

if ($ValidRequest) {
    // 计算偏移量
    $Offset = ($Page - 1) * $Limit;
    if ($MySQL !== null) {
        // 获取总记录数
        $SQL = 'SELECT COUNT(*) AS Total FROM APILog WHERE UserID = ?';
        $STMT = $MySQL->prepare($SQL);
        $STMT->bind_param('i', $APPRow['UserID']);
        $STMT->execute();
        $Result = $STMT->get_result();
        $Row = $Result->fetch_assoc();
        $Total = ceil($Row['Total'] / $Limit);
        $STMT->close();
        $SQL = 'SELECT * FROM APILog WHERE UserID = ? LIMIT ? OFFSET ?';
        $STMT = $MySQL->prepare($SQL);
        $STMT->bind_param('iii', $APPRow['UserID'], $Limit, $Offset);
        $STMT->execute();
        $Result = $STMT->get_result();
        $STMT->close();
        if ($Result->num_rows > 0) {
            $Data = [];
            while ($Row = $Result->fetch_assoc()) {
                $Data[] = $Row;
            }
            $Response['Data'] = [
                'APILog' => $Data,
                'Total' => $Total
            ];
        } else {
            $Auth->Return(6, '暂无数据');
        }
    }
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
