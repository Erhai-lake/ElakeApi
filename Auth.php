<?php
// 用户验证
// error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
$Dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$Dotenv->load();

$ValidRequest  = null;
$Response  = null;
$MySQL = null;
$Redis = null;
$APPRow = null;

class Auth
{
  // 初始化
  public function Initialization(): void
  {
    global $ValidRequest, $Response, $MySQL, $Redis;
    http_response_code(401);
    $ValidRequest = false;
    $Response = [
      'Code' => 1,
      'Message' => '非法请求',
      'Data' => [],
      'Tips' => 'API接口由洱海工作室(https://www.elake.top)免费提供',
      'Timestamp' => time()
    ];
    $MySQL = $this->DatabaseEstablishesConnection();
    $Redis = $this->CacheEstablishesConnection();
  }

  // 结束
  public function End(): void
  {
    global $MySQL, $Redis;
    if ($MySQL === null) {
      return;
    }
    if ($Redis === null) {
      return;
    }
    $MySQL->close();
    $Redis->close();
  }

  // 身份验证
  public function Authenticate($DeBUG = false): bool
  {
    global $MySQL, $Redis, $APPRow;
    if ($DeBUG) {
      // !DeBUG请求头,上线前注释!
      // $this->Normal(false);
      // return true;
    } else {
      // !DeBUG请求头,上线前注释!
      // if ($_SERVER['HTTP_DEBUG'] == 'true') {
      //   $this->Normal(false);
      //   return true;
      // }
      // 数据库连接失败
      if ($MySQL === null) {
        return false;
      }
      // 缓存连接失败
      if ($Redis === null) {
        return false;
      }
      // Authorization,Bearer不存在
      if (!isset($_SERVER['HTTP_AUTHORIZATION']) || strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer') !== 0) {
        return false;
      }
      // 认证数据解析
      $Authenticat = substr($_SERVER['HTTP_AUTHORIZATION'], 7);
      $Authenticat = base64_decode($Authenticat);
      $Authenticat = json_decode($Authenticat, true);
      // 对象数量不等于4
      if (count($Authenticat) !== 4) {
        return false;
      }
      // SecretID为空
      if (!isset($Authenticat['SecretID']) || empty($Authenticat['SecretID'])) {
        return false;
      }
      $SecretID = $Authenticat['SecretID'];
      // SecretKey为空
      if (!isset($Authenticat['SecretKey']) || empty($Authenticat['SecretKey'])) {
        return false;
      }
      $SecretKey = $Authenticat['SecretKey'];
      // Deprecated为空
      if (!isset($Authenticat['Deprecated']) || empty($Authenticat['Deprecated'])) {
        return false;
      }
      $Deprecated = $Authenticat['Deprecated'];
      // Timestamp为空
      if (!isset($Authenticat['Timestamp']) || empty($Authenticat['Timestamp'])) {
        return false;
      }
      $Timestamp = $Authenticat['Timestamp'];
      // Deprecated不是UUIDv4
      if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $Deprecated)) {
        return false;
      }
      // 通过SecretID,SecretKey获取APPID,UserID,Switch,AccessControl,LimitIP
      $SQLConditions = "SecretID = '$SecretID' AND SecretKey = '$SecretKey'";
      $SQL = "SELECT * FROM APPs WHERE $SQLConditions";
      $Result = $MySQL->query($SQL);
      // 数据不存在
      if ($Result && $Result->num_rows < 0) {
        return false;
      }
      $APPRow = $Result->fetch_assoc();
      // 通过UserID获取Banned
      $SQLConditions = "UserID = '$APPRow[UserID]'";
      $SQL = "SELECT Banned FROM Users WHERE $SQLConditions";
      $Result = $MySQL->query($SQL);
      // 数据不存在
      if ($Result && $Result->num_rows < 0) {
        return false;
      }
      $UsersRow = $Result->fetch_assoc();
      // API所属者被封禁
      if ((int)$UsersRow['Banned'] !== 0) {
        return false;
      }
      $IP = $_SERVER['REMOTE_ADDR'];
      $LimitIP = explode(',', $APPRow['LimitIP']);
      // 请求者不在白名单中
      if ((int)$APPRow['AccessControl'] === 1) {
        if (!in_array($IP, $LimitIP)) {
          return false;
        }
      }
      // 请求者在黑名单中
      if ((int)$APPRow['AccessControl'] === 2) {
        if (in_array($IP, $LimitIP)) {
          return false;
        }
      }
      // Redis中存在同样的UUID
      $UUID = $APPRow['UserID'] . $Deprecated;
      if ($Redis->exists($UUID)) {
        return false;
      }
      // 写UUID到Redis
      $Redis->set($UUID, time());
      $Redis->expire($UUID, 1 * 24 * 60 * 60);
      // APP没有启用
      if ((int)$APPRow['Switch'] !== 0) {
        return false;
      }
      // 有1分钟时差
      $Difference = time() - $Timestamp;
      if ($Difference > 60) {
        return false;
      }
      $this->Normal();
      return true;
    }
  }

  // 传入字符串参数
  public function StringParameters(string $Name, string $Default = null): string
  {
    if (!isset($_GET[$Name]) || empty($_GET[$Name])) {
      if ($Default === null) {
        $this->Missing();
        return '';
      }
      return (string)$Default;
    } else {
      return (string)$_GET[$Name];
    }
  }

  // 传入数值参数
  public function IntParameters(string $Name, int $Default = null): int
  {
    if (!isset($_GET[$Name])) {
      if ($Default === null) {
        $this->Missing();
        return '';
      }
      return (int)$Default;
    }
    return (int)$_GET[$Name];
  }

  // 传入数值参数(带最大判断)
  public function MaxRangeIntParameters(string $Name, int $Max, int $Default = null): int
  {
    $Value = (int)$this->IntParameters($Name, $Default);
    if ($Value > $Max) {
      $this->Abnormal();
      return '';
    }
    return (int)$Value;
  }

  // 传入数值参数(带最小判断)
  public function MinRangeIntParameters(string $Name, int $Min, int $Default = null): int
  {
    $Value = (int)$this->IntParameters($Name, $Default);
    if ($Value < $Min) {
      $this->Abnormal();
      return '';
    }
    return (int)$Value;
  }

  // 传入数值参数(带最大最小判断)
  public function RangeIntParameters(string $Name, int $Min, int $Max, int $Default = null): int
  {
    $Value = (int)$this->IntParameters($Name, $Default);
    if ($Value < $Min || $Value > $Max) {
      $this->Abnormal();
      return '';
    }
    return (int)$Value;
  }

  // 参数白名单
  public function WhitelistParameters(string $Name, array $Limit): string
  {
    if (!isset($_GET[$Name])) {
      $this->Missing();
      return '';
    } else {
      if (!in_array($_GET[$Name], $Limit)) {
        $this->Abnormal();
        return '';
      }
    }
    return $_GET[$Name];
  }

  // 参数白名单
  public function BlacklistParameters(string $Name, array $Limit): string
  {
    if (!isset($_GET[$Name])) {
      $this->Missing();
      return '';
    } else {
      if (in_array($_GET[$Name], $Limit)) {
        $this->Abnormal();
        return '';
      }
    }
    return $_GET[$Name];
  }

  // 正则参数
  public function PCREParameters(string $Name, string $PCREP): string
  {
    if (!isset($_GET[$Name])) {
      $this->Missing();
      return '';
    } else {
      if (!preg_match($PCREP, $_GET[$Name])) {
        $this->Abnormal();
        return '';
      }
    }
    return $_GET[$Name];
  }

  // 传入Cookie
  public function CookieParameters(string $Name): string
  {
    if (!isset($_COOKIE[$Name]) || empty($_COOKIE[$Name])) {
      $this->Missing();
      return '';
    }
    return $_COOKIE[$Name];
  }

  // 正常
  public function Normal(bool $APILog = true): void
  {
    global $ValidRequest, $Response, $MySQL, $APPRow;
    if ($APILog) {
      if ($MySQL === null) {
        return;
      }
      $this->APILog($MySQL, (int)$APPRow['APPID'], (int)$APPRow['UserID'], '成功');
    }
    http_response_code(200);
    $ValidRequest = true;
    $Response['Code'] = 0;
    $Response['Message'] = '成功';
    return;
  }

  // 参数缺失
  public function Missing(): void
  {
    global $ValidRequest, $Response, $MySQL, $APPRow;
    if ($MySQL === null) {
      return;
    }
    $this->APILog($MySQL, (int)$APPRow['APPID'], (int)$APPRow['UserID'], '参数缺失');
    http_response_code(400);
    $ValidRequest = false;
    $Response['Code'] = 2;
    $Response['Message'] = '参数缺失';
    return;
  }

  // 参数异常
  public function Abnormal(): void
  {
    global $ValidRequest, $Response, $MySQL, $APPRow;
    if ($MySQL === null) {
      return;
    }
    $this->APILog($MySQL, (int)$APPRow['APPID'], (int)$APPRow['UserID'], '参数异常');
    http_response_code(400);
    $ValidRequest = false;
    $Response['Code'] = 3;
    $Response['Message'] = '参数异常';
    return;
  }

  // 自定义200错误消息
  public function Custom(string $Message): void
  {
    global $ValidRequest, $Response, $MySQL, $APPRow;
    if ($MySQL === null) {
      return;
    }
    $this->APILog($MySQL, (int)$APPRow['APPID'], (int)$APPRow['UserID'], $Message);
    http_response_code(200);
    $ValidRequest = false;
    $Response['Code'] = 5;
    $Response['Message'] = $Message;
    return;
  }

  // 数据库建立连接
  private function DatabaseEstablishesConnection(): ?mysqli
  {
    global $ValidRequest, $Response;
    $MySQL = new mysqli($_ENV['MySQLHostName'], $_ENV['MySQLUserName'], $_ENV['MySQLPassword'], $_ENV['MySQLDatabase'], $_ENV['MySQLPort']);
    if (!$MySQL->connect_error) {
      if ($MySQL->ping()) {
        return $MySQL;
      }
    }
    http_response_code(500);
    $ValidRequest = false;
    $Response['Code'] = 4;
    $Response['Message'] = '服务器错误';
    return null;
  }

  // 缓存建立连接
  private function CacheEstablishesConnection(): ?redis
  {
    global $ValidRequest, $Response;
    $Redis = new Redis();
    if ($Redis->connect($_ENV['RedisHostName'], $_ENV['RedisHostPort'])) {
      $Redis->auth($_ENV['RedisPassword']);
      if ($Redis->ping()) {
        return $Redis;
      }
    }
    http_response_code(500);
    $ValidRequest = false;
    $Response['Code'] = 4;
    $Response['Message'] = '服务器错误';
    return null;
  }

  // 写API日志
  private function APILog(mysqli $MySQL, int $APPID, int $UserID, string $Message): void
  {
    $IP = $_SERVER['REMOTE_ADDR'];
    $CurrentURL = explode('?', $this->CurrentURL() . $_SERVER['REQUEST_URI'])[0];
    $SQL = 'INSERT INTO APILog (APPID, UserID, IP, DateTime, Message, Url) VALUES (?, ?, ?, ?, ?, ?)';
    $STMT = $MySQL->prepare($SQL);
    $STMT->bind_param('ssssss', $APPID, $UserID, $IP, date('Y-m-d H:i:s'), $Message, $CurrentURL);
    $STMT->execute();
    $STMT->close();
  }

  // 获取当前网址
  public function CurrentURL(): string
  {
    $Protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $Host = $_SERVER['HTTP_HOST'];
    $CurrentURL = $Protocol . "://" . $Host;
    return $CurrentURL;
  }
}
