<?php
// 构造返回
$Response = [
    'Code' => -1,
    'Message' => '未知错误,请联系管理员',
    'Data' => []
];
$ValidRequest = false;

if (!isset($_GET['Url']) && empty($_GET['Url'])) {
    $Response['Code'] = 1;
    $Response['Message'] = 'zip链接为空';
} else {
    // 下载zip
    $Curl = curl_init();
    curl_setopt($Curl, CURLOPT_URL, $_GET['Url']);
    curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($Curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
    $FileName = basename(parse_url($_GET['Url'], PHP_URL_PATH));
    $DownloadedPath = 'cache/Downloaded/' . $FileName;
    $File = fopen($DownloadedPath, 'w');
    curl_setopt($Curl, CURLOPT_FILE, $File);
    $DownloadResult = curl_exec($Curl);
    curl_close($Curl);
    fclose($File);
    if ($DownloadResult) {
        // 打开zip
        $Zip = new ZipArchive;
        if ($Zip->open($DownloadedPath) === true) {
            // 验证密码
            $Zip->setPassword($_GET['Password']);
            $ValidRequest = true;
        } else {
            $Response['Code'] = 2;
            $Response['Message'] = 'zip文件异常';
        }
    } else {
        $Response['Code'] = 2;
        $Response['Message'] = 'zip获取失败';
    }
}

if ($ValidRequest) {
    // 解压zip
    $Zip->extractTo($CacheDir);
    // 关闭zip
    $Zip->close();
    // 构造zip结构
    $Data = Open($CacheDir);
    if (!empty($Data)) {
        $Response['Code'] = 0;
        $Response['Message'] = '正常';
        $Response['Data'] = $Data;
    }
}

header('Content-Type: application/json');
echo json_encode($Response);

class DirectoryItem
{
    public $Name;
    public $Type;
    public $Children;

    public function __construct($Name, $Type)
    {
        $this->Name = $Name;
        $this->Type = $Type;
        $this->Children = [];
    }
}

function Open($Dir)
{
    $Files = scandir($Dir);
    $Data = new DirectoryItem($Dir, 'directory');
    foreach ($Files as $File) {
        if ($File != '.' && $File != '..') {
            if (is_dir($Dir . '/' . $File)) {
                $Data->Children[] = Open($Dir . '/' . $File);
            } else {
                $Data->Children[] = new DirectoryItem($Dir . '/' . $File, 'file');
            }
        }
    }
    return $Data;
}
