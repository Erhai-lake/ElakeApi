<?php
// Bing每日壁纸
require_once $_SERVER['DOCUMENT_ROOT'] . '/Auth.php';
$Auth = new Auth();
$Auth->Initialization();

if ($Auth->Authenticate()) {
  // 页数
  $Page = (int)$Auth->RangeIntParameters('Page', 1, 8);
  // 数量
  $Limit = (int)$Auth->RangeIntParameters('Limit', 1, 8);
}

if ($ValidRequest) {
  $BingJson = json_decode(file_get_contents('https://cn.bing.com/HPImageArchive.aspx?format=js&idx=' . $Page . '&n=' . $Limit . '&mkt=zh-CN'), true)['images'];
  $Data = [];
  foreach ($BingJson as $Item) {
    $Data[] = [
      'StartDate' => (string)$Item['startdate'],
      'EndDate' => (string)$Item['enddate'],
      'Title' => (string)$Item['title'],
      'ImageUrl' => (string)'https://cn.bing.com' . $Item['url'],
      'Copyright' => (string)$Item['copyright'],
      'CopyrightLink' => (string)$Item['copyrightlink']
    ];
  }
  $Response['Data'] = $Data;
}

$Auth->End();

header('Content-Type: application/json');
echo json_encode($Response);
