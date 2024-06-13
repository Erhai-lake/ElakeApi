<?php
// 新历转农历
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

use Overtrue\ChineseCalendar\Calendar;

if ($Auth->Authenticate()) {
    // 日期
    $Date = (string)$Auth->StringParameters('Date', date('Y-m-d'));
}

if ($ValidRequest) {
    date_default_timezone_set('PRC');
    $Calendar = new Calendar();
    $DateArray = explode('-', $Date);
    $Data = $Calendar->solar($DateArray[0], $DateArray[1], $DateArray[2]);
    $Response['Data'] = $Data['lunar_month_chinese'] . $Data['lunar_day_chinese'];
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
