<?php
// TODO 用户验证
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
$Dotenv = Dotenv\Dotenv::createImmutable(str_replace('/index', '', str_replace('\index', '', __DIR__)));
$Dotenv->load();

$Code = json_decode(file_get_contents(str_replace('/index', '/', str_replace('\index', '\\', __DIR__)) . 'Code.json'), true);
$ValidRequest = null;
$Response = [
    'Code' => 0,
    'Message' => '',
    'Data' => [],
    'Tips' => 'API接口由洱海工作室(https://www.elake.top)免费提供',
    'Version' => $_ENV['Version'],
    'Timestamp' => time()
];
$Authenticat = null;
$MySQL = null;
$Redis = null;
$APPRow = null;
$UserRow = null;

class Auth
{
    // TODO 初始化
    public function Initialization(): void
    {
        global $MySQL, $Redis;
        if ($_ENV['DeBUG'] === 'true') {
            $this->Return(7);
        } else {
            $this->Return(1);
            $MySQL = $this->DatabaseEstablishesConnection();
            $Redis = $this->CacheEstablishesConnection();
        }
    }

    // TODO 结束
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

    // TODO 身份验证
    public function Authenticate(bool $AuthenticateSwitch = false): bool
    {
        global $MySQL, $Redis, $Authenticat, $APPRow, $UserRow;
        // 数据库连接失败
        if ($MySQL === null) {
            return false;
        }
        // 缓存连接失败
        if ($Redis === null) {
            return false;
        }
        if ($AuthenticateSwitch) {
            $this->Return(0);
            return true;
        } else {
            // User-Agent,Authorization,Bearer不存在
            if (!isset($_SERVER['HTTP_USER_AGENT']) || !isset($_SERVER['HTTP_AUTHORIZATION']) || strpos($_SERVER['HTTP_AUTHORIZATION'], 'Bearer') !== 0) {
                return false;
            }
            // 认证数据解析
            $Authenticat = substr($_SERVER['HTTP_AUTHORIZATION'], 7);
            $Authenticat = base64_decode($Authenticat);
            $Authenticat = json_decode($Authenticat, true);
            // 对象数量不等于4
            if (count($Authenticat) !== 3) {
                return false;
            }
            // SecretID为空
            if (!isset($Authenticat['SecretID']) || empty($Authenticat['SecretID'])) {
                return false;
            }
            $SecretID = $Authenticat['SecretID'];
            // DS为空
            if (!isset($Authenticat['DS']) || empty($Authenticat['DS'])) {
                return false;
            }
            $DS = $Authenticat['DS'];
            // Deprecated为空
            if (!isset($Authenticat['Deprecated']) || empty($Authenticat['Deprecated'])) {
                return false;
            }
            $Deprecated = $Authenticat['Deprecated'];
            // Deprecated不是UUIDv4
            if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $Deprecated)) {
                return false;
            }
            // 通过SecretID获取APP数据
            $SQL = 'SELECT * FROM APPs WHERE SecretID = ?';
            $STMT = $MySQL->prepare($SQL);
            $STMT->bind_param('s', $SecretID);
            $STMT->execute();
            $Result = $STMT->get_result();
            $STMT->close();
            // 数据不存在
            if ($Result && $Result->num_rows <= 0) {
                return false;
            }
            $APPRow = $Result->fetch_assoc();
            $DS = explode(',', $DS);
            // 有1分钟时差
            $Difference = time() - $DS[0];
            if ($Difference > 60) {
                return false;
            }
            // 校验DS
            $ComputedDS = md5('salt=' . $APPRow['SecretKey'] . '&t=' . $DS[0] . '&r=' . $DS[1]);
            if ($ComputedDS !== $DS[2]) {
                return false;
            }
            // 通过UserID获取User数据
            $SQL = 'SELECT * FROM Users WHERE UserID = ?';
            $STMT = $MySQL->prepare($SQL);
            $STMT->bind_param('i', $APPRow['UserID']);
            $STMT->execute();
            $Result = $STMT->get_result();
            $STMT->close();
            // 数据不存在
            if ($Result && $Result->num_rows < 0) {
                return false;
            }
            $UserRow = $Result->fetch_assoc();
            // API所属者被封禁
            if ((int)$UserRow['Banned'] !== 0) {
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
            $this->Return(0);
            return true;
        }
    }

    // TODO 传入字符串参数
    public function StringParameters(string $Name, string $Default = null)
    {
        if (isset($_GET[$Name]) && !empty($_GET[$Name])) {
            return (string)$_GET[$Name];
        } else {
            if ($Default !== null) {
                return (string)$Default;
            } else {
                $this->Return(2, $Name);
                return;
            }
        }
    }

    // TODO 传入数值参数
    public function IntParameters(string $Name, float $Default = null)
    {
        if (isset($_GET[$Name])) {
            return (float)$_GET[$Name];
        } else {
            if ($Default !== null) {
                return (float)$Default;
            } else {
                $this->Return(2, $Name);
                return;
            }
        }
    }

    // TODO 传入数值参数(带最大判断)
    public function MaxRangeIntParameters(string $Name, float $Max, float $Default = null)
    {
        $Value = (float)$this->IntParameters($Name, $Default);
        if ($Value <= $Max) {
            return (float)$Value;
        } else {
            $this->Return(3, $Name);
            return;
        }
    }

    // TODO 传入数值参数(带最小判断)
    public function MinRangeIntParameters(string $Name, float $Min, float $Default = null)
    {
        $Value = (float)$this->IntParameters($Name, $Default);
        if ($Value >= $Min) {
            return (float)$Value;
        } else {
            $this->Return(3, $Name);
            return;
        }
    }

    // TODO 传入数值参数(带最大最小判断)
    public function RangeIntParameters(string $Name, float $Min, float $Max, float $Default = null)
    {
        $Value = (float)$this->IntParameters($Name, $Default);
        if ($Value >= $Min && $Value <= $Max) {
            return (float)$Value;
        } else {
            $this->Return(3, $Name);
            return;
        }
    }

    // TODO 参数白名单
    public function WhitelistParameters(string $Name, array $Limit, $Default = null)
    {
        if (isset($_GET[$Name])) {
            if (in_array($_GET[$Name], $Limit)) {
                return $_GET[$Name];
            } else {
                $this->Return(3, $Name);
                return;
            }
        } else {
            if ($Default === null) {
                $this->Return(2, $Name);
                return;
            } else {
                return $Default;
            }
        }
    }

    // TODO 参数白名单
    public function BlacklistParameters(string $Name, array $Limit)
    {
        if (isset($_GET[$Name])) {
            if (!in_array($_GET[$Name], $Limit)) {
                return $_GET[$Name];
            } else {
                $this->Return(3, $Name);
                return;
            }
        } else {
            $this->Return(2, $Name);
            return;
        }
    }

    // TODO 正则参数
    public function PCREParameters($Value, string $PCREP, bool $Bool)
    {
        if (preg_match($PCREP, $Value) == $Bool) {
            return $Value;
        } else {
            $this->Return(3);
            return;
        }
    }

    // TODO 传入Cookie
    public function CookieParameters(string $Name)
    {
        if (isset($_COOKIE[$Name]) && !empty($_COOKIE[$Name])) {
            return $_COOKIE[$Name];
        } else {
            $this->Return(2, $Name);
            return;
        }
    }

    // TODO 返回
    public function Return(int $ID, String $Error = null): void
    {
        global $Code, $ValidRequest, $Response, $MySQL, $APPRow;
        $CodeArray = $Code[$ID];
        $Response['Code'] = $CodeArray['Code'];
        if ($APPRow['DeBUG'] === 1 || !isset($APPRow['DeBUG'])) {
            $Response['Message'] = $CodeArray['Message'];
            if ($Error !== null) {
                $Response['Error'][] = [$CodeArray['Message'], $Error];
            }
        } else {
            $Response['Message'] = $CodeArray['Message'];
        }
        $ValidRequest = $CodeArray['ValidRequest'];
        http_response_code($CodeArray['HttpCode']);
        if ($MySQL !== null) {
            $this->APILog($MySQL, (int)$APPRow['APPID'], (int)$APPRow['UserID'], $CodeArray['Message']);
        }
    }

    // TODO 数据库建立连接
    private function DatabaseEstablishesConnection(): ?mysqli
    {
        $MySQL = new mysqli($_ENV['MySQLHost'], $_ENV['MySQLName'], $_ENV['MySQLPassword'], $_ENV['MySQLDatabase'], $_ENV['MySQLPort']);
        if (!$MySQL->connect_error) {
            return $MySQL;
        }
        $this->Return(4);
        return null;
    }

    // TODO 缓存建立连接
    private function CacheEstablishesConnection(): ?redis
    {
        $Redis = new Redis();
        try {
            $Redis->connect($_ENV['RedisHost'], $_ENV['RedisPort']);
            $Redis->auth($_ENV['RedisPassword']);
            return $Redis;
        } catch (Exception $Error) {
            $this->Return(4);
        }
        return null;
    }

    // TODO 写API日志
    private function APILog(mysqli $MySQL, int $APPID, int $UserID, string $Message): void
    {
        if ($MySQL !== null) {
            $IP = $_SERVER['REMOTE_ADDR'];
            $UserAgent = $_SERVER['HTTP_USER_AGENT'];
            $CurrentURL = explode('?', $this->CurrentURL() . $_SERVER['REQUEST_URI'])[0];
            $SQL = 'INSERT INTO APILog (APPID, UserID, IP, DateTime, UserAgent, Message, Url) VALUES (?, ?, ?, ?, ?, ?, ?)';
            $STMT = $MySQL->prepare($SQL);
            $STMT->bind_param('iisssss', $APPID, $UserID, $IP, date('Y-m-d H:i:s'), $UserAgent, $Message, $CurrentURL);
            $STMT->execute();
            $STMT->close();
        }
    }

    // TODO 获取当前网址
    public function CurrentURL(): string
    {
        $Protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $Host = $_SERVER['HTTP_HOST'];
        $CurrentURL = $Protocol . "://" . $Host;
        return $CurrentURL;
    }

    // TODO 请求
    public function Curl(string $Request, string $Url, array $Parameters = [], array $Header = []): string
    {
        $Curl = curl_init();
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($Curl, CURLOPT_HTTPHEADER, $Header);
        switch ($Request) {
            case 'GET':
                curl_setopt($Curl, CURLOPT_URL, $Url . '?' . http_build_query($Parameters));
                break;
            case 'POST':
                curl_setopt($Curl, CURLOPT_URL, $Url);
                curl_setopt($Curl, CURLOPT_POST, true);
                curl_setopt($Curl, CURLOPT_POSTFIELDS, http_build_query($Parameters));
                break;
            default:
                $this->Return(4);
                break;
        }
        $Response = curl_exec($Curl);
        if (curl_errno($Curl)) {
            $this->Return(curl_error($Curl));
            curl_close($Curl);
            $this->Return(5);
            return '';
        }
        $StatusCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
        curl_close($Curl);
        if ($StatusCode >= 200 && $StatusCode < 300) {
            return $Response;
        } else {
            $this->Return(5);
            return '';
        }
    }
}
