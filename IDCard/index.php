<?php
// 身份证信息查询
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 身份证号码
  $IDCard = (string)$Auth->PCREParameters($Auth->StringParameters('IDCard'), '/^\d{17}[\dXx]$/i', true);
}

if ($ValidRequest) {
  $IDCard = strtoupper($IDCard);
  $Wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
  $Ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
  $Sigma = 0;
  for ($I = 0; $I < 17; $I++) {
    $Sigma += ($IDCard[$I] * $Wi[$I]);
  }
  $LastChar = $Ai[$Sigma % 11];
  if ($LastChar !== $IDCard[17]) {
    $Auth->Abnormal();
  }
  $Response['Data'] = array(
    'Gender' => Gender(),
    'Age' => Age(),
    'Birthdate' => Birthdate(),
    'Birthday' => Birthday(),
    'Zodiac' => Zodiac(),
    'ZodiacSign' => ZodiacSign(),
    'Region' => Region(),
    'City' => City(),
  );
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);

// 性别
function Gender(): string
{
  global $IDCard;
  $GenderDigit = intval(substr($IDCard, -2, 1));
  if ($GenderDigit % 2 === 0) {
    return '女';
  } else {
    return '男';
  }
}

// 年龄
function Age(): int
{
  global $IDCard;
  $BirthYear = substr($IDCard, 6, 4);
  $BirthMonth = substr($IDCard, 10, 2);
  $BirthDay = substr($IDCard, 12, 2);
  $CurrentYear = date('Y');
  $CurrentMonth = date('m');
  $CurrentDay = date('d');
  $Age = $CurrentYear - $BirthYear;
  if ($CurrentMonth < $BirthMonth || ($CurrentMonth == $BirthMonth && $CurrentDay < $BirthDay)) {
    $Age--;
  }
  return $Age;
}

// 生日
function Birthday(): string
{
  global $IDCard;
  $Month = substr($IDCard, 10, 2);
  $Day = substr($IDCard, 12, 2);
  $CurrentYear = date('Y');
  $CurrentMonth = date('m');
  $CurrentDay = date('d');
  $NextBirthdayYear = $CurrentYear;
  if ($CurrentMonth > $Month || ($CurrentMonth == $Month && $CurrentDay > $Day)) {
    $NextBirthdayYear++;
  }
  $NextBirthday = $NextBirthdayYear . '-' . $Month . '-' . $Day;
  return $NextBirthday;
}

// 降临日
function Birthdate(): string
{
  global $IDCard;
  $Year = substr($IDCard, 6, 4);
  $Mmonth = substr($IDCard, 10, 2);
  $Day = substr($IDCard, 12, 2);
  $Birthdate = $Year . '-' . $Mmonth . '-' . $Day;
  return $Birthdate;
}

// 生肖
function Zodiac(): string
{
  global $IDCard;
  $Year = substr($IDCard, 6, 4);
  $Zodiacs = array(
    '鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪'
  );
  $ZodiacIndex = ($Year - 1900) % 12;
  return $Zodiacs[$ZodiacIndex];
}

// 星座
function ZodiacSign(): string
{
  global $IDCard;
  $Month = substr($IDCard, 10, 2);
  $Day = substr($IDCard, 12, 2);
  $ZodiacSigns = [
    ['Name' => '摩羯座', 'Start' => '01-01', 'End' => '01-19'],
    ['Name' => '水瓶座', 'Start' => '01-20', 'End' => '02-18'],
    ['Name' => '双鱼座', 'Start' => '02-19', 'End' => '03-20'],
    ['Name' => '白羊座', 'Start' => '03-21', 'End' => '04-19'],
    ['Name' => '金牛座', 'Start' => '04-20', 'End' => '05-20'],
    ['Name' => '双子座', 'Start' => '05-21', 'End' => '06-20'],
    ['Name' => '巨蟹座', 'Start' => '06-21', 'End' => '07-22'],
    ['Name' => '狮子座', 'Start' => '07-23', 'End' => '08-22'],
    ['Name' => '处女座', 'Start' => '08-23', 'End' => '09-22'],
    ['Name' => '天秤座', 'Start' => '09-23', 'End' => '10-22'],
    ['Name' => '天蝎座', 'Start' => '10-23', 'End' => '11-21'],
    ['Name' => '射手座', 'Start' => '11-22', 'End' => '12-21'],
    ['Name' => '摩羯座', 'Start' => '12-22', 'End' => '12-31']
  ];
  $Birthdate = $Month . '-' . $Day;
  foreach ($ZodiacSigns as $Sign) {
    $Start = $Sign['Start'];
    $End = $Sign['End'];
    if (($Birthdate >= $Start && $Birthdate <= $End) || ($Start > $End && ($Birthdate >= $Start || $Birthdate <= $End))) {
      return $Sign['Name'];
    }
  }
  return '未知';
}

// 所属地(区域)
function Region(): string
{
  global $IDCard;
  $RegionCode = substr($IDCard, 0, 2);
  $RegionMap = json_decode(file_get_contents('regionCode.json'), true);
  if (isset($RegionMap[$RegionCode][$RegionCode])) {
    return $RegionMap[$RegionCode][$RegionCode];
  } else {
    return '未知地区';
  }
}

// 所属地(城市)
function City(): string
{
  global $IDCard;
  $RegionCode1 = substr($IDCard, 0, 2);
  $RegionCode2 = substr($IDCard, 2, 4);
  $RegionMap = json_decode(file_get_contents('regionCode.json'), true);
  if (isset($RegionMap[$RegionCode1][$RegionCode2])) {
    return $RegionMap[$RegionCode1][$RegionCode2];
  } else {
    return '未知地区';
  }
}
