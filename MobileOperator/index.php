<?php
// 手机号运营商查询
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 电话号码
  $PhoneNumber = (string)$Auth->PCREParameters($Auth->StringParameters('PhoneNumber'), '/^1\d{10}$/', true);
}

if ($ValidRequest) {
  if (preg_match('/^(13[0-3])\d{8}$/', $PhoneNumber)) {
    $MobileOperator = '中国联通';
    $MobileOperatorImg = $Auth->CurrentURL() . '/Main/PhoneNumber/LianTong.png';
  } elseif (preg_match('/^((13[5-9])|(134[0-8]))\d{7}$/', $PhoneNumber)) {
    $MobileOperator =  '中国移动';
    $MobileOperatorImg = $Auth->CurrentURL() . '/Main/PhoneNumber/YiDong.png';
  } else {
    $MobileOperator =  '中国电信';
    $MobileOperatorImg = $Auth->CurrentURL() . '/Main/PhoneNumber/DianXin.png';
  }
  $Response['Data'] = array(
    'MobileOperator' => $MobileOperator,
    'MobileOperatorImg' => $MobileOperatorImg
  );
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
