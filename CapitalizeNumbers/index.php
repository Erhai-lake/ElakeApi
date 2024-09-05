<?php
// 数字大写
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
    // 数字
    $Num = (string)$Auth->StringParameters('Num', 114514);
    if (strlen($Num) > 12) {
        $Auth->Return(6, '位数不能超过千亿');
    }
}

if ($ValidRequest) {
    $Char = array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
    $Unit = array('', '拾', '佰', '仟');
    $Section = array('', '万', '亿');
    $Retval = '';
    $Zero = false;

    // 转换为字符串，便于处理
    $NumStr = (string)$Num;

    // 遍历每一位数字
    for ($i = 0; $i < strlen($NumStr); $i++) {
        // 当前数字
        $digit = $NumStr[$i];
        // 当前数字的大写
        $char = $Char[$digit];
        // 当前位数的单位
        $unitIndex = strlen($NumStr) - $i - 1;
        // 当前单位
        $unit = $Unit[$unitIndex % 4];
        // 当前节
        $sectionIndex = (int)($unitIndex / 4);
        $section = $Section[$sectionIndex];

        // 添加单位
        if ($digit != '0') {
            $Retval .= $char . $unit;
            $Zero = false;
        } elseif (!$Zero) {
            $Retval .= '零';
            $Zero = true;
        }

        // 添加节单位
        if ($unitIndex % 4 == 0 && $unitIndex != 0) {
            $Retval .= $section;
            $Zero = false; // 重置零的标记
        }
    }

    // 替换多个零为一个
    $Retval = preg_replace('/零{2,}/', '零', $Retval);
    // 替换零开头的节
    $Retval = preg_replace('/零([拾佰仟万亿兆])/', '\\1', $Retval);
    // 替换结尾的零
    $Retval = preg_replace('/零$/', '', $Retval);
    // 替换亿万为亿
    $Retval = preg_replace('/亿万/', '亿', $Retval);

    $Response['Data'] = $Retval;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
